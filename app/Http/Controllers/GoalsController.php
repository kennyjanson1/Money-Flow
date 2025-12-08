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
        $query = SavingsPlan::where('user_id', auth()->user()->id);

        if ($request->has('status') && in_array($request->status, ['active', 'completed', 'canceled'])) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'active');
        }

        $savingsPlans = $query->orderBy('deadline', 'asc')->get();

        return view('goals', compact('savingsPlans'));
    }

    public function create()
    {
        return view('goals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:1',
            'current_amount' => 'nullable|numeric|min:0',
            'deadline' => 'nullable|date|after:today',
        ]);

        $validated['user_id'] = auth()->user()->id;
        $validated['current_amount'] = $validated['current_amount'] ?? 0;
        $validated['status'] = 'active';

        SavingsPlan::create($validated);

        return redirect()->route('goals.index')
            ->with('success', 'Goal created successfully!');
    }

    public function show(SavingsPlan $savingsPlan)
    {
        $this->authorize('view', $savingsPlan);

        $savingsPlan->load('savingsTransactions');
        
        return view('goals.show', compact('savingsPlan'));
    }

    public function edit(SavingsPlan $savingsPlan)
    {
        $this->authorize('update', $savingsPlan);

        return view('goals.edit', compact('savingsPlan'));
    }

    public function update(Request $request, SavingsPlan $savingsPlan)
    {
        $this->authorize('update', $savingsPlan);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_amount' => 'required|numeric|min:1',
            'deadline' => 'nullable|date',
            'status' => 'required|in:active,completed,canceled',
        ]);

        $savingsPlan->update($validated);

        if ($savingsPlan->current_amount >= $savingsPlan->target_amount) {
            $savingsPlan->update(['status' => 'completed']);
        }

        return redirect()->route('goals.index')
            ->with('success', 'Goal updated successfully!');
    }

    public function destroy(SavingsPlan $savingsPlan)
    {
        $this->authorize('delete', $savingsPlan);

        $savingsPlan->delete();

        return redirect()->route('goals.index')
            ->with('success', 'Goal deleted successfully!');
    }

    public function complete(SavingsPlan $savingsPlan)
    {
        $this->authorize('update', $savingsPlan);

        $savingsPlan->update(['status' => 'completed']);

        return redirect()->route('goals.show', $savingsPlan)
            ->with('success', 'Goal marked as completed!');
    }

    public function cancel(SavingsPlan $savingsPlan)
    {
        $this->authorize('update', $savingsPlan);

        $savingsPlan->update(['status' => 'canceled']);

        return redirect()->route('goals.index')
            ->with('success', 'Goal canceled.');
    }
    
}