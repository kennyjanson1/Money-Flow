<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\SavingsPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        // Total Summary
        $totalIncome = Transaction::where('user_id', $userId)->income()->sum('amount');
        $totalExpense = Transaction::where('user_id', $userId)->expense()->sum('amount');
        $totalBalance = $totalIncome - $totalExpense;

  
        /*
        |--------------------------------------------------------------------------
        | DYNAMIC CASHFLOW (THIS YEAR / THIS WEEK / THIS MONTH - FULL MONTH)
        |--------------------------------------------------------------------------
        */
        $range = request('range', 'this_month');
        $cashflowLabels = [];
        $cashflowIncome = [];
        $cashflowExpense = [];

        $today = now();

        if ($range === 'this_year') {
            // LAST 12 MONTHS (from 11 months ago -> this month)
            for ($i = 11; $i >= 0; $i--) {
                $monthDate = $today->copy()->subMonths($i);

                $cashflowLabels[] = $monthDate->format('M Y');

                $cashflowIncome[] = Transaction::where('user_id', $userId)
                    ->income()
                    ->whereYear('date', $monthDate->year)
                    ->whereMonth('date', $monthDate->month)
                    ->sum('amount');

                $cashflowExpense[] = Transaction::where('user_id', $userId)
                    ->expense()
                    ->whereYear('date', $monthDate->year)
                    ->whereMonth('date', $monthDate->month)
                    ->sum('amount');
            }
        }

        elseif ($range === 'this_week') {
            // LAST 7 DAYS (label: Mon (09 Dec))
            for ($i = 6; $i >= 0; $i--) {
                $day = $today->copy()->subDays($i);

                $cashflowLabels[] = $day->format('D (d M)');

                $cashflowIncome[] = Transaction::where('user_id', $userId)
                    ->income()
                    ->whereDate('date', $day->toDateString())
                    ->sum('amount');

                $cashflowExpense[] = Transaction::where('user_id', $userId)
                    ->expense()
                    ->whereDate('date', $day->toDateString())
                    ->sum('amount');
            }
        }

        else {
            // THIS MONTH -> FULL MONTH (1 .. endOfMonth)
            $startOfMonth = $today->copy()->startOfMonth();
            $endOfMonth = $today->copy()->endOfMonth();

            $days = $startOfMonth->diffInDays($endOfMonth); // e.g. 30 for 31-day month (0..30)

            for ($i = 0; $i <= $days; $i++) {
                $date = $startOfMonth->copy()->addDays($i);

                // LABEL: 01 Dec, 02 Dec, ...
                $cashflowLabels[] = $date->format('d M');

                $cashflowIncome[] = Transaction::where('user_id', $userId)
                    ->income()
                    ->whereDate('date', $date->toDateString())
                    ->sum('amount');

                $cashflowExpense[] = Transaction::where('user_id', $userId)
                    ->expense()
                    ->whereDate('date', $date->toDateString())
                    ->sum('amount');
            }
        }



        /*
        |--------------------------------------------------------------------------
        | CATEGORY SPENDING
        |--------------------------------------------------------------------------
        */
        $range = request('range', 'this_month'); // default this month

        // Tentukan tanggal awal & akhir berdasarkan range
        if ($range === 'this_week') {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate   = Carbon::now()->endOfDay();
        } elseif ($range === 'this_year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate   = Carbon::now()->endOfYear();
        } else {
            // default this month
            $startDate = Carbon::now()->startOfMonth();
            $endDate   = Carbon::now()->endOfMonth();
        }

        // Query
        $categorySpending = Transaction::select(
                'categories.name as category',
                DB::raw('SUM(transactions.amount) as total'),
                'categories.color'
            )
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->where('transactions.type', 'expense') // hanya expense
            ->whereBetween('transactions.date', [$startDate, $endDate])
            ->groupBy('categories.name', 'categories.color')
            ->orderByDesc('total')
            ->get();

        // Recent Transactions
        $recentTransactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->latest('date')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Savings Summary
        |--------------------------------------------------------------------------
        */
        $savingsSummary = SavingsPlan::where('user_id', $userId)
            ->get()
            ->map(function ($plan) {

                $totalSaved = $plan->savingsTransactions()
                    ->sum('amount');

                return [
                    'name'     => $plan->name,
                    'target'   => (float) $plan->target_amount,
                    'saved'    => (float) $totalSaved,
                    'progress' => $plan->target_amount > 0
                        ? round(($totalSaved / $plan->target_amount) * 100, 2)
                        : 0,
                ];
            });



        /*
        |--------------------------------------------------------------------------
        | MONTH COMPARISON
        |--------------------------------------------------------------------------
        */
        $startOfMonth = now()->startOfMonth();
        $endOfMonth   = now()->endOfMonth();

        $currentMonth = Carbon::now()->format('F Y');

        $currentIncome = Transaction::where('user_id', $userId)
            ->income()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $currentExpense = Transaction::where('user_id', $userId)
            ->expense()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $currentBalance = $currentIncome - $currentExpense;

        // Previous month
        $prevMonthStart = now()->subMonth()->startOfMonth();
        $prevMonthEnd   = now()->subMonth()->endOfMonth();

        $prevIncome = Transaction::where('user_id', $userId)
            ->income()
            ->whereBetween('date', [$prevMonthStart, $prevMonthEnd])
            ->sum('amount');

        $prevExpense = Transaction::where('user_id', $userId)
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

        // Recent Transactions
        $transactions = Transaction::with('category')
            ->where('user_id', $userId)
            ->recent(10)
            ->get();

        return view('dashboard', [
            'totalBalance'       => $totalBalance,
            'totalIncome'        => $totalIncome,
            'totalExpense'       => $totalExpense,

            // NOTE: Cashflow Data
            'months'            => $cashflowLabels,
            'monthlyIncome'     => $cashflowIncome,
            'monthlyExpenses'   => $cashflowExpense,

            'categorySpending'   => $categorySpending,
            'recentTransactions' => $recentTransactions,
            'savingsSummary'     => $savingsSummary,

            'currentMonth'    => $currentMonth,
            'currentBalance'  => $currentBalance,
            'currentIncome'   => $currentIncome,
            'currentExpense'  => $currentExpense,
            'balanceChange'   => $balanceChange,
            'incomeChange'    => $incomeChange,
            'expenseChange'   => $expenseChange,

            'transactions' => $transactions,
        ]);
    }

    /**
     * Get spending data by category for AJAX request (Spending Chart)
     */
    public function getSpendingData(Request $request)
    {
        $range = $request->get('range', 'this_month');
        $userId = auth()->id();

        // Tentukan tanggal awal & akhir berdasarkan range
        if ($range === 'this_week') {
            $startDate = Carbon::now()->subDays(6)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
        } elseif ($range === 'this_year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();
        } else {
            // default this_month
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
        }

        // Query - sama seperti di method index()
        $categorySpending = Transaction::select(
                'categories.name as category',
                DB::raw('SUM(transactions.amount) as total'),
                'categories.color'
            )
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', $userId)
            ->where('transactions.type', 'expense')
            ->whereBetween('transactions.date', [$startDate, $endDate])
            ->groupBy('categories.name', 'categories.color')
            ->orderByDesc('total')
            ->get();

        $totalSpending = $categorySpending->sum('total');

        // Format data untuk JSON response
        $categories = $categorySpending->map(function($cat) use ($totalSpending) {
            return [
                'name' => $cat->category,
                'amount' => (float) $cat->total,
                'percentage' => $totalSpending > 0
                    ? round(($cat->total / $totalSpending) * 100, 1)
                    : 0,
                'color' => $cat->color,
            ];
        })->toArray();

        return response()->json([
            'totalSpending' => (float) $totalSpending,
            'categories' => $categories,
        ]);
    }
}