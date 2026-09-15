
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-slate-100 leading-tight">
                    {{ $pettyCashFund->name }}
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    {{ $pettyCashFund->branch->name }}
                    · Custodian: {{ $pettyCashFund->custodian->name }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('accounting.petty-cash.funds.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg border border-slate-600 text-sm font-medium text-slate-300 hover:bg-slate-700">
                    Back to Funds
                </a>

                @if ($pettyCashFund->is_active)
                    <a href="{{ route('accounting.petty-cash.transactions.create', $pettyCashFund) }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-lg font-semibold text-sm text-white hover:bg-indigo-500">
                        Record Transaction
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 rounded-lg bg-emerald-500/10 border border-emerald-500/30 px-4 py-3 text-green-300 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Fund Summary --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

                <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
                    <p class="text-sm text-slate-400">
                        Current Balance
                    </p>

                    <p class="mt-2 text-2xl font-bold text-slate-100">
                        ₦{{ number_format((float) $pettyCashFund->current_balance, 2) }}
                    </p>
                </div>

                <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
                    <p class="text-sm text-slate-400">
                        Opening Balance
                    </p>

                    <p class="mt-2 text-2xl font-bold text-slate-100">
                        ₦{{ number_format((float) $pettyCashFund->opening_balance, 2) }}
                    </p>
                </div>

                <div class="bg-slate-800 border border-slate-700 rounded-xl p-5">
                    <p class="text-sm text-slate-400">
                        Status
                    </p>

                    <div class="mt-3">
                        @if ($pettyCashFund->is_active)
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-emerald-500/10 text-slate-300">
                                Active
                            </span>
                        @else
                            <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-slate-600 text-slate-300">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Transactions --}}
            <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">

                <div class="px-6 py-4 border-b border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-100">
                        Transactions
                    </h3>
                </div>

                @if ($transactions->count())

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-700">

                            <thead class="bg-slate-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                                        Date
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                                        Type
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                                        Description
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                                        Account
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">
                                        Amount
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                                        Recorded By
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-700">

                                @foreach ($transactions as $transaction)

                                    <tr class="hover:bg-slate-700/40">

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                            {{ $transaction->transaction_date->format('d M Y') }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if ($transaction->type === 'expense')
                                                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-red-500/10 text-red-300">
                                                    Expense
                                                </span>
                                            @else
                                                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-500/10 text-slate-300">
                                                    Replenishment
                                                </span>
                                            @endif

                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="text-sm text-slate-100">
                                                {{ $transaction->description }}
                                            </div>

                                            @if ($transaction->reference)
                                                <div class="text-xs text-slate-500 mt-1">
                                                    Ref: {{ $transaction->reference }}
                                                </div>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                            @if ($transaction->type === 'expense')
                                                {{ $transaction->account?->name ?? '—' }}
                                            @else
                                                {{ $transaction->sourceAccount?->name ?? '—' }}
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold
                                            {{ $transaction->type === 'expense' ? 'text-red-300' : 'text-slate-300' }}">

                                            {{ $transaction->type === 'expense' ? '-' : '+' }}
                                            ₦{{ number_format((float) $transaction->amount, 2) }}

                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                            {{ $transaction->recordedBy->name }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>
                        </table>
                    </div>

                @else

                    <div class="px-6 py-12 text-center">
                        <p class="text-slate-400">
                            No transactions have been recorded for this petty cash fund yet.
                        </p>

                        @if ($pettyCashFund->is_active)
                            <a href="{{ route('accounting.petty-cash.transactions.create', $pettyCashFund) }}"
                               class="inline-flex items-center mt-4 px-4 py-2 bg-indigo-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                                Record First Transaction
                            </a>
                        @endif
                    </div>

                @endif

            </div>

        </div>
    </div>
</x-app-layout>

