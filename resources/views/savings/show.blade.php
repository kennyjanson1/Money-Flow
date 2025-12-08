{{-- resources/views/savings/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-slate-900 dark:text-white">
            {{ $savingsPlan->title }}
        </h2>

        <!-- Delete Goal -->
        <form action="{{ route('savings.destroy', $savingsPlan->id) }}" 
              method="POST"
              onsubmit="return confirm('Are you sure you want to delete this goal?')">
            @csrf
            @method('DELETE')
            <button class="text-red-500 hover:text-red-700">
                Delete
            </button>
        </form>
    </div>

    <!-- Progress -->
    @php
        $percentage = $savingsPlan->target_amount > 0
            ? round(($savingsPlan->current_amount / $savingsPlan->target_amount) * 100, 1)
            : 0;
    @endphp

    <div class="mb-6">
        <div class="text-sm text-slate-500 mb-1">
            Progress: {{ $percentage }}%
        </div>
        <div class="w-full h-3 bg-slate-200 dark:bg-slate-700 rounded-lg overflow-hidden">
            <div class="h-full bg-indigo-600" style="width: {{ min($percentage, 100) }}%"></div>
        </div>

        <div class="flex justify-between mt-2 text-sm text-slate-600 dark:text-slate-300">
            <span>Rp {{ number_format($savingsPlan->current_amount) }}</span>
            <span>Target: Rp {{ number_format($savingsPlan->target_amount) }}</span>
        </div>
    </div>

    <!-- Deposit / Withdraw -->
    <div class="space-y-6">

        {{-- Deposit --}}
        <div class="border rounded-xl p-4 bg-white dark:bg-slate-800">
            <h3 class="font-semibold mb-3">Deposit</h3>

            <form method="POST" action="{{ route('savings.transactions.store', $savingsPlan->id) }}">
                @csrf

                <input type="hidden" name="type" value="deposit">

                <div class="mb-3">
                    <label class="text-sm text-slate-600">Amount</label>
                    <input type="number" name="amount" required step="0.01"
                           class="w-full p-2 rounded border dark:bg-slate-700">
                </div>

                <div class="mb-3">
                    <label class="text-sm text-slate-600">Date</label>
                    <input type="date" name="date" required
                           class="w-full p-2 rounded border dark:bg-slate-700">
                </div>

                <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Add Deposit
                </button>
            </form>
        </div>

        {{-- Withdraw --}}
        <div class="border rounded-xl p-4 bg-white dark:bg-slate-800">
            <h3 class="font-semibold mb-3">Withdraw</h3>

            <form method="POST" action="{{ route('savings.transactions.store', $savingsPlan->id) }}">
                @csrf

                <input type="hidden" name="type" value="withdraw">

                <div class="mb-3">
                    <label class="text-sm text-slate-600">Amount</label>
                    <input type="number" name="amount" required step="0.01"
                           class="w-full p-2 rounded border dark:bg-slate-700">
                </div>

                <div class="mb-3">
                    <label class="text-sm text-slate-600">Date</label>
                    <input type="date" name="date" required
                           class="w-full p-2 rounded border dark:bg-slate-700">
                </div>

                <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Withdraw
                </button>
            </form>
        </div>

    </div>

    <!-- Transaction History -->
    <h3 class="mt-10 mb-3 text-lg font-semibold">History</h3>

    @forelse($savingsPlan->savingsTransactions as $txn)
        <div class="border-l-4 p-3 my-2 
                    {{ $txn->type == 'deposit' ? 'border-green-500' : 'border-red-500' }}">
            <div class="flex justify-between">
                <span class="font-medium">
                    {{ ucfirst($txn->type) }} – Rp {{ number_format($txn->amount) }}
                </span>

                <!-- Delete Transaction -->
                <form method="POST" action="{{ route('savings.transactions.destroy', $txn->id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-500 text-sm hover:text-red-700">
                        Delete
                    </button>
                </form>
            </div>

            <div class="text-xs text-slate-500">
                {{ $txn->date }}
            </div>
        </div>
    @empty
        <p class="text-slate-500">No transactions yet.</p>
    @endforelse

</div>
@endsection
