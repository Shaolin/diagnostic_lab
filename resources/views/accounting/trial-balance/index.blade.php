<x-app-layout>

   
<div class="p-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">
            Trial Balance
        </h1>

        <p class="mt-1 text-sm text-slate-400">
            Summary of posted debit and credit balances.
        </p>
    </div>

    {{-- Filters --}}
    <div class="mb-6 rounded-xl border border-slate-700 bg-slate-900 p-5">
        <form method="GET"
              action="{{ route('accounting.trial-balance') }}"
              class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>
                <label for="from" class="block text-sm font-medium text-slate-300 mb-2">
                    From
                </label>

                <input
                    type="date"
                    name="from"
                    id="from"
                    value="{{ $from }}"
                    class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-white focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            <div>
                <label for="to" class="block text-sm font-medium text-slate-300 mb-2">
                    To
                </label>

                <input
                    type="date"
                    name="to"
                    id="to"
                    value="{{ $to }}"
                    class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-white focus:border-blue-500 focus:ring-blue-500"
                >
            </div>

            <div class="flex items-end">
                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition"
                >
                    Apply Filters
                </button>
            </div>

        </form>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
            <p class="text-sm text-slate-400">Total Debit</p>
            <p class="mt-1 text-xl font-bold text-white">
                ₦{{ number_format($totalDebit, 2) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
            <p class="text-sm text-slate-400">Total Credit</p>
            <p class="mt-1 text-xl font-bold text-white">
                ₦{{ number_format($totalCredit, 2) }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
            <p class="text-sm text-slate-400">Difference</p>

            <p class="mt-1 text-xl font-bold
                {{ abs($totalDebit - $totalCredit) < 0.01
                    ? 'text-green-400'
                    : 'text-red-400' }}">
                ₦{{ number_format(abs($totalDebit - $totalCredit), 2) }}
            </p>
        </div>

    </div>

    {{-- Trial Balance Table --}}
    <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-900">

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700">

                <thead class="bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                            Code
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                            Account
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase">
                            Debit
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-400 uppercase">
                            Credit
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-800">

                    @forelse($accounts as $account)

                        <tr class="hover:bg-slate-800/50 transition">

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-400">
                                {{ $account->code }}
                            </td>

                            <td class="px-6 py-4 text-sm font-medium text-white">
                                {{ $account->name }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-slate-300">
                                @if($account->debit_total > 0)
                                    ₦{{ number_format($account->debit_total, 2) }}
                                @else
                                    —
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-slate-300">
                                @if($account->credit_total > 0)
                                    ₦{{ number_format($account->credit_total, 2) }}
                                @else
                                    —
                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4"
                                class="px-6 py-10 text-center text-sm text-slate-400">
                                No posted transactions found for the selected period.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

                @if($accounts->count() > 0)
                    <tfoot class="bg-slate-800 border-t border-slate-700">
                        <tr>
                            <td colspan="2"
                                class="px-6 py-4 text-right text-sm font-bold text-white">
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
                @endif

            </table>
        </div>

    </div>

    </div>

</x-app-layout>

