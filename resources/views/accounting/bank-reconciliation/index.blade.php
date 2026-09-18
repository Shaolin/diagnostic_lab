<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-white">
                    Bank Reconciliation
                </h2>
                <p class="text-sm text-slate-400 mt-1">
                    Reconcile your bank statement with the laboratory's accounting records.
                </p>
            </div>

            <a href="{{ route('accounting.bank-reconciliation.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-500">
                <span>+</span>
                New Reconciliation
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Filters --}}
            <div class="mb-6 rounded-xl border border-slate-700 bg-slate-900 p-5">
                <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3">

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-300">
                            Branch
                        </label>

                        <select name="branch_id"
                                class="w-full rounded-lg border-slate-700 bg-slate-800 text-white focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Branches</option>

                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    @selected(request('branch_id') == $branch->id)>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-300">
                            Status
                        </label>

                        <select name="status"
                                class="w-full rounded-lg border-slate-700 bg-slate-800 text-white focus:border-blue-500 focus:ring-blue-500">
                            <option value="">All Statuses</option>
                            <option value="open" @selected(request('status') === 'open')>
                                Open
                            </option>
                            <option value="completed" @selected(request('status') === 'completed')>
                                Completed
                            </option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="rounded-lg bg-slate-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-600">
                            Filter
                        </button>

                        <a href="{{ route('accounting.bank-reconciliation.index') }}"
                           class="rounded-lg border border-slate-700 px-4 py-2.5 text-sm font-semibold text-slate-300 hover:bg-slate-800">
                            Reset
                        </a>
                    </div>

                </form>
            </div>

            {{-- Reconciliation Table --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-900">

                <div class="border-b border-slate-700 px-5 py-4">
                    <h3 class="font-semibold text-white">
                        Reconciliation History
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700">
                        <thead class="bg-slate-800">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Statement Date
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Bank Account
                                </th>

                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Branch
                                </th>

                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Statement Balance
                                </th>

                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Difference
                                </th>

                                <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-800">
                            @forelse ($reconciliations as $reconciliation)
                                <tr class="hover:bg-slate-800/50">

                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-white">
                                        {{ $reconciliation->statement_date?->format('d M Y') }}
                                    </td>

                                    <td class="px-5 py-4 text-sm text-slate-300">
                                        {{ $reconciliation->bankAccount?->name }}
                                        <span class="text-xs text-slate-500">
                                            ({{ $reconciliation->bankAccount?->code }})
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-sm text-slate-300">
                                        {{ $reconciliation->branch?->name ?? 'Head Office' }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-white">
                                        ₦{{ number_format((float) $reconciliation->statement_closing_balance, 2) }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm
                                        {{ (float) $reconciliation->difference == 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                        ₦{{ number_format((float) $reconciliation->difference, 2) }}
                                    </td>

                                    <td class="px-5 py-4 text-center">
                                        @if ($reconciliation->status === 'completed')
                                            <span class="inline-flex rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-medium text-emerald-400">
                                                Completed
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-amber-500/10 px-3 py-1 text-xs font-medium text-amber-400">
                                                Open
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">
                                        <div class="text-4xl">🏦</div>

                                        <p class="mt-3 text-sm font-medium text-slate-300">
                                            No bank reconciliations found.
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            Start by creating your first bank reconciliation.
                                        </p>

                                        <a href="{{ route('accounting.bank-reconciliation.create') }}"
                                           class="mt-4 inline-flex rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                                            Create Reconciliation
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($reconciliations->hasPages())
                    <div class="border-t border-slate-700 px-5 py-4">
                        {{ $reconciliations->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>