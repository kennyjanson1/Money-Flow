<div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-gray-100 dark:bg-slate-900">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100">Recent Transaction</h3>
        <div class="flex items-center gap-2">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="search" placeholder="Search"
                    class="pl-10 w-48 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            <button
                class="border border-slate-200 dark:border-slate-700 rounded-lg p-2 hover:bg-slate-50 dark:hover:bg-slate-800">
                <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                    </path>
                </svg>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200 dark:border-slate-700">
                    <th class="text-left py-3 px-4 text-sm font-medium text-slate-600 dark:text-slate-400">Title</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-slate-600 dark:text-slate-400">Category</th>
                    <th class="text-left py-3 px-4 text-sm font-medium text-slate-600 dark:text-slate-400">Date</th>
                    <th class="text-right py-3 px-4 text-sm font-medium text-slate-600 dark:text-slate-400">Amount</th>
                    <th class="text-right py-3 px-4 text-sm font-medium text-slate-600 dark:text-slate-400">Type</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr
                        class="border-b border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800">

                        {{-- TITLE / MERCHANT --}}
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center text-xl">
                                    {{ $transaction->type === 'income' ? '💰' : '💸' }}
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                        {{ $transaction->title }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        {{ $transaction->description }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- CATEGORY --}}
                        <td class="py-3 px-4">
                            <span class="text-sm text-slate-600 dark:text-slate-400">
                                {{ $transaction->category->name ?? 'No Category' }}
                            </span>
                        </td>

                        {{-- DATE --}}
                        <td class="py-3 px-4 text-sm text-slate-600 dark:text-slate-400">
                            {{ $transaction->date->format('d M Y') }}
                        </td>

                        {{-- AMOUNT --}}
                        <td
                            class="py-3 px-4 text-right text-sm font-medium 
        {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">

                            {{ $transaction->type === 'income' ? '+' : '-' }}
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>

                        {{-- STATUS / TYPE --}}
                        <td class="py-3 px-4 text-right">
                            @if ($transaction->type === 'income')
                                <span
                                    class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium 
                bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    Income
                                </span>
                            @else
                                <span
                                    class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium 
                bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                    Expense
                                </span>
                            @endif
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-slate-500">
                            No transactions available
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
