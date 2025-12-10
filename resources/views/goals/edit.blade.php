@extends('layouts.app')

@section('title', 'Edit Goal - Casholve')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Edit Savings Goal</h1>
            <p class="text-slate-600 dark:text-slate-400">Update your savings target and details</p>
        </div>
        <a href="{{ route('goals.show', $savingsPlan) }}" class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('goals.update', $savingsPlan) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">
                        Goal Title *
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $savingsPlan->title) }}"
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
                    <input type="text" id="target_amount" name="target_amount" value="{{ old('target_amount', $savingsPlan->target_amount) }}" inputmode="numeric"
                           class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('target_amount') border-red-500 @enderror"
                           placeholder="1.000.000" required>
                    @error('target_amount')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deadline -->
                <div>
                    <label for="deadline" class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">
                        Target Deadline
                    </label>
                    <input type="date" id="deadline" name="deadline" value="{{ old('deadline', $savingsPlan->deadline ? \Carbon\Carbon::parse($savingsPlan->deadline)->format('Y-m-d') : '') }}"
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
                              placeholder="Describe your goal and why it's important to you...">{{ old('description', $savingsPlan->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 mt-6">
                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2.5 text-sm font-medium transition">
                    Update Goal
                </button>
                <a href="{{ route('goals.show', $savingsPlan) }}" class="flex-1 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition text-center">
                    Cancel
                </a>
                <button type="button" id="deleteBtn" class="sm:w-auto px-6 bg-red-600 hover:bg-red-700 text-white rounded-lg py-2.5 text-sm font-medium transition">
                    Delete
                </button>
            </div>
        </div>
    </form>

    <!-- Hidden Delete Form -->
    <form id="deleteForm" action="{{ route('goals.destroy', $savingsPlan) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
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

    const targetAmountInput = document.querySelector('#target_amount');
    if (targetAmountInput) {
        if (targetAmountInput.value) {
            targetAmountInput.value = formatIDR(targetAmountInput.value);
        }
        handleInputFormat(targetAmountInput);
    }

    // Delete Button Handler
    document.getElementById('deleteBtn').addEventListener('click', function() {
        if (confirm('Are you sure you want to delete this goal? This action cannot be undone.')) {
            document.getElementById('deleteForm').submit();
        }
    });
});
</script>
@endpush
@endsection