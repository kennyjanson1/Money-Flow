@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 px-4">

    <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
        
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-slate-100">
                Create New Savings Goal
            </h2>
            <p class="text-slate-600 dark:text-slate-400 text-sm mt-1">
                Plan your savings and reach your financial goals.
            </p>
        </div>

        <form action="{{ route('goals.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Goal Title --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    Goal Title
                </label>
                <input 
                    type="text"
                    name="title"
                    required
                    placeholder="e.g. New Laptop, Vacation, Emergency Fund"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            {{-- Target Amount --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    Target Amount (Rp)
                </label>
                <input 
                    type="number"
                    name="target_amount"
                    required
                    min="1"
                    placeholder="e.g. 5000000"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            {{-- Deadline --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    Deadline
                </label>
                <input 
                    type="date"
                    name="deadline"
                    required
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            {{-- Monthly Saving --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    Monthly Saving Amount (Rp)
                </label>
                <input 
                    type="number"
                    name="monthly_saving"
                    required
                    min="1"
                    placeholder="e.g. 500000"
                    class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            {{-- Buttons --}}
            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('goals.index') }}" class="text-slate-600 dark:text-slate-300 text-sm hover:underline">
                    Cancel
                </a>

                <button 
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg text-sm font-medium">
                    Create Goal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
