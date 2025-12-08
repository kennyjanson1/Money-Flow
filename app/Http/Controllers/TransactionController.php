<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        // Start with base query
        $query = Transaction::where('user_id', auth()->id())
            ->with('category');

        // Apply Type Filter
        if ($request->filled('type') && in_array($request->type, ['income', 'expense'])) {
            $query->where('type', $request->type);
        }

        // Apply Category Filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Apply Date Range Filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('date', '>=', $request->start_date)
                  ->whereDate('date', '<=', $request->end_date);
        }

        // Apply Period Filter (if no custom date range)
        if (!$request->filled('start_date') && $request->filled('period')) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('date', today());
                    break;
                case 'yesterday':
                    $query->whereDate('date', today()->subDay());
                    break;
                case 'this_week':
                    $query->whereBetween('date', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;
                case 'last_week':
                    $query->whereBetween('date', [
                        now()->subWeek()->startOfWeek(),
                        now()->subWeek()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereBetween('date', [
                        now()->startOfMonth(),
                        now()->endOfMonth()
                    ]);
                    break;
                case 'last_month':
                    $query->whereBetween('date', [
                        now()->subMonth()->startOfMonth(),
                        now()->subMonth()->endOfMonth()
                    ]);
                    break;
                case 'this_year':
                    $query->whereBetween('date', [
                        now()->startOfYear(),
                        now()->endOfYear()
                    ]);
                    break;
            }
        }

        // Apply Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sort by newest first
        $query->orderBy('date', 'desc')
              ->orderBy('created_at', 'desc');

        // Paginate results
        $transactions = $query->paginate(15)->withQueryString();

        // Get all categories for filter dropdown
        // Include user's categories AND default categories
        $categories = Category::where(function($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere('is_default', true);
            })
            ->orderBy('is_default', 'desc') // Default categories first
            ->orderBy('name', 'asc')
            ->get();

        return view('transaction', compact('transactions', 'categories'));
    }

    public function storeBulk(Request $request)
    {

        $request->validate([
            'transactions' => 'required|array|min:1',
            'transactions.*.category_id' => 'required|exists:categories,id',
            'transactions.*.title' => 'required|string|max:255',
            'transactions.*.description' => 'nullable|string',
            'transactions.*.amount' => 'required|numeric|min:0',
            'transactions.*.type' => 'required|in:income,expense',
            'transactions.*.date' => 'required|date',
        ]);

        $savedCount = 0;

        foreach ($request->transactions as $transactionData) {
            Transaction::create([
                'user_id' => auth()->id(),
                'category_id' => $transactionData['category_id'],
                'title' => $transactionData['title'],
                'description' => $transactionData['description'] ?? null,
                'amount' => $transactionData['amount'],
                'type' => $transactionData['type'],
                'date' => $transactionData['date'],
            ]);
            $savedCount++;
        }

        return redirect()->route('transactions.index')
            ->with('success', "{$savedCount} transaction(s) created successfully!");
    }

    public function create()
    {
        // Get categories using the forUser scope from model
        $categories = Category::forUser(auth()->id())
            ->orderBy('is_default', 'desc')
            ->orderBy('name', 'asc')
            ->get();
        
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
        ]);

        $validated['user_id'] = auth()->id();

        Transaction::create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully!');
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->load('category');
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Get categories using the forUser scope
        $categories = Category::forUser(auth()->id())
            ->orderBy('is_default', 'desc')
            ->orderBy('name', 'asc')
            ->get();
        
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'date' => 'required|date',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully!');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully!');
    }

    public function restore($id)
    {
        $transaction = Transaction::withTrashed()->findOrFail($id);

        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $transaction->restore();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction restored successfully!');
    }

    public function trash()
    {
        $transactions = Transaction::onlyTrashed()
            ->where('user_id', auth()->id())
            ->with('category')
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('transactions.trash', compact('transactions'));
    }

    
}