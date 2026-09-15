<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-white">
                General Ledger
            </h2>

            <p class="mt-1 text-sm text-slate-400">
                View and review posted accounting transactions.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Filters --}}
            <div class="mb-6 rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                <form method="GET"
                      action="{{ route('accounting.general-ledger') }}">

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-5">

                        {{-- Account --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">
                                Account
                            </label>

                            <select
                                name="account_id"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option value="">All Accounts</option>

                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}"
                                        @selected($accountId == $account->id)>
                                        {{ $account->code }} - {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Branch --}}
<div>
    <label class="mb-2 block text-sm font-medium text-slate-300">
        Branch
    </label>

    <select
        name="branch_id"
        class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
    >
        <option value="">All Branches</option>

        @foreach ($branches as $branch)
            <option value="{{ $branch->id }}"
                @selected($branchId == $branch->id)>
                {{ $branch->name }}
            </option>
        @endforeach
    </select>
</div>

                        {{-- From --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">
                                From Date
                            </label>

                            <input
                                type="date"
                                name="from"
                                value="{{ $from }}"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                        </div>

                        {{-- To --}}
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">
                                To Date
                            </label>

                            <input
                                type="date"
                                name="to"
                                value="{{ $to }}"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-end gap-3">

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                            >
                                Filter
                            </button>

                            <a
                                href="{{ route('accounting.general-ledger') }}"
                                class="inline-flex w-full items-center justify-center rounded-lg border border-slate-600 bg-slate-900 px-5 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700"
                            >
                                Reset
                            </a>

                        </div>

                    </div>

                </form>

            </div>


            {{-- Ledger Table --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-900">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Date
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Reference
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Account
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Description
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Debit
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Credit
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-700 bg-slate-800">

                            @forelse ($ledger as $line)

                                <tr class="transition hover:bg-slate-700/40">

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-300">
                                        {{ $line->journalEntry->entry_date->format('d M Y') }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">

                                        <span class="font-mono text-sm text-indigo-300">
                                            {{ $line->journalEntry->reference ?? '—' }}
                                        </span>

                                    </td>

                                    <td class="px-6 py-4">

                                        <div class="space-y-1">

                                            <div class="font-semibold text-white">
                                                {{ $line->account->name }}
                                            </div>

                                            <div class="font-mono text-xs text-slate-500">
                                                {{ $line->account->code }}
                                            </div>

                                        </div>

                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-400">
                                        {{ $line->description ?? $line->journalEntry->description }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium text-slate-200">
                                        @if ($line->debit > 0)
                                            ₦{{ number_format($line->debit, 2) }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium text-slate-200">
                                        @if ($line->credit > 0)
                                            ₦{{ number_format($line->credit, 2) }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="px-6 py-12 text-center">

                                        <div class="flex flex-col items-center">

                                            <svg class="mb-4 h-12 w-12 text-slate-600"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="1.5"
                                                 viewBox="0 0 24 24">

                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z"
                                                />

                                            </svg>

                                            <h3 class="text-lg font-semibold text-white">
                                                No ledger transactions found
                                            </h3>

                                            <p class="mt-2 text-sm text-slate-400">
                                                Posted accounting transactions will appear here.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>


                        {{-- Totals --}}
                        <tfoot class="border-t border-slate-700 bg-slate-900">

                            <tr>

                                <td colspan="4"
                                    class="px-6 py-4 text-right text-sm font-semibold text-slate-300">
                                    Total
                                </td>

                                <td class="px-6 py-4 text-right text-sm font-bold text-white">
                                    ₦{{ number_format($totalDebit, 2) }}
                                </td>

                                <td class="px-6 py-4 text-right text-sm font-bold text-white">
                                    ₦{{ number_format($totalCredit, 2) }}
                                </td>

                            </tr>

                        </tfoot>

                    </table>

                  @if($ledger->hasPages())
    <div class="border-t border-slate-700 px-6 py-4">
        {{ $ledger->links() }}
    </div>
@endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>