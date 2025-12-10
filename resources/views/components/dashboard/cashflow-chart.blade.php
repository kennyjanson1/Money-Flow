<div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-gray-100 dark:bg-slate-900">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100">
            Cash Flow Graph
        </h3>

        <!-- RANGE DROPDOWN -->
        <select id="periodSelector"
                class="border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-1 text-sm font-medium
                       text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800">
            <option value="yearly">This Year</option>
            <option value="monthly" selected>This Month</option>
            <option value="weekly">Last 7 Days</option>
        </select>
    </div>

    <!-- Legends -->
    <div class="flex items-center gap-6 mb-6">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-green-500 rounded"></div>
            <span class="text-sm text-slate-600 dark:text-slate-400">Income</span>
        </div>

        <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-red-500 rounded"></div>
            <span class="text-sm text-slate-600 dark:text-slate-400">Expenses</span>
        </div>
    </div>

    <!-- Chart -->
    <div class="h-72">
        <canvas id="cashFlowChart"></canvas>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('cashFlowChart');
    if (!ctx) return;

    let chartInstance = null;

    // Fungsi untuk membuat/update chart
    function updateChart(data) {
        const labels = data.map(item => item.label);
        const income = data.map(item => item.income);
        const expenses = data.map(item => item.expense);

        if (labels.length === 0) {
            console.warn("Cash Flow chart: No data available");
            return;
        }

        // Hancurkan chart lama jika ada
        if (chartInstance) {
            chartInstance.destroy();
        }

        // Buat chart baru
        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Income',
                        data: income,
                        backgroundColor: '#10b981',
                        hoverBackgroundColor: '#059669',
                        borderRadius: 6,
                        barThickness: 20
                    },
                    {
                        label: 'Expenses',
                        data: expenses,
                        backgroundColor: '#ef4444',
                        hoverBackgroundColor: '#dc2626',
                        borderRadius: 6,
                        barThickness: 20
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
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 13, weight: '600' },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function (context) {
                                const label = context.dataset.label || '';
                                const value = context.parsed.y;
                                return label + ': Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11 },
                            callback: function (value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                                }
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        },
                        grid: {
                            color: 'rgba(148,163,184,0.15)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11 }
                        },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Fungsi untuk fetch data dari server
    function fetchCashFlowData(period) {
        fetch(`/cashflow/data?period=${period}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            updateChart(data);
        })
        .catch(error => {
            console.error('Error fetching cash flow data:', error);
        });
    }

    // Load data awal
    fetchCashFlowData('monthly');

    // Event listener untuk dropdown
    const periodSelector = document.getElementById('periodSelector');
    periodSelector.addEventListener('change', function() {
        const selectedPeriod = this.value;
        fetchCashFlowData(selectedPeriod);
    });
});
</script>
@endpush