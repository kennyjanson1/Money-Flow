@extends('layouts.app')

@section('title', 'Create Goal - Casholve')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Create New Savings Goal</h1>
            <p class="text-slate-600 dark:text-slate-400">Set your savings target and track your progress</p>
        </div>
        <a href="{{ route('goals.index') }}" class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('goals.store') }}" method="POST">
        @csrf

        <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">
                        Goal Title *
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                           class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('title') border-red-500 @enderror"
                           placeholder="e.g., Emergency Fund, Vacation, New Car" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target Amount -->
                <div>
                    <label for="target_amount" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">
                        Target Amount (Rp) *
                    </label>
                    <input type="text" id="target_amount" name="target_amount" value="{{ old('target_amount') }}" inputmode="numeric"
                           class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('target_amount') border-red-500 @enderror"
                           placeholder="1.000.000" required>
                    @error('target_amount')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Amount -->
                <div>
                    <label for="current_amount" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">
                        Current Saved Amount (Rp)
                    </label>
                    <input type="text" id="current_amount" name="current_amount" value="{{ old('current_amount', 0) }}" inputmode="numeric"
                           class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('current_amount') border-red-500 @enderror"
                           placeholder="0">
                    @error('current_amount')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deadline -->
                <div>
                    <label for="deadline" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">
                        Target Deadline
                    </label>
                    <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}"
                           class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('deadline') border-red-500 @enderror">
                    @error('deadline')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">
                        Description (Optional)
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('description') border-red-500 @enderror"
                              placeholder="Describe your goal and why it's important to you...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 mt-6">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2.5 text-sm font-medium transition">
                    Create Goal
                </button>
                <a href="{{ route('goals.index') }}" class="flex-1 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition text-center">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
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

    // Format untuk target_amount dan current_amount
    const amountInputs = document.querySelectorAll('#target_amount, #current_amount');
    amountInputs.forEach(input => {
        if (input.value) {
            input.value = formatIDR(input.value);
        }
        handleInputFormat(input);
    });
});
</script>
@endpush
@endsection