{{-- resources/views/components/goals/goal-card.blade.php --}}

@php
    use Carbon\Carbon;
    
    $percentage = $goal->target_amount > 0 
        ? round(($goal->current_amount / $goal->target_amount) * 100) 
        : 0;
    
    // Determine status based on progress and deadline
    $now = Carbon::now();
    $deadline = $goal->deadline ? Carbon::parse($goal->deadline) : null;
    $status = 'Active';
    $statusColor = 'border-green-300 text-green-700 dark:border-green-700 dark:text-green-400';

    if ($deadline) {
        $daysRemaining = $now->diffInDays($deadline, false);
        if ($daysRemaining < 0) {
            $status = 'Overdue';
            $statusColor = 'border-red-300 text-red-700 dark:border-red-700 dark:text-red-400';
        } elseif ($percentage >= 75) {
            $status = 'On Track';
            $statusColor = 'border-green-300 text-green-700 dark:border-green-700 dark:text-green-400';
        } elseif ($percentage >= 50) {
            $status = 'Good Progress';
            $statusColor = 'border-blue-300 text-blue-700 dark:border-blue-700 dark:text-blue-400';
        } else {
            $status = 'Needs Attention';
            $statusColor = 'border-amber-300 text-amber-700 dark:border-amber-700 dark:text-amber-400';
        }
    } else {
        if ($percentage >= 75) {
            $status = 'Almost There';
            $statusColor = 'border-green-300 text-green-700 dark:border-green-700 dark:text-green-400';
        } elseif ($percentage >= 50) {
            $status = 'Good Progress';
            $statusColor = 'border-blue-300 text-blue-700 dark:border-blue-700 dark:text-blue-400';
        } else {
            $status = 'Just Started';
            $statusColor = 'border-slate-300 text-slate-700 dark:border-slate-700 dark:text-slate-400';
        }
    }
    
    // Icon colors
    $iconColors = [
        'bg-red-100 dark:bg-red-900/30',
        'bg-blue-100 dark:bg-blue-900/30',
        'bg-indigo-100 dark:bg-indigo-900/30',
        'bg-emerald-100 dark:bg-emerald-900/30',
        'bg-purple-100 dark:bg-purple-900/30',
        'bg-amber-100 dark:bg-amber-900/30',
    ];
    
    $progressColors = [
        'bg-red-500',
        'bg-blue-500',
        'bg-indigo-500',
        'bg-emerald-500',
        'bg-purple-500',
        'bg-amber-500',
    ];
    
    $iconBg = $iconColors[$goal->id % count($iconColors)];
    $progressColor = $progressColors[$goal->id % count($progressColors)];
    
    // Icons based on goal type (you can add icon field to database)
    $icons = ['🎯', '💰', '🏠', '✈️', '🚗', '💻', '📱', '🎓', '⚽'];
    $icon = $icons[$goal->id % count($icons)];
@endphp

<div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-4 md:p-5 hover:shadow-md transition-shadow border border-slate-200 dark:border-slate-700 cursor-pointer"
     onclick="window.location='{{ route('goals.show', $goal->id) }}'">
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="{{ $iconBg }} w-10 h-10 md:w-12 md:h-12 rounded-lg flex items-center justify-center text-xl md:text-2xl flex-shrink-0">
                {{ $icon }}
            </div>
            <div>
                <h4 class="text-base md:text-lg font-semibold text-slate-900 dark:text-slate-100 mb-1">{{ $goal->title }}</h4>
                <p class="text-sm text-slate-600 dark:text-slate-400">Savings Goal</p>
            </div>
        </div>
        <span class="px-2 py-1 rounded-md text-xs border {{ $statusColor }}">
            {{ $status }}
        </span>
    </div>

    <div class="mb-3">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm md:text-base text-slate-600 dark:text-slate-400">
                Rp {{ number_format($goal->current_amount, 0, ',', '.') }} / Rp {{ number_format($goal->target_amount, 0, ',', '.') }}
            </span>
            <span class="text-sm md:text-base font-semibold text-slate-900 dark:text-slate-100">
                {{ $percentage }}%
            </span>
        </div>
        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2.5 overflow-hidden">
            <div class="{{ $progressColor }} h-2.5 rounded-full transition-all duration-500" style="width: {{ min($percentage, 100) }}%"></div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-200 dark:border-slate-700">
        <div>
            <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400 mb-1">Deadline</p>
            <p class="text-sm md:text-base font-medium text-slate-900 dark:text-slate-100">
                {{ $goal->deadline ? Carbon::parse($goal->deadline)->format('M Y') : 'No deadline' }}
            </p>
        </div>
        <div>
            <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400 mb-1">Remaining</p>
            <p class="text-sm md:text-base font-medium text-slate-900 dark:text-slate-100">
                Rp {{ number_format(max($goal->target_amount - $goal->current_amount, 0), 0, ',', '.') }}
            </p>
        </div>
    </div>
</div>