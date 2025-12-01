<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\SavingsPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Total Summary
        $totalIncome = Transaction::where('user_id', $user->id)
            ->income()
            ->sum('amount');

        $totalExpense = Transaction::where('user_id', $user->id)
            ->expense()
            ->sum('amount');

        $totalBalance = $totalIncome - $totalExpense;

        // Cashflow (last 30 days)
        $startDate = now()->subDays(29)->startOfDay();
        $endDate   = now()->endOfDay();

        $cashflowData = Transaction::select(
            DB::raw('DATE(date) as day'),
            DB::raw("SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income"),
            DB::raw("SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense")
        )
            ->where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('day')
            ->get();

        // Siapkan array kosong 30 hari
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');

            $labels[] = $date;

            $record = $cashflowData->firstWhere('day', $date);

            $incomeData[] = $record ? (float) $record->income : 0;
            $expenseData[] = $record ? (float) $record->expense : 0;
        }

        // Category Spending
        $categorySpending = Transaction::select(
            'categories.name as category',
            DB::raw('SUM(transactions.amount) as total')
        )
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $user->id)
            ->where('transactions.type', 'expense')
            ->whereMonth('transactions.date', now()->month)
            ->whereYear('transactions.date', now()->year)
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        // Recent Transactions
        $recentTransactions = Transaction::with('category')
            ->where('user_id', $user->id)
            ->latest('date')
            ->take(10)
            ->get();

        // Savings Summary
        // Savings Summary
        $savingsSummary = SavingsPlan::where('user_id', $user->id)
            ->get()
            ->map(function ($plan) use ($user) {

                $totalSaved = Transaction::where('user_id', $user->id)
                    ->where('savings_plan_id', $plan->id)
                    ->sum('amount');

                return [
                    'name'     => $plan->name,
                    'target'   => (float) $plan->target_amount,
                    'saved'    => (float) $totalSaved,
                    'progress' => $plan->target_amount > 0
                        ? round(($totalSaved / $plan->target_amount) * 100, 2)
                        : 0
                ];
            });


        // Month Comparison
        $startOfMonth = now()->startOfMonth();
        $endOfMonth   = now()->endOfMonth();

        $currentIncome = Transaction::where('user_id', $user->id)
            ->income()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $currentExpense = Transaction::where('user_id', $user->id)
            ->expense()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $currentBalance = $currentIncome - $currentExpense;

        // Previous month
        $prevMonthStart = now()->subMonth()->startOfMonth();
        $prevMonthEnd   = now()->subMonth()->endOfMonth();

        $prevIncome = Transaction::where('user_id', $user->id)
            ->income()
            ->whereBetween('date', [$prevMonthStart, $prevMonthEnd])
            ->sum('amount');

        $prevExpense = Transaction::where('user_id', $user->id)
            ->expense()
            ->whereBetween('date', [$prevMonthStart, $prevMonthEnd])
            ->sum('amount');

        $prevBalance = $prevIncome - $prevExpense;

        // Percentage change
        $balanceChange = $prevBalance != 0
            ? (($currentBalance - $prevBalance) / abs($prevBalance)) * 100
            : 0;

        $incomeChange = $prevIncome != 0
            ? (($currentIncome - $prevIncome) / abs($prevIncome)) * 100
            : 0;

        $expenseChange = $prevExpense != 0
            ? (($currentExpense - $prevExpense) / abs($prevExpense)) * 100
            : 0;

        // Return Data
        return view('dashboard', [
            'totalBalance'       => $totalBalance,
            'totalIncome'        => $totalIncome,
            'totalExpense'        => $totalExpense,

            'cashflowLabels'     => $labels,
            'cashflowIncome'     => $incomeData,
            'cashflowExpense'    => $expenseData,

            'categorySpending'   => $categorySpending,
            'recentTransactions' => $recentTransactions,
            'savingsSummary'      => $savingsSummary,

            'currentBalance' => $currentBalance,
            'currentIncome'  => $currentIncome,
            'currentExpense' => $currentExpense,
            'balanceChange'  => $balanceChange,
            'incomeChange'   => $incomeChange,
            'expenseChange'  => $expenseChange,
        ]);
    }
}
