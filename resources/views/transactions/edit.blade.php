@extends('layouts.app')

@section('title', 'Edit Transaction - Casholve')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Edit Transaction</h1>
                <p class="text-slate-600 dark:text-slate-400">Update transaction details</p>
            </div>
            <a href="{{ route('transactions.index') }}"
                class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Type *</label>
                        <select name="type" id="type"
                            class="transaction-type w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('type') border-red-500 @enderror"
                            required>
                            <option value="">Select type</option>
                            <option value="income" {{ old('type', $transaction->type) == 'income' ? 'selected' : '' }}>Income</option>
                            <option value="expense" {{ old('type', $transaction->type) == 'expense' ? 'selected' : '' }}>Expense</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Category *</label>
                        <select name="category_id" id="category_id"
                            class="category-select w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('category_id') border-red-500 @enderror"
                            required>
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->icon ?? '' }} {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Title *</label>
                        <input type="text" name="title" value="{{ old('title', $transaction->title) }}" 
                            placeholder="e.g., Lunch at restaurant"
                            class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('title') border-red-500 @enderror"
                            required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Amount (Rp) *</label>
                        <input type="text" name="amount" id="amount" value="{{ old('amount', $transaction->amount) }}" 
                            inputmode="numeric" placeholder="100.000"
                            class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('amount') border-red-500 @enderror"
                            required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Date *</label>
                        <input type="date" name="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}"
                            class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('date') border-red-500 @enderror"
                            required>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Description (Optional)</label>
                        <textarea name="description" rows="3" placeholder="Add notes..."
                            class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $transaction->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <button type="submit"
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2.5 text-sm font-medium transition">
                        Update Transaction
                    </button>
                    <a href="{{ route('transactions.index') }}"
                        class="flex-1 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition text-center">
                        Cancel
                    </a>
                    <button type="button" id="deleteBtn"
                        class="sm:w-auto px-6 bg-red-600 hover:bg-red-700 text-white rounded-lg py-2.5 text-sm font-medium transition">
                        Delete
                    </button>
                </div>
            </div>
        </form>

        <!-- Hidden Delete Form -->
        <form id="deleteForm" action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    @push('scripts')
        <script>
            // IDR Formatting
            function formatIDR(value) {
                const number = value.replace(/\D/g, '');
                if (number === '') return '';
                return new Intl.NumberFormat('id-ID').format(number);
            }

            function handleInputFormat(input) {
                input.addEventListener('input', function(e) {
                    const cursorPosition = this.selectionStart;
                    const oldValue = this.value;
                    const oldLength = oldValue.length;
                    
                    const formatted = formatIDR(this.value);
                    this.value = formatted;
                    
                    const newLength = formatted.length;
                    const diff = newLength - oldLength;
                    const newCursorPosition = cursorPosition + diff;
                    this.setSelectionRange(newCursorPosition, newCursorPosition);
                });

                const form = input.closest('form');
                if (form) {
                    form.addEventListener('submit', function() {
                        input.value = input.value.replace(/\./g, '');
                    });
                }
            }

            // Format amount on page load
            const amountInput = document.querySelector('#amount');
            if (amountInput) {
                if (amountInput.value) {
                    amountInput.value = formatIDR(amountInput.value);
                }
                handleInputFormat(amountInput);
            }

            // Delete Button Handler
            document.getElementById('deleteBtn').addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this transaction? This action cannot be undone.')) {
                    document.getElementById('deleteForm').submit();
                }
            });
        </script>
    @endpush
@endsection