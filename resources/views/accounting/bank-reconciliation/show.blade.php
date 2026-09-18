<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-white">
                    Bank Reconciliation
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    {{ $bankReconciliation->bankAccount?->name }}
                    —
                    {{ $bankReconciliation->statement_date?->format('d M Y') }}
                </p>
            </div>

            <a href="{{ route('accounting.bank-reconciliation.index') }}"
               class="rounded-lg border border-slate-700 px-4 py-2 text-sm font-semibold text-slate-300 hover:bg-slate-800">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

            {{-- Summary --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-5">

                <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
                    <p class="text-sm text-slate-400">
                        Statement Opening
                    </p>

                    <p class="mt-2 text-xl font-semibold text-white">
                        ₦{{ number_format((float) $bankReconciliation->statement_opening_balance, 2) }}
                    </p>
                </div>

                <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
                    <p class="text-sm text-slate-400">
                        Statement Closing
                    </p>

                    <p class="mt-2 text-xl font-semibold text-white">
                        ₦{{ number_format((float) $bankReconciliation->statement_closing_balance, 2) }}
                    </p>
                </div>

                   <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
    <p class="text-sm text-slate-400">
        Book Balance
    </p>

    <p class="mt-2 text-xl font-semibold text-white">
        ₦{{ number_format((float) $bookBalance, 2) }}
    </p>
</div>

                <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
                    <p class="text-sm text-slate-400">
                        Reconciled
                    </p>

                    <p class="mt-2 text-xl font-semibold text-emerald-400">
                        ₦{{ number_format((float) $bankReconciliation->reconciled_balance, 2) }}
                    </p>
                </div>

                <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
                    <p class="text-sm text-slate-400">
                        Difference
                    </p>

                    <p class="mt-2 text-xl font-semibold
                        {{ (float) $bankReconciliation->difference == 0
                            ? 'text-emerald-400'
                            : 'text-amber-400' }}">
                        ₦{{ number_format((float) $bankReconciliation->difference, 2) }}
                    </p>
                </div>

            </div>

            {{-- Information --}}
            <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
                <div class="flex flex-wrap gap-6 text-sm">

                    <div>
                        <span class="text-slate-500">Branch:</span>
                        <span class="ml-1 text-slate-300">
                            {{ $bankReconciliation->branch?->name ?? 'Head Office' }}
                        </span>
                    </div>

                    <div>
                        <span class="text-slate-500">Status:</span>

                        @if ($bankReconciliation->status === 'completed')
                            <span class="ml-1 text-emerald-400">
                                Completed
                            </span>
                        @else
                            <span class="ml-1 text-amber-400">
                                Open
                            </span>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Transactions --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-900">

                <div class="border-b border-slate-700 px-5 py-4">
                    <h3 class="font-semibold text-white">
                        Bank Transactions
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Transactions recorded in the accounting system for this bank account.
                    </p>
                </div>

                <form method="POST" action="{{ route('accounting.bank-reconciliation.reconcile', $bankReconciliation) }}">
                      @csrf

                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-800">



                            
                            <tr>

                                <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-400">
    Select
</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-400">
                                    Date
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-400">
                                    Reference
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-400">
                                    Description
                                </th>

                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-slate-400">
                                    Debit
                                </th>

                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-slate-400">
                                    Credit
                                </th>

                                <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-slate-400">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-800">

                            @forelse ($transactions as $transaction)

                                @php
                                    $isReconciled = in_array(
                                        $transaction->id,
                                        $reconciledLineIds
                                    );
                                @endphp

                                <tr class="{{ $isReconciled ? 'bg-emerald-500/5' : 'hover:bg-slate-800/50' }}">

                                    <td class="px-5 py-4 text-center">
    @if (!$isReconciled)
        <input
            type="checkbox"
            name="journal_entry_line_ids[]"
            value="{{ $transaction->id }}"
            class="rounded border-slate-600 bg-slate-800 text-emerald-500 focus:ring-emerald-500"
        >
    @else
        <span class="text-emerald-400">✓</span>
    @endif
</td>

                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-300">
                                        {{ $transaction->journalEntry?->created_at?->format('d M Y') }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-white">
                                        {{ $transaction->journalEntry?->reference ?? '—' }}
                                    </td>

                                    <td class="px-5 py-4 text-sm text-slate-300">
                                        {{ $transaction->journalEntry?->description ?? '—' }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-emerald-400">
                                        @if ((float) $transaction->debit > 0)
                                            ₦{{ number_format((float) $transaction->debit, 2) }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-red-400">
                                        @if ((float) $transaction->credit > 0)
                                            ₦{{ number_format((float) $transaction->credit, 2) }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-center">

                                        @if ($isReconciled)
                                            <span class="inline-flex rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-400">
                                                Reconciled
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-slate-700 px-3 py-1 text-xs font-medium text-slate-400">
                                                Unreconciled
                                            </span>
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center">

                                        <div class="text-4xl">🏦</div>

                                        <p class="mt-3 text-sm font-medium text-slate-300">
                                            No bank transactions found.
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            Posted transactions for this bank account will appear here.
                                        </p>

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>
                    <div class="border-t border-slate-700 px-5 py-4 flex justify-end">
    <button
        type="submit"
        class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-500"
    >
        Reconcile Selected
    </button>
</div>

</div>
</form>

@if ($bankReconciliation->status !== 'completed')
    <div class="border-t border-slate-700 px-5 py-4 flex justify-end">
        <form
            method="POST"
            action="{{ route('accounting.bank-reconciliation.complete', $bankReconciliation) }}"
        >
            @csrf

            <button
                type="submit"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500"
            >
                Complete Reconciliation
            </button>
        </form>
    </div>
@endif
                </div>

                @if ($transactions->hasPages())
                    <div class="border-t border-slate-700 px-5 py-4">
                        {{ $transactions->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>