{{-- resources/views/components/dashboard/spending-chart.blade.php --}}
@php
    use Carbon\Carbon;

    // Ambil range dari controller atau query
    $selectedRange = $range ?? request('range', 'this_month');

    // Tentukan start & end date
    switch ($selectedRange) {
        case 'this_week':
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate   = Carbon::now()->endOfDay();
            break;

        case 'this_year':
            $startDate = Carbon::now()->startOfYear();
            $endDate   = Carbon::now()->endOfYear();
            break;

        default:
            $startDate = Carbon::now()->startOfMonth();
            $endDate   = Carbon::now()->endOfMonth();
            break;
    }

    // Ambil transaksi + kategori
    $categorySpending = auth()->user()->transactions()
        ->where('type', 'expense')
        ->whereBetween('date', [$startDate, $endDate])
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

    $categories = $categorySpending->map(function($cat) use ($totalSpending) {
        return [
            'name' => $cat['name'],
            'amount' => $cat['amount'],
            'percentage' => $totalSpending > 0
                ? round(($cat['amount'] / $totalSpending) * 100, 1)
                : 0,
            'color' => $cat['color'],
        ];
    })->toArray();

    // id unik agar Chart.js tidak bentrok
    $chartId = 'spendingChart_' . md5($selectedRange);
@endphp


<div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-gray-100 dark:bg-slate-900 h-full">

    <div class="flex items-start justify-between mb-4">
        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100">Spending by Category</h3>

        <div class="relative">
            <button 
                onclick="document.getElementById('spendingRangeMenu').classList.toggle('hidden')"
                class="border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-1 text-sm font-medium flex items-center gap-1">

                {{ $selectedRange === 'this_month' ? 'This Month' : ($selectedRange === 'this_week' ? 'Last 7 Days' : 'This Year') }}

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div id="spendingRangeMenu"
                 class="absolute right-0 mt-1 w-36 bg-white dark:bg-slate-800 border rounded-lg shadow-lg hidden z-20">

                <a href="?range=this_month"
                    class="block px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 {{ $selectedRange === 'this_month' ? 'font-bold text-indigo-600' : '' }}">
                    This Month
                </a>

                <a href="?range=this_week"
                    class="block px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 {{ $selectedRange === 'this_week' ? 'font-bold text-indigo-600' : '' }}">
                    Last 7 Days
                </a>

                <a href="?range=this_year"
                    class="block px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 {{ $selectedRange === 'this_year' ? 'font-bold text-indigo-600' : '' }}">
                    This Year
                </a>
            </div>
        </div>
    </div>

    <div class="text-3xl font-medium text-slate-900 dark:text-slate-100 mb-6">
        Rp {{ number_format($totalSpending, 0, ',', '.') }}
    </div>

    @if(count($categories) > 0)
        <div class="relative mb-6 flex items-center justify-center h-48">
            <canvas id="{{ $chartId }}"></canvas>
        </div>

        <div class="grid grid-cols-2 gap-y-3 gap-x-6">
            @foreach($categories as $category)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-sm" style="background-color: {{ $category['color'] }}"></div>
                        <span class="text-sm">{{ $category['name'] }}</span>
                    </div>
                    <span class="text-sm font-medium">{{ $category['percentage'] }}%</span>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center py-12">
            <p class="text-slate-500">No expenses in this period.</p>
        </div>
    @endif
</div>


@if(count($categories) > 0)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    const canvas = document.getElementById("{{ $chartId }}");
    if (!canvas) return;

    // Destroy old chart if exists
    if (window["{{ $chartId }}_instance"]) {
        window["{{ $chartId }}_instance"].destroy();
    }

    window["{{ $chartId }}_instance"] = new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: @json(array_column($categories, 'name')),
            datasets: [{
                data: @json(array_column($categories, 'amount')),  // pakai amount, bukan percentage
                backgroundColor: @json(array_column($categories, 'color')),
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });
});
</script>
@endpush
@endif
