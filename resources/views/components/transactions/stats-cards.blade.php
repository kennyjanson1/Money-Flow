{{-- resources/views/components/transactions/stats-cards.blade.php --}}

@php
    use Carbon\Carbon;
    
    $currentMonth = Carbon::now();
    $startOfMonth = $currentMonth->copy()->startOfMonth();
    $endOfMonth = $currentMonth->copy()->endOfMonth();
    
    // Current month stats
    $totalTransactions = auth()->user()->transactions()
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->count();
    
    $totalIncome = auth()->user()->transactions()
        ->where('type', 'income')
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->sum('amount');
    
    $totalExpenses = auth()->user()->transactions()
        ->where('type', 'expense')
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->sum('amount');
    
    // Previous month for comparison
    $prevMonth = $currentMonth->copy()->subMonth();
    $prevStart = $prevMonth->copy()->startOfMonth();
    $prevEnd = $prevMonth->copy()->endOfMonth();
    
    $prevTransactions = auth()->user()->transactions()
        ->whereBetween('date', [$prevStart, $prevEnd])
        ->count();
    
    $prevIncome = auth()->user()->transactions()
        ->where('type', 'income')
        ->whereBetween('date', [$prevStart, $prevEnd])
        ->sum('amount');
    
    $prevExpenses = auth()->user()->transactions()
        ->where('type', 'expense')
        ->whereBetween('date', [$prevStart, $prevEnd])
        ->sum('amount');
    
    // Calculate percentage changes
    $transactionChange = $prevTransactions > 0 
        ? (($totalTransactions - $prevTransactions) / $prevTransactions) * 100 
        : 0;
    
    $incomeChange = $prevIncome > 0 
        ? (($totalIncome - $prevIncome) / $prevIncome) * 100 
        : 0;
    
    $expenseChange = $prevExpenses > 0 
        ? (($totalExpenses - $prevExpenses) / $prevExpenses) * 100 
        : 0;
    
    $netBalance = $totalIncome - $totalExpenses;
@endphp

<div>
    <h3 class="md:text-xl font-semibold text-slate-900 dark:text-slate-100">This Month</h3>
    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">All transactions this month</p>
    <br>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Total Transactions -->
        <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-4 md:p-6 bg-gray-100 dark:bg-slate-900">
            <p class="text-sm md:text-base text-slate-600 dark:text-slate-400 mb-2">Total Transactions</p>
            <p class="text-xl md:text-2xl text-slate-900 dark:text-slate-100 mb-1">{{ number_format($totalTransactions) }}</p>
            @if($transactionChange != 0)
                <p class="text-sm {{ $transactionChange >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    {{ $transactionChange >= 0 ? '+' : '' }}{{ number_format(abs($transactionChange), 1) }}% from last month
                </p>
            @else
                <p class="text-sm text-slate-400">No change</p>
            @endif
        </div>
        
        <!-- Total Income -->
        <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-4 md:p-6 bg-gray-100 dark:bg-slate-900">
            <p class="text-sm md:text-base text-slate-600 dark:text-slate-400 mb-2">Total Income</p>
            <p class="text-xl md:text-2xl text-slate-900 dark:text-slate-100 mb-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
            @if($incomeChange != 0)
                <p class="text-sm {{ $incomeChange >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    {{ $incomeChange >= 0 ? '+' : '' }}{{ number_format(abs($incomeChange), 1) }}% from last month
                </p>
            @else
                <p class="text-sm text-slate-400">No change</p>
            @endif
        </div>
        
        <!-- Total Expenses -->
        <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-4 md:p-6 bg-gray-100 dark:bg-slate-900">
            <p class="text-sm md:text-base text-slate-600 dark:text-slate-400 mb-2">Total Expenses</p>
            <p class="text-xl md:text-2xl text-slate-900 dark:text-slate-100 mb-1">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
            @if($expenseChange != 0)
                <p class="text-sm {{ $expenseChange <= 0 ? 'text-green-500' : 'text-red-500' }}">
                    {{ $expenseChange >= 0 ? '+' : '' }}{{ number_format(abs($expenseChange), 1) }}% from last month
                </p>
            @else
                <p class="text-sm text-slate-400">No change</p>
            @endif
        </div>
        
        <!-- Net Balance -->
        <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-4 md:p-6 bg-gray-100 dark:bg-slate-900">
            <p class="text-sm md:text-base text-slate-600 dark:text-slate-400 mb-2">Net Balance</p>
            <p class="text-xl md:text-2xl text-slate-900 dark:text-slate-100 mb-1">Rp {{ number_format($netBalance, 0, ',', '.') }}</p>
            <p class="text-sm {{ $netBalance >= 0 ? 'text-green-500' : 'text-red-500' }}">
                This month
            </p>
        </div>
    </div>
</div>

