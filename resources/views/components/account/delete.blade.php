@extends('layouts.app')

@section('title', 'Delete Account - Casholve')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('account.index') }}" class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 mb-6 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Account
    </a>

    <!-- Delete Account Form -->
    <div class="border border-red-200 dark:border-red-800 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
        <div class="mb-6">  
            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-medium text-slate-900 dark:text-slate-100">Delete Account</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">This action cannot be undone</p>
        </div>

        <!-- Warning Box -->
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
            <h3 class="font-medium text-red-900 dark:text-red-400 mb-2">Warning</h3>
            <ul class="text-sm text-red-700 dark:text-red-400 space-y-1">
                <li>• All your personal data will be permanently deleted</li>
                <li>• All your categories will be removed</li>
                <li>• All your transactions will be removed</li>
                <li>• This action cannot be reversed</li>
            </ul>
        </div>

        <form action="{{ route('account.destroy') }}" method="POST" onsubmit="return confirm('Are you absolutely sure? This action cannot be undone!')">
            @csrf
            @method('DELETE')

            <!-- Password Confirmation -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">
                    Confirm your password to continue
                </label>
                <input 
                    type="password" 
                    name="password"
                    placeholder="Enter your current password"
                    class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                    required
                >
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-3">
                <button 
                    type="submit"
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white rounded-lg px-4 py-2.5 text-sm font-medium transition"
                >
                    Yes, Delete My Account
                </button>
                <a 
                    href="{{ route('account.index') }}"
                    class="flex-1 text-center border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection