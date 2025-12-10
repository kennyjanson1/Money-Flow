{{-- resources/views/components/transactions/table-header.blade.php --}}

<div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
    <div>
        <h3 class="text-lg md:text-xl font-semibold text-slate-900 dark:text-slate-100">All Transactions</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1" id="filterStatus">
            <span id="filterText"></span>
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-2">
        <!-- Search Input -->
        <div class="w-full sm:w-64">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="search" id="searchInput" value="{{ request('search') }}"
                    placeholder="Search transactions..."
                    class="pl-10 w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                
                <button type="button" id="clearSearch" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Type Filter -->
        <select id="typeFilter"
            class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">All Types</option>
            <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income</option>
            <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
        </select>

        <!-- Category Filter with Custom Dropdown -->
        <div class="relative inline-block" id="categoryDropdown">
            <button type="button" 
                class="rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 flex items-center gap-2 min-w-[160px] justify-between"
                onclick="toggleCategoryDropdown()">
                <span id="selectedCategoryText">
                    @if(request('category_id'))
                        @php
                            $selectedCat = $categories->firstWhere('id', request('category_id'));
                        @endphp
                        {{ $selectedCat ? $selectedCat->icon . ' ' . $selectedCat->name : 'All Categories' }}
                    @else
                        All Categories
                    @endif
                </span>
                <svg class="w-4 h-4 transition-transform" id="categoryDropdownArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <div id="categoryDropdownMenu" 
                class="hidden absolute top-full left-0 mt-1 w-full min-w-[200px] bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg z-50 max-h-[240px] overflow-y-auto">
                <div class="py-1">
                    <button type="button" 
                        onclick="selectCategory('', 'All Categories')"
                        class="w-full text-left px-4 py-2 text-sm text-slate-900 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-700 category-option"
                        data-category-id="">
                        All Categories
                    </button>
                    @foreach ($categories as $category)
                        <button type="button" 
                            onclick="selectCategory('{{ $category->id }}', '{{ $category->icon }} {{ $category->name }}')"
                            class="w-full text-left px-4 py-2 text-sm text-slate-900 dark:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-700 category-option"
                            data-category-id="{{ $category->id }}">
                            {{ $category->icon }} {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Add Transaction Button -->
        <a href="{{ route('transactions.create') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="hidden sm:inline">Add Transaction</span>
            <span class="sm:hidden">Add</span>
        </a>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-20 z-40 flex items-center justify-center">
    <div class="bg-white dark:bg-slate-800 rounded-lg p-4 shadow-xl">
        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>
</div>

<!-- TABLE DATA -->
<div id="transactionsTable" class="bg-white dark:bg-slate-900 rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Description
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">
                        Category
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden lg:table-cell">
                        Date
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden xl:table-cell">
                        Type
                    </th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        Amount
                    </th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">
                        Action
                    </th>
                </tr>
            </thead>

            <tbody id="transactionsBody" class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse ($transactions as $transaction)
                    @include('components.transactions.table-row')
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-slate-500 dark:text-slate-400 text-base mb-2">No transactions yet</p>
                            <p class="text-slate-400 dark:text-slate-500 text-sm mb-4">Start by adding your first transaction</p>
                            <a href="{{ route('transactions.create') }}" 
                               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Transaction
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div id="paginationContainer">
        @if($transactions->hasPages())
            <div class="px-4 py-4 border-t border-slate-200 dark:border-slate-700">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    // Filter state
    let filters = {
        search: '{{ request("search") }}',
        type: '{{ request("type") }}',
        category_id: '{{ request("category_id") }}'
    };

    let searchTimeout = null;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateFilterStatus();
        updateClearSearchButton();
        
        // Search input with debounce
        document.getElementById('searchInput').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filters.search = e.target.value;
                fetchTransactions();
                updateClearSearchButton();
            }, 500);
        });

        // Clear search button
        document.getElementById('clearSearch').addEventListener('click', function() {
            document.getElementById('searchInput').value = '';
            filters.search = '';
            fetchTransactions();
            updateClearSearchButton();
        });

        // Type filter
        document.getElementById('typeFilter').addEventListener('change', function(e) {
            filters.type = e.target.value;
            fetchTransactions();
        });

        // Handle pagination clicks
        document.addEventListener('click', function(e) {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                const url = e.target.closest('.pagination a').getAttribute('href');
                if (url) {
                    fetchTransactions(url);
                }
            }
        });
    });

    function updateClearSearchButton() {
        const clearBtn = document.getElementById('clearSearch');
        if (filters.search) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
    }

    function toggleCategoryDropdown() {
        const menu = document.getElementById('categoryDropdownMenu');
        const arrow = document.getElementById('categoryDropdownArrow');
        
        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            arrow.style.transform = 'rotate(180deg)';
        } else {
            menu.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    function selectCategory(categoryId, categoryText) {
        filters.category_id = categoryId;
        document.getElementById('selectedCategoryText').textContent = categoryText;
        
        // Update active state
        document.querySelectorAll('.category-option').forEach(option => {
            if (option.dataset.categoryId === categoryId) {
                option.classList.add('bg-indigo-50', 'dark:bg-indigo-900/30');
            } else {
                option.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/30');
            }
        });
        
        toggleCategoryDropdown();
        fetchTransactions();
    }

    function updateFilterStatus() {
        const filterText = document.getElementById('filterText');
        const filterStatus = document.getElementById('filterStatus');
        
        let parts = [];
        
        if (filters.search) {
            parts.push(`Search: "<span class="font-medium">${filters.search}</span>"`);
        }
        if (filters.type) {
            parts.push(`Type: <span class="font-medium">${filters.type.charAt(0).toUpperCase() + filters.type.slice(1)}</span>`);
        }
        if (filters.category_id) {
            const categoryText = document.getElementById('selectedCategoryText').textContent;
            if (categoryText !== 'All Categories') {
                parts.push(`Category: <span class="font-medium">${categoryText}</span>`);
            }
        }
        
        if (parts.length > 0) {
            filterText.innerHTML = 'Showing filtered results • ' + parts.join(' • ') + 
                ' <a href="#" onclick="clearAllFilters(); return false;" class="ml-2 text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 text-xs">Clear filters</a>';
            filterStatus.classList.remove('hidden');
        } else {
            filterStatus.classList.add('hidden');
        }
    }

    function clearAllFilters() {
        filters = { search: '', type: '', category_id: '' };
        document.getElementById('searchInput').value = '';
        document.getElementById('typeFilter').value = '';
        document.getElementById('selectedCategoryText').textContent = 'All Categories';
        
        // Update category dropdown active states
        document.querySelectorAll('.category-option').forEach(option => {
            if (option.dataset.categoryId === '') {
                option.classList.add('bg-indigo-50', 'dark:bg-indigo-900/30');
            } else {
                option.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/30');
            }
        });
        
        fetchTransactions();
        updateClearSearchButton();
    }

    function fetchTransactions(url = null) {
        // Show loading
        document.getElementById('loadingOverlay').classList.remove('hidden');
        
        // Build URL with filters
        if (!url) {
            url = '{{ route("transactions.index") }}';
        }
        
        const params = new URLSearchParams();
        if (filters.search) params.append('search', filters.search);
        if (filters.type) params.append('type', filters.type);
        if (filters.category_id) params.append('category_id', filters.category_id);
        
        const fullUrl = url.includes('?') 
            ? `${url}&${params.toString()}`
            : `${url}?${params.toString()}`;
        
        // Fetch data
        fetch(fullUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse the response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update table body
            const newBody = doc.querySelector('#transactionsBody');
            if (newBody) {
                document.getElementById('transactionsBody').innerHTML = newBody.innerHTML;
            }
            
            // Update pagination
            const newPagination = doc.querySelector('#paginationContainer');
            if (newPagination) {
                document.getElementById('paginationContainer').innerHTML = newPagination.innerHTML;
            }
            
            // Update URL without reload
            window.history.pushState({}, '', fullUrl);
            
            // Update filter status
            updateFilterStatus();
            
            // Hide loading
            document.getElementById('loadingOverlay').classList.add('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loadingOverlay').classList.add('hidden');
            alert('Failed to load transactions. Please try again.');
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('categoryDropdown');
        const menu = document.getElementById('categoryDropdownMenu');
        const arrow = document.getElementById('categoryDropdownArrow');
        
        if (dropdown && !dropdown.contains(event.target)) {
            menu.classList.add('hidden');
            arrow.style.transform = 'rotate(0deg)';
        }
    });
</script>