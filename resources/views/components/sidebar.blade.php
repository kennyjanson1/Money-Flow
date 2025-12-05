<aside 
    x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed lg:sticky inset-y-0 left-0 top-0 z-50 flex flex-col w-64 h-screen bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-700 p-6 transition-transform duration-300 lg:translate-x-0 overflow-y-auto"
>
    <!-- Logo and Close Button -->
    <div class="flex items-center justify-between mb-10 flex-shrink-0">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                <div class="w-4 h-4 bg-white rounded"></div>
            </div>
            <h1 class="text-xl font-medium text-slate-900 dark:text-slate-100">Moneta</h1>
        </div>
        <button 
            @click="sidebarOpen = false"
            class="lg:hidden p-2 rounded-xl hover:bg-slate-500 dark:hover:bg-slate-800"
        >
            <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 space-y-1 overflow-y-auto">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-900/10 dark:hover:bg-slate-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="font-medium">Dashboard</span>
        </a>
        
        <!-- Transaction -->
        <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl transition {{ request()->routeIs('transactions.*') || request()->routeIs('transaction') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-900/10 dark:hover:bg-slate-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
            </svg>
            <span class="font-medium">Transaction</span>
        </a>
        
        <!-- Cash Flow -->
        <a href="{{ route('cashflow') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl transition {{ request()->routeIs('cashflow') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-900/10 dark:hover:bg-slate-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
            <span class="font-medium">Cash Flow</span>
        </a>
        
        <!-- Goals -->
        <a href="{{ route('goals.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl transition {{ request()->routeIs('goals.*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-900/10 dark:hover:bg-slate-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">Goals</span>
        </a>
    </nav>
    
    <!-- Bottom Items -->
    <div class="space-y-1 mt-4 pt-4 border-t border-slate-200 dark:border-slate-700 flex-shrink-0">
        <!-- Account -->
        <a href="{{ route('account') }}" class="flex items-center gap-3 px-4 py-2 rounded-xl transition {{ request()->routeIs('account') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-900/10 dark:hover:bg-slate-800' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="font-medium">Account</span>
        </a>
        
        <!-- Get Help -->
        <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-xl transition text-slate-600 dark:text-slate-400 hover:bg-slate-900/10 dark:hover:bg-slate-800">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">Get Help</span>
        </a>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button 
                type="submit"
                class="w-full flex items-center gap-3 px-4 py-2 rounded-xl text-slate-600 dark:text-slate-400 hover:bg-red-100 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 transition"
            >
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span class="font-medium">Logout</span>
            </button>
        </form>
    </div>
</aside>