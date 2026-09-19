<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-white">
                Branch Income / Sales
            </h2>

            <p class="text-sm text-slate-400 mt-1">
                View laboratory service income by branch and consolidated laboratory income.
            </p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Filters --}}
            <div class="bg-slate-900 border border-slate-700 rounded-xl p-5 mb-6">

                <form method="GET"
                      action="{{ route('accounting.branch-income.index') }}"
                      class="grid grid-cols-1 md:grid-cols-4 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Branch
                        </label>

                        <select name="branch_id"
                                class="w-full rounded-lg bg-slate-800 border-slate-700 text-white">

                            <option value="">
                                All Branches
                            </option>

                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    @selected(request('branch_id') == $branch->id)>
                                    {{ $branch->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            From
                        </label>

                        <input type="date"
                               name="from"
                               value="{{ request('from') }}"
                               class="w-full rounded-lg bg-slate-800 border-slate-700 text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            To
                        </label>

                        <input type="date"
                               name="to"
                               value="{{ request('to') }}"
                               class="w-full rounded-lg bg-slate-800 border-slate-700 text-white">
                    </div>

                    <div class="flex items-end gap-2">

                        <button type="submit"
                                class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium">
                            Filter
                        </button>

                        <a href="{{ route('accounting.branch-income.index') }}"
                           class="px-5 py-2.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-white font-medium">
                            Reset
                        </a>

                    </div>

                </form>

            </div>

            {{-- Total Income --}}
            <div class="bg-slate-900 border border-slate-700 rounded-xl p-6 mb-6">

                <p class="text-sm text-slate-400">
                    Total Laboratory Income
                </p>

                <p class="text-3xl font-bold text-white mt-2">
                    ₦{{ number_format($totalIncome, 2) }}
                </p>

                <p class="text-sm text-slate-500 mt-2">
                    Laboratory Services
                </p>

            </div>

            {{-- Income Entries --}}
            <div class="bg-slate-900 border border-slate-700 rounded-xl overflow-hidden">

                <div class="px-6 py-4 border-b border-slate-700">
                    <h3 class="text-lg font-semibold text-white">
                        Income Transactions
                    </h3>
                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-800">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Date
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Reference
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Branch
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Description
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase">
                                    Amount
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-700">

                            @forelse($incomeEntries as $entry)

                                <tr class="hover:bg-slate-800/50">

                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ $entry->journalEntry->entry_date?->format('d M Y') }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-white font-medium">
                                        {{ $entry->journalEntry->reference }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ $entry->journalEntry->branch?->name ?? 'Head Office' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ $entry->description ?? $entry->journalEntry->description }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-white text-right font-semibold">
                                        ₦{{ number_format($entry->credit, 2) }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-10 text-center text-slate-400">
                                        No income transactions found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                @if($incomeEntries->hasPages())

                    <div class="px-6 py-4 border-t border-slate-700">
                        {{ $incomeEntries->links() }}
                    </div>

                @endif

            </div>

        </div>
    </div>

</x-app-layout>