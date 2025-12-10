<!DOCTYPE html>
<html x-data="{
    darkMode: localStorage.getItem('theme') === 'dark'}" :class="{ 'dark': darkMode }" 
    x-init="$watch('darkMode', value => {
    localStorage.setItem('theme', value ? 'dark' : 'light');
})">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo-casholve.png') }}" type="image/png">

    <title>@yield('title', 'Casholve - Money Management Dashboard')</title>

    <!-- Tailwind CSS (HARUS PERTAMA) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Config (SETELAH TAILWIND) -->
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {}
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Enhanced scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
</head>

<body class="bg-gray-100 text-black dark:bg-slate-900 dark:text-white overflow-x-hidden min-h-screen"
    x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header -->
            @include('components.header')

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>

</html>