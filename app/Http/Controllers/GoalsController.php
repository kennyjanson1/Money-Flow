<?php

namespace App\Http\Controllers;

use App\Models\SavingsPlan;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GoalsController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = SavingsPlan::where('user_id', auth()->id());

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['active', 'completed', 'canceled'])) {
            $query->where('status', $request->status);
        } else {
            // Default to active goals
            $query->where('status', 'active');
        }

        $savingsPlans = $query->orderBy('deadline', 'asc')
                             ->orderBy('created_at', 'desc')
                             ->get();

        return view('goals', compact('savingsPlans'));
    }

    public function create()
    {
        return view('goals.create');
    }

    public function store(Request $request)
    {
        // Remove dots for numeric validation
        $request['target_amount'] = str_replace('.', '', $request->target_amount);
        $request['current_amount'] = str_replace('.', '', $request->current_amount);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'target_amount' => 'required|numeric|min:1',
            'current_amount' => 'nullable|numeric|min:0',
            'deadline' => 'nullable|date|after:today',
        ], [
            'title.required' => 'Goal title is required',
            'target_amount.required' => 'Target amount is required',
            'target_amount.min' => 'Target amount must be at least Rp 1',
            'deadline.after' => 'Deadline must be a future date',
        ]);

        // Set defaults
        $validated['user_id'] = auth()->id();
        $validated['current_amount'] = $validated['current_amount'] ?? 0;
        $validated['status'] = 'active';

        // Ensure current amount doesn't exceed target
        if ($validated['current_amount'] >= $validated['target_amount']) {
            $validated['current_amount'] = $validated['target_amount'];
            $validated['status'] = 'completed';
        }

        // Create the goal
        $goal = SavingsPlan::create($validated);

        return redirect()->route('goals.index')
            ->with('success', 'Savings goal "' . $goal->title . '" created successfully!');
    }

    public function show(SavingsPlan $savingsPlan)
    {
        // Check authorization
        if ($savingsPlan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load related transactions if you have them
        $savingsPlan->load('savingsTransactions');
        
        return view('goals.show', compact('savingsPlan'));
    }

    public function edit(SavingsPlan $savingsPlan)
    {
        // Check authorization
        if ($savingsPlan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('goals.edit', compact('savingsPlan'));
    }

    public function update(Request $request, SavingsPlan $savingsPlan)
    {
        $request['target_amount'] = str_replace('.', '', $request->target_amount);

        // Check authorization
        if ($savingsPlan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'target_amount' => 'required|numeric|min:1',
            'deadline' => 'nullable|date',
            'status' => 'sometimes|in:active,completed,canceled',
        ]);

        // Auto-complete if current amount reaches or exceeds target
        if ($savingsPlan->current_amount >= $validated['target_amount']) {
            $validated['status'] = 'completed';
        }

        $savingsPlan->update($validated);

        return redirect()->route('goals.index')
            ->with('success', 'Goal updated successfully!');
    }

    public function destroy(SavingsPlan $savingsPlan)
    {
        // Check authorization
        if ($savingsPlan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $goalTitle = $savingsPlan->title;
        $savingsPlan->delete();

        return redirect()->route('goals.index')
            ->with('success', 'Goal "' . $goalTitle . '" deleted successfully!');
    }

    public function complete(SavingsPlan $savingsPlan)
    {
        // Check authorization
        if ($savingsPlan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $savingsPlan->update([
            'status' => 'completed',
            'current_amount' => $savingsPlan->target_amount // Set to target when completing
        ]);

        return redirect()->route('goals.show', $savingsPlan)
            ->with('success', 'Congratulations! Goal marked as completed! 🎉');
    }

    public function cancel(SavingsPlan $savingsPlan)
    {
        // Check authorization
        if ($savingsPlan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $savingsPlan->update(['status' => 'canceled']);

        return redirect()->route('goals.index')
            ->with('success', 'Goal canceled.');
    }

    public function addSavings(Request $request, SavingsPlan $savingsPlan)
    {
        $request['amount'] = str_replace('.', '', $request->amount);

        // Check authorization
        if ($savingsPlan->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow adding savings to active goals
        if ($savingsPlan->status !== 'active') {
            return redirect()->route('goals.show', $savingsPlan)
                ->with('error', 'You can only add savings to active goals.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date|before_or_equal:today',
            'note' => 'nullable|string|max:255',
        ]);

        // Create savings transaction
        $savingsPlan->savingsTransactions()->create([
            'user_id' => auth()->id(),
            'amount' => $validated['amount'],
            'type' => 'deposit',
            'date' => $validated['date'],
            'note' => $validated['note'] ?? null,
        ]);

        // Update current amount
        $savingsPlan->increment('current_amount', $validated['amount']);

        // Check if goal is completed
        if ($savingsPlan->current_amount >= $savingsPlan->target_amount) {
            $savingsPlan->update(['status' => 'completed']);
        }

        return redirect()->route('goals.show', $savingsPlan)
            ->with('success', 'Rp ' . number_format($validated['amount'], 0, ',', '.') . ' added to your savings goal!');
    }
}
