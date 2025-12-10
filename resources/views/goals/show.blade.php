@extends('layouts.app')

@section('title', $savingsPlan->title . ' - Casholve')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('goals.index') }}" class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $savingsPlan->title }}</h1>
            <p class="text-slate-600 dark:text-slate-400">Savings Goal</p>
        </div>
        @if($savingsPlan->status === 'active')
            <div class="flex gap-2">
                <a href="{{ route('goals.edit', $savingsPlan) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 text-sm font-medium">
                    Edit Goal
                </a>
                <form action="{{ route('goals.complete', $savingsPlan) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2 text-sm font-medium"
                            onclick="return confirm('Mark this goal as completed?')">
                        Mark Complete
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Add Money Form (only for active goals) -->
    @if($savingsPlan->status === 'active')
        <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-6">Add Money to Goal</h3>

            <form action="{{ route('goals.add-savings', $savingsPlan) }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Amount -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Amount (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="amount" name="amount" value="{{ old('amount') }}" inputmode="numeric"
                               class="amount-input w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('amount') border-red-500 @enderror"
                               placeholder="100000">
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}"
                               class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('date') border-red-500 @enderror">
                        @error('date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Note -->
                    <div>
                        <label for="note" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Note (Optional)
                        </label>
                        <input type="text" id="note" name="note" value="{{ old('note') }}"
                               class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('note') border-red-500 @enderror"
                               placeholder="e.g., Monthly savings">
                        @error('note')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-6 py-3 text-sm font-medium transition">
                        Add to Savings
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Goal Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Progress Card -->
        <div class="lg:col-span-2 border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-6">Progress Overview</h3>

            @php
                $percentage = $savingsPlan->target_amount > 0
                    ? round(($savingsPlan->current_amount / $savingsPlan->target_amount) * 100)
                    : 0;
            @endphp

            <div class="space-y-4">
                <!-- Progress Bar -->
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600 dark:text-slate-400">Progress</span>
                    <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $percentage }}%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-3 overflow-hidden">
                    <div class="bg-indigo-500 h-3 rounded-full transition-all duration-500" style="width: {{ min($percentage, 100) }}%"></div>
                </div>

                <!-- Amounts -->
                <div class="grid grid-cols-2 gap-4 pt-4">
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Current Saved</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">Rp {{ number_format($savingsPlan->current_amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Target Amount</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">Rp {{ number_format($savingsPlan->target_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Remaining -->
                @if($savingsPlan->status === 'active')
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                        <p class="text-sm text-slate-600 dark:text-slate-400">Remaining to Save</p>
                        <p class="text-xl font-semibold text-slate-900 dark:text-slate-100">Rp {{ number_format(max($savingsPlan->target_amount - $savingsPlan->current_amount, 0), 0, ',', '.') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Goal Details -->
        <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-6">Goal Details</h3>

            <div class="space-y-4">
                <!-- Status -->
                <div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Status</p>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($savingsPlan->status === 'completed') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400
                        @elseif($savingsPlan->status === 'active') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                        @else bg-slate-100 text-slate-800 dark:bg-slate-900/30 dark:text-slate-400 @endif">
                        {{ ucfirst($savingsPlan->status) }}
                    </span>
                </div>

                <!-- Deadline -->
                @if($savingsPlan->deadline)
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Deadline</p>
                        <p class="text-base font-medium text-slate-900 dark:text-slate-100">
                            {{ \Carbon\Carbon::parse($savingsPlan->deadline)->format('M j, Y') }}
                        </p>
                        @php
                            $daysRemaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($savingsPlan->deadline), false);
                        @endphp
                        @if($daysRemaining > 0)
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $daysRemaining }} days remaining</p>
                        @elseif($daysRemaining === 0)
                            <p class="text-sm text-amber-600 dark:text-amber-400">Due today</p>
                        @else
                            <p class="text-sm text-red-600 dark:text-red-400">{{ abs($daysRemaining) }} days overdue</p>
                        @endif
                    </div>
                @endif

                <!-- Created -->
                <div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Created</p>
                    <p class="text-base font-medium text-slate-900 dark:text-slate-100">
                        {{ \Carbon\Carbon::parse($savingsPlan->created_at)->format('M j, Y') }}
                    </p>
                </div>

                <!-- Actions -->
                @if($savingsPlan->status === 'active')
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700 space-y-2">
                        <form action="{{ route('goals.cancel', $savingsPlan) }}" method="POST" class="inline-block w-full">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white rounded-lg px-4 py-2 text-sm font-medium"
                                    onclick="return confirm('Are you sure you want to cancel this goal?')">
                                Cancel Goal
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Description -->
    @if($savingsPlan->description)
        <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Description</h3>
            <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $savingsPlan->description }}</p>
        </div>
    @endif

    <!-- Savings Transactions -->
    @if($savingsPlan->savingsTransactions && $savingsPlan->savingsTransactions->count() > 0)
        <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-6">Savings History</h3>

            <div class="space-y-4">
                @foreach($savingsPlan->savingsTransactions->sortByDesc('created_at') as $transaction)
                    <div class="flex items-center justify-between py-3 border-b border-slate-200 dark:border-slate-700 last:border-b-0">
                        <div>
                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                {{ $transaction->note ?: 'Savings contribution' }}
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ \Carbon\Carbon::parse($transaction->date)->format('M j, Y') }}
                            </p>
                        </div>
                        <p class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                            +Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
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

    const amountInput = document.querySelector('#amount');
    if (amountInput) {
        handleInputFormat(amountInput);
    }
});
</script>
@endpush