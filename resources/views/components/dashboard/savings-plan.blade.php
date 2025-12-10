@php
    // Ambil active goals dari database
    $savingsPlans = auth()->user()->savingsPlans()
        ->where('status', 'active')
        ->orderBy('deadline', 'asc')
        ->limit(3) // Hanya ambil 3 goals teratas
        ->get();
    
    // Hitung total current amount dari semua active goals
    $totalSaved = auth()->user()->savingsPlans()
        ->where('status', 'active')
        ->sum('current_amount');
@endphp

<div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-white dark:bg-slate-900 h-full">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100">My Savings Plan</h3>
        <a href="{{ route('goals.index') }}" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>

    <div class="text-3xl font-medium text-slate-900 dark:text-slate-100 mb-6">
        Rp {{ number_format($totalSaved, 0, ',', '.') }}
    </div>

    @if($savingsPlans->count() > 0)
        <div class="space-y-4">
            @foreach($savingsPlans as $plan)
                @php
                    $percentage = $plan->target_amount > 0 
                        ? round(($plan->current_amount / $plan->target_amount) * 100) 
                        : 0;
                    
                    // Icon mapping berdasarkan keyword di title
                    $iconMap = [
                        'emergency' => ['icon' => '🚨', 'bg' => 'bg-red-100 dark:bg-red-900/30'],
                        'vacation' => ['icon' => '✈️', 'bg' => 'bg-blue-100 dark:bg-blue-900/30'],
                        'car' => ['icon' => '🚗', 'bg' => 'bg-purple-100 dark:bg-purple-900/30'],
                        'house' => ['icon' => '🏠', 'bg' => 'bg-green-100 dark:bg-green-900/30'],
                        'home' => ['icon' => '🏠', 'bg' => 'bg-green-100 dark:bg-green-900/30'],
                        'education' => ['icon' => '📚', 'bg' => 'bg-indigo-100 dark:bg-indigo-900/30'],
                        'wedding' => ['icon' => '💍', 'bg' => 'bg-pink-100 dark:bg-pink-900/30'],
                        'retirement' => ['icon' => '🏖️', 'bg' => 'bg-amber-100 dark:bg-amber-900/30'],
                        'laptop' => ['icon' => '💻', 'bg' => 'bg-slate-100 dark:bg-slate-700'],
                        'phone' => ['icon' => '📱', 'bg' => 'bg-cyan-100 dark:bg-cyan-900/30'],
                        'gadget' => ['icon' => '📱', 'bg' => 'bg-cyan-100 dark:bg-cyan-900/30'],
                    ];
                    
                    // Default icon
                    $icon = '💰';
                    $iconBg = 'bg-emerald-100 dark:bg-emerald-900/30';
                    
                    // Cek keyword di title
                    $titleLower = strtolower($plan->title);
                    foreach ($iconMap as $keyword => $data) {
                        if (str_contains($titleLower, $keyword)) {
                            $icon = $data['icon'];
                            $iconBg = $data['bg'];
                            break;
                        }
                    }
                @endphp
                
                <a href="{{ route('goals.show', $plan->id) }}" class="block bg-slate-50 dark:bg-slate-800 rounded-xl p-4 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="{{ $iconBg }} w-10 h-10 rounded-lg flex items-center justify-center text-xl flex-shrink-0">
                                {{ $icon }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-slate-900 dark:text-slate-100 truncate">
                                    {{ $plan->title }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    Rp {{ number_format($plan->current_amount, 0, ',', '.') }}/Rp {{ number_format($plan->target_amount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-slate-900 dark:text-slate-100 flex-shrink-0 ml-2">
                            {{ $percentage }}%
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ min($percentage, 100) }}%"></div>
                    </div>
                </a>
            @endforeach
        </div>
        
        @if(auth()->user()->savingsPlans()->where('status', 'active')->count() > 3)
            <div class="mt-4 text-center">
                <a href="{{ route('goals.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium">
                    View All Goals →
                </a>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-8 text-center">
            <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">No active savings goals</p>
            <p class="text-xs text-slate-500 dark:text-slate-500 mb-4">Start planning for your future</p>
            <a href="{{ route('goals.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition">
                Create Your First Goal
            </a>
        </div>
    @endif
</div>  