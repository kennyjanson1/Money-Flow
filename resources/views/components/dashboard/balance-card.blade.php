<div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-gray-100 dark:bg-slate-900">
    <div class="flex items-start justify-between mb-6">
        <div>
            <h3 class="text-base text-slate-600 dark:text-slate-400 mb-2">My Balance</h3>

            <div class="flex items-baseline gap-3 mb-1">
                <span class="text-4xl font-medium text-slate-900 dark:text-slate-100">
                    Rp {{ number_format($currentBalance, 0, ',', '.') }}
                </span>

                @if ($balanceChange != 0)
                    <span
                        class="flex items-center gap-1 text-sm {{ $balanceChange >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if ($balanceChange >= 0)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            @endif
                        </svg>
                        {{ abs(number_format($balanceChange, 1)) }}%
                    </span>
                @endif
            </div>

            <p class="text-sm text-slate-500 dark:text-slate-400">
                Your Balance in {{ $currentMonth ?? now()->format('F Y') }}
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('transactions.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 text-sm font-medium">
                Add Transaction
            </a>

            <button
                class="border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 text-sm
                font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50
                dark:hover:bg-slate-800 flex items-center gap-1">
                This Month
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
    </div>


    <div class="grid grid-cols-2 gap-4">

        {{-- INCOME --}}
        <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4 flex items-start gap-3">
            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>

            <div>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Income</p>

                <p class="text-xl font-medium text-slate-900 dark:text-slate-100 mb-1">
                    Rp {{ number_format($currentIncome, 0, ',', '.') }}
                </p>

                @if ($incomeChange != 0)
                    <span
                        class="flex items-center gap-1 text-xs {{ $incomeChange >= 0 ? 'text-green-500' : 'text-red-500' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if ($incomeChange >= 0)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            @endif
                        </svg>
                        {{ abs(number_format($incomeChange, 1)) }}%
                    </span>
                @endif
            </div>
        </div>


        {{-- EXPENSES --}}
        <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4 flex items-start gap-3">
            <div class="w-10 h-10 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                </svg>
            </div>

            <div>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Expenses</p>

                <p class="text-xl font-medium text-slate-900 dark:text-slate-100 mb-1">
                    Rp {{ number_format($currentExpense, 0, ',', '.') }}
                </p>

                @if ($expenseChange != 0)
                    <span
                        class="flex items-center gap-1 text-xs {{ $expenseChange <= 0 ? 'text-green-500' : 'text-red-500' }}">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if ($expenseChange <= 0)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            @endif
                        </svg>
                        {{ abs(number_format($expenseChange, 1)) }}%
                    </span>
                @endif
            </div>
        </div>

    </div>
</div>
