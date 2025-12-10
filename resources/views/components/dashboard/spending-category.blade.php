{{-- resources/views/components/dashboard/spending-chart.blade.php --}}

<div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-gray-100 dark:bg-slate-900 h-full">

    <div class="flex items-start justify-between mb-4">
        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100">Spending by Category</h3>

        <div class="relative">
            <button 
                id="spendingRangeButton"
                class="border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-1 text-sm font-medium flex items-center gap-1 text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800">
                <span id="selectedRangeText">This Month</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div id="spendingRangeMenu"
                 class="absolute right-0 mt-1 w-36 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg hidden z-20">

                <button data-range="this_month"
                    class="range-option block w-full text-left px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300">
                    This Month
                </button>

                <button data-range="this_week"
                    class="range-option block w-full text-left px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300">
                    Last 7 Days
                </button>

                <button data-range="this_year"
                    class="range-option block w-full text-left px-3 py-2 text-sm hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300">
                    This Year
                </button>
            </div>
        </div>
    </div>

    <div id="totalSpendingAmount" class="text-3xl font-medium text-slate-900 dark:text-slate-100 mb-6">
        Rp 0
    </div>

    <div id="spendingChartContainer">
        <div class="relative mb-6 flex items-center justify-center h-48">
            <canvas id="spendingChart"></canvas>
        </div>

        <div id="categoriesList" class="grid grid-cols-2 gap-y-3 gap-x-6">
            <!-- Categories akan di-render di sini -->
        </div>
    </div>

    <div id="noDataMessage" class="flex flex-col items-center justify-center py-12 hidden">
        <p class="text-slate-500">No expenses in this period.</p>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let spendingChartInstance = null;
    let currentRange = 'this_month';

    const canvas = document.getElementById('spendingChart');
    const totalSpendingEl = document.getElementById('totalSpendingAmount');
    const categoriesListEl = document.getElementById('categoriesList');
    const chartContainer = document.getElementById('spendingChartContainer');
    const noDataMessage = document.getElementById('noDataMessage');
    const rangeButton = document.getElementById('spendingRangeButton');
    const rangeMenu = document.getElementById('spendingRangeMenu');
    const selectedRangeText = document.getElementById('selectedRangeText');

    // Toggle dropdown menu
    rangeButton.addEventListener('click', function(e) {
        e.stopPropagation();
        rangeMenu.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!rangeButton.contains(e.target) && !rangeMenu.contains(e.target)) {
            rangeMenu.classList.add('hidden');
        }
    });

    // Function to update chart
    function updateSpendingChart(data) {
        // Update total spending
        totalSpendingEl.textContent = 'Rp ' + data.totalSpending.toLocaleString('id-ID');

        // Show/hide content based on data
        if (data.categories.length === 0) {
            chartContainer.classList.add('hidden');
            noDataMessage.classList.remove('hidden');
            return;
        }

        chartContainer.classList.remove('hidden');
        noDataMessage.classList.add('hidden');

        // Destroy old chart if exists
        if (spendingChartInstance) {
            spendingChartInstance.destroy();
        }

        // Create new chart
        spendingChartInstance = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: data.categories.map(cat => cat.name),
                datasets: [{
                    data: data.categories.map(cat => cat.amount),
                    backgroundColor: data.categories.map(cat => cat.color),
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed;
                                return context.label + ': Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Update categories list
        categoriesListEl.innerHTML = data.categories.map(cat => `
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-sm" style="background-color: ${cat.color}"></div>
                    <span class="text-sm text-slate-700 dark:text-slate-300">${cat.name}</span>
                </div>
                <span class="text-sm font-medium text-slate-900 dark:text-slate-100">${cat.percentage}%</span>
            </div>
        `).join('');
    }

    // Function to fetch spending data
    function fetchSpendingData(range) {
        fetch(`/dashboard/spending-data?range=${range}`, {
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
            updateSpendingChart(data);
        })
        .catch(error => {
            console.error('Error fetching spending data:', error);
        });
    }

    // Handle range selection
    document.querySelectorAll('.range-option').forEach(button => {
        button.addEventListener('click', function() {
            const range = this.getAttribute('data-range');
            currentRange = range;

            // Update button text
            selectedRangeText.textContent = this.textContent.trim();

            // Close menu
            rangeMenu.classList.add('hidden');

            // Fetch new data
            fetchSpendingData(range);

            // Update active state
            document.querySelectorAll('.range-option').forEach(btn => {
                btn.classList.remove('font-bold', 'text-indigo-600');
            });
            this.classList.add('font-bold', 'text-indigo-600');
        });
    });

    // Load initial data
    fetchSpendingData(currentRange);
});
</script>
@endpush