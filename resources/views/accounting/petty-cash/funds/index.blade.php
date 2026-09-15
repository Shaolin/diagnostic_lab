<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-slate-100 leading-tight">
                    Petty Cash Funds
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    Manage petty cash funds for your branches.
                </p>
            </div>

            <a href="{{ route('accounting.petty-cash.funds.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                New Petty Cash Fund
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 rounded-lg bg-emerald-500/10 border border-emerald-500/30 px-4 py-3 text-green-300 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">

                <div class="px-6 py-4 border-b border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-100">
                        Petty Cash Funds
                    </h3>
                </div>

                @if ($funds->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-700">
                            <thead class="bg-slate-900/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                                        Fund
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                                        Branch
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                                        Custodian
                                    </th>

                                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase tracking-wider">
                                        Balance
                                    </th>

                                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-700">
                                @foreach ($funds as $fund)
                                    <tr class="hover:bg-slate-700/40">

                                        <td class="px-6 py-4 whitespace-nowrap">
                                           <a href="{{ route('accounting.petty-cash.transactions.index', $fund) }}"
   class="text-sm font-medium text-indigo-400 hover:text-indigo-300">
    {{ $fund->name }}
</a>

                                            <div class="text-xs text-slate-400">
                                                Opening: {{ $fund->opening_balance }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                            {{ $fund->branch->name }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                            {{ $fund->custodian->name }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100 text-right font-semibold">
                                            {{ $fund->current_balance }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if ($fund->is_active)
                                                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-500/10 text-slate-300">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-slate-600 text-slate-300">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="text-slate-400 mb-4">
                            No petty cash funds have been created yet.
                        </div>

                        <a href="{{ route('accounting.petty-cash.funds.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                            Create First Fund
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>