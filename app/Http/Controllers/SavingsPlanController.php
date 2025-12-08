<?php

namespace App\Http\Controllers;

use App\Models\SavingsPlan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingsPlanController extends Controller
{
    public function index()
    {
        $plans = SavingsPlan::where('user_id', Auth::id())->get();
        return view('savings.index', compact('plans'));
    }

    public function create()
    {
        return view('savings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'deadline'      => 'nullable|date',
        ]);

        SavingsPlan::create([
            'user_id'       => Auth::id(),
            'name'          => $request->name,
            'target_amount' => $request->target_amount,
            'deadline'      => $request->deadline,
        ]);

        return redirect()->route('savings.index');
    }

    public function show($id)
    {
        $plan = SavingsPlan::where('user_id', Auth::id())->findOrFail($id);

        $totalSaved = Transaction::where('user_id', Auth::id())
            ->where('savings_plan_id', $plan->id)
            ->sum('amount');

        return view('savings.show', compact('plan', 'totalSaved'));
    }

    public function edit($id)
    {
        $plan = SavingsPlan::where('user_id', Auth::id())->findOrFail($id);
        return view('savings.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $plan = SavingsPlan::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name'          => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:1',
            'deadline'      => 'nullable|date',
        ]);

        $plan->update($request->only('name', 'target_amount', 'deadline'));

        return redirect()->route('savings.show', $id);
    }

    public function destroy($id)
    {
        $plan = SavingsPlan::where('user_id', Auth::id())->findOrFail($id);
        $plan->delete();

        return redirect()->route('savings.index');
    }
}
