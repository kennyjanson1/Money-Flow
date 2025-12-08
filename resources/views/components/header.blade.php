@php
    $pageInfo = [
        'dashboard' => ['title' => 'Dashboard', 'description' => 'Track and Analyze Your Financial Performance'],
        'account' => ['title' => 'Account', 'description' => 'Manage Your Profile and Account Settings'],
        'transactions' => ['title' => 'Transaction', 'description' => 'View and Manage All Your Transactions'],
        'cashflow' => ['title' => 'Cash Flow', 'description' => 'Monitor Your Income and Expense Trends'],
        'goals' => ['title' => 'Goals', 'description' => 'Track Your Savings Goals and Progress'],
        'gethelp' => ['title' => 'Get Help', 'description' => 'Find Answers and Contact Support'],
    ];

    $currentRoute = request()->route()->getName();
    
    // Extract the first part of route name (e.g., "transactions.index" -> "transactions")
    $routePrefix = explode('.', $currentRoute)[0];
    
    // Try to get page info by full route name first, then by prefix, finally fallback to dashboard
    $currentPage = $pageInfo[$currentRoute] ?? $pageInfo[$routePrefix] ?? $pageInfo['dashboard'];
@endphp

<header
    class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
    <div class="flex items-center justify-between gap-3 sm:gap-6">
        <!-- Left: Menu & Title -->
        <div class="flex items-center gap-2 sm:gap-4 min-w-0">
            <button @click="sidebarOpen = true"
                class="lg:hidden p-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 flex-shrink-0">
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
            <div class="min-w-0">
                <h1 class="text-lg sm:text-xl md:text-2xl font-medium text-slate-900 dark:text-slate-100 truncate">
                    {{ $currentPage['title'] }}</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 hidden sm:block truncate">
                    {{ $currentPage['description'] }}</p>
            </div>
        </div>

        <!-- Right: Search, Notifications, Theme, Profile -->
        <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">

            <!-- Theme Toggle -->
            <button @click="darkMode = !darkMode"
                class="p-2 rounded-lg bg-slate-200 dark:bg-slate-700 transition">

                <!-- Moon (Light Mode) -->
                <svg x-show="!darkMode" class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                    </path>
                </svg>

                <!-- Sun (Dark Mode) -->
                <svg x-show="darkMode" x-cloak class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </button>

            <!-- User Profile -->
            <a href="{{ route('account') }}" class="flex items-center gap-2 cursor-pointer hover:opacity-80 transition">
                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&h=100&fit=crop"
                    alt="Profile" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full ring-2 ring-slate-100 dark:ring-slate-700">
                <div class="hidden lg:block">
                    <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->email }}</p>
                </div>
            </a>
        </div>
    </div>
</header>
