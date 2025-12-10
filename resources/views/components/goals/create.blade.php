{{-- resources/views/goals/create.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 px-4">

    <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
        
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">
                Create New Savings Goal
            </h2>
            <p class="text-slate-600 dark:text-slate-400 text-sm mt-1">
                Plan your savings and reach your financial goals.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('goals.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Goal Title --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    Goal Title <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text"
                    name="title"
                    required
                    value="{{ old('title') }}"
                    placeholder="e.g. New Laptop, Vacation, Emergency Fund"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 @error('title') border-red-500 @enderror"
                >
                @error('title')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    Description (Optional)
                </label>
                <textarea 
                    name="description"
                    rows="3"
                    placeholder="Add notes about your goal..."
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 @error('description') border-red-500 @enderror"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Target Amount --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    Target Amount (Rp) <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number"
                    name="target_amount"
                    required
                    min="1"
                    step="1000"
                    value="{{ old('target_amount') }}"
                    placeholder="e.g. 5000000"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 @error('target_amount') border-red-500 @enderror"
                >
                @error('target_amount')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Current Amount (Starting Balance) --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    Current Amount (Rp) - Optional
                </label>
                <input 
                    type="number"
                    name="current_amount"
                    min="0"
                    step="1000"
                    value="{{ old('current_amount', 0) }}"
                    placeholder="e.g. 1000000 (if you already have some savings)"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 @error('current_amount') border-red-500 @enderror"
                >
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Leave as 0 if starting from scratch
                </p>
                @error('current_amount')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deadline --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    Target Deadline (Optional)
                </label>
                <input 
                    type="date"
                    name="deadline"
                    value="{{ old('deadline') }}"
                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 @error('deadline') border-red-500 @enderror"
                >
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Set a deadline to stay motivated
                </p>
                @error('deadline')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info Box --}}
            <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-indigo-700 dark:text-indigo-300">
                        <p class="font-medium mb-1">Tips for setting goals:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Be specific with your goal title</li>
                            <li>Set a realistic target amount</li>
                            <li>Choose a deadline that motivates you</li>
                            <li>Track your progress regularly</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-slate-700">
                <a href="{{ route('goals.index') }}" class="text-slate-600 dark:text-slate-300 text-sm hover:underline flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Goals
                </a>

                <button 
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Create Goal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-format currency input
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value) {
                // Round to nearest thousand for better UX
                const value = Math.round(parseFloat(this.value) / 1000) * 1000;
                this.value = value;
            }
        });
    });

    // Set minimum date for deadline
    const deadlineInput = document.querySelector('input[name="deadline"]');
    if (deadlineInput) {
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const minDate = tomorrow.toISOString().split('T')[0];
        deadlineInput.setAttribute('min', minDate);
    }
</script>
@endsection