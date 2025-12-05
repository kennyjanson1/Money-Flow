{{-- resources/views/components/cashflow/trend-chart.blade.php --}}

@php
    use Carbon\Carbon;
    
    $currentYear = Carbon::now()->year;
    $trendData = [];
    
    // Get last 12 months data
    for ($i = 11; $i >= 0; $i--) {
        $date = Carbon::now()->subMonths($i);
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        $income = auth()->user()->transactions()
            ->where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        
        $expense = auth()->user()->transactions()
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        
        $trendData[] = [
            'month' => $date->format('M'),
            'income' => $income,
            'expense' => $expense,
            'net' => $income - $expense,
        ];
    }
@endphp

<div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-4 md:p-6 bg-white dark:bg-slate-900">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <h3 class="text-lg md:text-xl text-slate-900 dark:text-slate-100">Cash Flow Trend</h3>
        <button class="border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-1.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 flex items-center gap-1 w-fit">
            Last 12 Months
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
    </div>

    <div class="h-64 md:h-80">
        <canvas id="trendChart"></canvas>
    </div>
</div>

@push('scripts')
<script>
    const trendData = @json($trendData);
    
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: trendData.map(d => d.month),
            datasets: [
                {
                    label: 'Income',
                    data: trendData.map(d => d.income),
                    borderColor: '#10b981', // Green-500
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                },
                {
                    label: 'Expense',
                    data: trendData.map(d => d.expense),
                    borderColor: '#ef4444', // Red-500
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: getComputedStyle(document.documentElement).getPropertyValue('color') || '#64748b',
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const value = context.parsed.y;
                            return label + ': Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: '#e2e8f0',
                        drawBorder: false,
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    grid: {
                        color: '#e2e8f0',
                        drawBorder: false,
                    },
                    ticks: {
                        color: '#94a3b8',
                        font: {
                            size: 11
                        },
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            } else if (value >= 1000) {
                                return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                            }
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush