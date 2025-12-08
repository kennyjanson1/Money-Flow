@extends('layouts.app')

@section('title', 'Add Transactions - Moneta')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Add Transactions</h1>
                <p class="text-slate-600 dark:text-slate-400">Add multiple transactions at once</p>
            </div>
            <a href="{{ route('transactions.index') }}"
                class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('transactions.storeBulk') }}" method="POST" id="bulkTransactionForm">
            @csrf

            <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">

                <!-- Transactions Container -->
                <div id="transactionsContainer" class="space-y-6">

                    <!-- Transaction Item Template -->
                    <div class="transaction-item border border-slate-200 dark:border-slate-700 rounded-xl p-4 relative">

                        <button type="button"
                            class="remove-transaction absolute top-4 right-4 text-red-500 hover:text-red-700 hidden">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Type -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Type *</label>
                                <select name="transactions[0][type]"
                                    class="transaction-type w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required>
                                    <option value="">Select type</option>
                                    <option value="income">Income</option>
                                    <option value="expense">Expense</option>
                                </select>
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Category *</label>
                                <select name="transactions[0][category_id]"
                                    class="category-select w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required disabled>
                                    <option value="">Select category</option>
                                </select>
                            </div>

                            <!-- Title -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Title *</label>
                                <input type="text" name="transactions[0][title]" placeholder="e.g., Lunch at restaurant"
                                    class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required>
                            </div>

                            <!-- Amount -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Amount *</label>
                                <input type="number" name="transactions[0][amount]" step="0.01" min="0"
                                    placeholder="0.00"
                                    class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required>
                            </div>

                            <!-- Date -->
                            <div>
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Date *</label>
                                <input type="date" name="transactions[0][date]" value="{{ date('Y-m-d') }}"
                                    class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Description (Optional)</label>
                                <textarea name="transactions[0][description]" rows="2" placeholder="Add notes..."
                                    class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Another Button -->
                <button type="button" id="addTransactionBtn"
                    class="mt-4 w-full border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl py-3 text-slate-600 dark:text-slate-400 hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Another Transaction
                </button>

                <!-- Submit Buttons -->
                <div class="flex gap-3 mt-6">
                    <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2.5 text-sm font-medium transition">
                        Save All Transactions
                    </button>
                    <a href="{{ route('transactions.index') }}"
                        class="flex-1 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition text-center">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>

@push('scripts')
<script>
    // ---------------------------
    // CATEGORY OPTIONS FROM DATABASE
    // ---------------------------

    // Semua kategori dari DB dikirim dari controller
    const allCategories = @json($categories);

    // Filter kategori berdasarkan type
    function getCategoriesByType(type) {
        return allCategories.filter(cat => cat.type === type);
    }

    // Function to update category dropdown based on selected type
    function updateCategoryOptions(container) {
        const typeSelect = container.querySelector('.transaction-type');
        const categorySelect = container.querySelector('.category-select');

        typeSelect.addEventListener('change', function () {
            const type = this.value;

            categorySelect.innerHTML = '<option value="">Select category</option>';
            categorySelect.disabled = true;

            if (!type) return;

            const list = getCategoriesByType(type);

            list.forEach(cat => {
                const opt = document.createElement('option');
                opt.value = cat.id;
                opt.textContent = cat.name;
                categorySelect.appendChild(opt);
            });

            categorySelect.disabled = false;
        });
    }

    // Initialize first transaction block
    updateCategoryOptions(document.querySelector('.transaction-item'));

    // CLONING SYSTEM
    let transactionCount = 1;

    document.getElementById('addTransactionBtn').addEventListener('click', function() {
        const container = document.getElementById('transactionsContainer');
        const template = container.querySelector('.transaction-item').cloneNode(true);

        template.querySelectorAll('[name]').forEach(input => {
            const name = input.getAttribute('name');
            input.setAttribute('name', name.replace(/\[\d+\]/, `[${transactionCount}]`));

            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            } else if (input.type !== 'date') {
                input.value = '';
            }
        });

        template.querySelector('.remove-transaction').classList.remove('hidden');

        template.querySelector('.category-select').disabled = true;
        template.querySelector('.category-select').innerHTML =
            '<option value="">Select category</option>';

        container.appendChild(template);
        transactionCount++;

        updateCategoryOptions(template);
    });

    // Remove transaction block
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-transaction')) {
            const item = e.target.closest('.transaction-item');
            if (document.querySelectorAll('.transaction-item').length > 1) {
                item.remove();
            } else {
                alert('You must have at least one transaction');
            }
        }
    });
</script>
@endpush


@endsection
