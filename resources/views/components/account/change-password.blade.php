@extends('layouts.app')

@section('title', 'Account - Casholve')

@section('content')
<div class="space-y-6">
    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Info -->
        <div class="lg:col-span-2 border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
            <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-6">Profile Information</h3>
            
            <div class="flex items-start gap-6 mb-6">
                <!-- User Avatar with Initial -->
                <div class="w-24 h-24 rounded-full bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-3xl ring-4 ring-slate-100 dark:ring-slate-700">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h2 class="text-2xl font-medium text-slate-900 dark:text-slate-100">{{ Auth::user()->name }}</h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                            Active User
                        </span>
                    </div>
                    <p class="text-base text-slate-600 dark:text-slate-400 mb-4">{{ Auth::user()->email }}</p>
                    <div class="flex gap-2">
                        <button 
                            onclick="enableEdit()"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition"
                        >
                            Edit Profile
                        </button>
                        <a 
                            href="{{ route('account.password') }}"
                            class="border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition"
                        >
                            Change Password
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <form action="{{ route('account.update') }}" method="POST" id="profileForm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Full Name</label>
                        <input 
                            type="text" 
                            name="name"
                            value="{{ old('name', Auth::user()->name) }}" 
                            class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-60 disabled:cursor-not-allowed"
                            disabled
                            id="nameInput"
                        >
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Email Address</label>
                        <input 
                            type="email" 
                            name="email"
                            value="{{ old('email', Auth::user()->email) }}" 
                            class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-60 disabled:cursor-not-allowed"
                            disabled
                            id="emailInput"
                        >
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Member Since</label>
                        <input 
                            type="text" 
                            value="{{ Auth::user()->created_at->format('F d, Y') }}" 
                            class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none"
                            disabled
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Last Updated</label>
                        <input 
                            type="text" 
                            value="{{ Auth::user()->updated_at->format('F d, Y') }}" 
                            class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none"
                            disabled
                        >
                    </div>
                </div>

                <!-- Save/Cancel Buttons (Hidden by default) -->
                <div class="mt-4 gap-2 hidden" id="editButtons">
                    <button 
                        type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition"
                    >
                        Save Changes
                    </button>
                    <button 
                        type="button"
                        onclick="cancelEdit()"
                        class="border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition"
                    >
                        Cancel
                    </button>
                </div>
            </form>
        </div>

        <!-- Account Stats -->
        <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
            <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-6">Account Stats</h3>
            
            <div class="space-y-4">
                <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4">
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Member Since</p>
                    <p class="text-base font-medium text-slate-900 dark:text-slate-100">
                        {{ Auth::user()->created_at->format('F Y') }}
                    </p>
                </div>
                
                <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4">
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Account Status</p>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <p class="text-base font-medium text-slate-900 dark:text-slate-100">Active</p>
                    </div>
                </div>
                
                <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4">
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Account Type</p>
                    <p class="text-base font-medium text-slate-900 dark:text-slate-100">Free Account</p>
                </div>
                
                <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4">
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Email Verified</p>
                    <p class="text-base font-medium text-slate-900 dark:text-slate-100">
                        @if(Auth::user()->email_verified_at)
                            <span class="text-green-600 dark:text-green-400">✓ Verified</span>
                        @else
                            <span class="text-orange-600 dark:text-orange-400">Not Verified</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Security -->
        <div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
            <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100 mb-4">Security</h3>
            <div class="space-y-3">
                <a href="{{ route('account.password') }}" class="w-full flex items-center justify-between p-3 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-medium text-slate-900 dark:text-slate-100">Change Password</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Update your password</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <button class="w-full flex items-center justify-between p-3 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-lg transition opacity-50 cursor-not-allowed">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-medium text-slate-900 dark:text-slate-100">Two-Factor Authentication</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Coming soon</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="border border-red-200 dark:border-red-800 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900">
            <h3 class="text-lg font-medium text-red-600 dark:text-red-400 mb-4">Danger Zone</h3>
            <div class="space-y-3">
                <a href="{{ route('account.delete') }}" class="w-full flex items-center justify-between p-3 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-medium text-slate-900 dark:text-slate-100">Delete Account</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Permanently delete your account</p>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function enableEdit() {
    document.getElementById('nameInput').disabled = false;
    document.getElementById('emailInput').disabled = false;
    document.getElementById('editButtons').classList.remove('hidden');
}

function cancelEdit() {
    document.getElementById('nameInput').disabled = true;
    document.getElementById('emailInput').disabled = true;
    document.getElementById('editButtons').classList.add('hidden');
    // Reset form to original values
    document.getElementById('profileForm').reset();
}
</script>
@endsection