{{-- resources/views/components/dashboard/spending-chart.blade.php --}}

@php
    use Carbon\Carbon;
    
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();
    
    // Get expenses by category for current month
    $categorySpending = auth()->user()->transactions()
        ->where('type', 'expense')
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->with('category')
        ->get()
        ->groupBy('category_id')
        ->map(function($transactions) {
            $category = $transactions->first()->category;
            return [
                'name' => $category->name ?? 'Uncategorized',
                'amount' => $transactions->sum('amount'),
                'color' => $category->color ?? '#6366f1',
            ];
        })
        ->sortByDesc('amount')
        ->values();
    
    $totalSpending = $categorySpending->sum('amount');
    
    // Calculate percentages
    $categories = $categorySpending->map(function($cat) use ($totalSpending) {
        return [
            'name' => $cat['name'],
            'amount' => $cat['amount'],
            'percentage' => $totalSpending > 0 ? round(($cat['amount'] / $totalSpending) * 100, 1) : 0,
            'color' => $cat['color'],
        ];
    })->toArray();
@endphp

<div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-gray-100 dark:bg-slate-900 h-full">
    <div class="flex items-start justify-between mb-4">
        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100">Spending by Category</h3>
        <button class="border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-1 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center gap-1">
            This Month
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>

    <div class="text-3xl font-medium text-slate-900 dark:text-slate-100 mb-6">
        Rp {{ number_format($totalSpending, 0, ',', '.') }}
    </div>

    @if(count($categories) > 0)
        <!-- Donut Chart -->
        <div class="relative mb-6 flex items-center justify-center h-48">
            <canvas id="spendingChart"></canvas>
        </div>

        <!-- Legend -->
        <div class="grid grid-cols-2 gap-y-3 gap-x-6">
            @foreach($categories as $category)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-sm" style="background-color: {{ $category['color'] }}"></div>
                    <span class="text-sm text-slate-600 dark:text-slate-400">{{ $category['name'] }}</span>
                </div>
                <span class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $category['percentage'] }}%</span>
            </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-12 text-center">
            <svg class="w-16 h-16 text-slate-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <p class="text-slate-600 dark:text-slate-400 text-sm">No expenses recorded this month</p>
            <a href="{{ route('transactions.create') }}" class="mt-4 text-indigo-600 dark:text-indigo-400 text-sm font-medium hover:underline">
                Add your first expense
            </a>
        </div>
    @endif
</div>

@if(count($categories) > 0)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('spendingChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json(array_column($categories, 'name')),
                datasets: [{
                    data: @json(array_column($categories, 'percentage')),
                    backgroundColor: @json(array_column($categories, 'color')),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const amount = @json(array_column($categories, 'amount'))[context.dataIndex];
                                return context.label + ': Rp ' + amount.toLocaleString('id-ID') + ' (' + context.parsed + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endif