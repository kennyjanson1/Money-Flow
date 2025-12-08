<div class="border border-slate-200 dark:border-slate-700 shadow-lg rounded-2xl p-6 bg-gray-100 dark:bg-slate-900">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-medium text-slate-900 dark:text-slate-100">Recent Transaction</h3>
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
                    <tr class="border-b border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800">
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
                        <td class="py-3 px-4 text-right text-sm font-medium 
                            {{ $transaction->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }}
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </td>

                        {{-- STATUS / TYPE --}}
                        <td class="py-3 px-4 text-right">
                            @if ($transaction->type === 'income')
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    Income
                                </span>
                            @else
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium 
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
