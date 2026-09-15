<x-app-layout>

    <div class="p-6">

        @if(session('success'))
    <div class="mb-6 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-3 text-sm text-green-300">
        {{ session('success') }}
    </div>
@endif

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Operating Expenses</h1>
                <p class="mt-1 text-sm text-slate-400">
                    Record and monitor day-to-day operating expenses.
                </p>
            </div>

            <a href="{{ route('accounting.expenses.create') }}"
               class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg transition hover:bg-blue-700">
                + Record Expense
            </a>
        </div>

        {{-- Summary --}}
        <div class="mb-6 rounded-xl border border-slate-700 bg-slate-800 p-5">
            <p class="text-sm text-slate-400">Total Expenses</p>
            <p class="mt-1 text-2xl font-bold text-white">
                ₦{{ number_format($totalExpenses, 2) }}
            </p>
        </div>

        {{-- Filters --}}
        <div class="mb-6 rounded-xl border border-slate-700 bg-slate-800 p-5">
            <form method="GET" action="{{ route('accounting.expenses') }}"
                  class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">

                <div>
                    <label class="mb-1 block text-sm text-slate-300">Branch</label>
                    <select name="branch_id"
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Branches</option>

                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-slate-300">Expense Account</label>
                    <select name="expense_account_id"
                            class="w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Expense Accounts</option>

                        @foreach($expenseAccounts as $account)
                            <option value="{{ $account->id }}"
                                {{ request('expense_account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->code }} - {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-slate-300">From</label>
                    <input type="date"
                           name="from"
                           value="{{ request('from') }}"
                           class="w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="mb-1 block text-sm text-slate-300">To</label>
                    <input type="date"
                           name="to"
                           value="{{ request('to') }}"
                           class="w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-sm text-white focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-600">
                        Filter
                    </button>

                    <a href="{{ route('accounting.expenses') }}"
                       class="rounded-lg border border-slate-600 px-4 py-2 text-sm font-semibold text-slate-300 hover:bg-slate-700">
                        Clear
                    </a>
                </div>

            </form>
        </div>

        {{-- Expenses Table --}}
        <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-700">

                    <thead class="bg-slate-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Date
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Branch
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Expense Account
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Description
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Amount
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Payment
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                Recorded By
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-700">

                        @forelse($expenses as $expense)

                            <tr class="hover:bg-slate-750">

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-300">
                                    {{ $expense->expense_date->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-300">
                                    {{ $expense->branch?->name ?? 'Head Office' }}
                                </td>

                                <td class="px-6 py-4 text-sm">
                                    <span class="font-medium text-white">
                                        {{ $expense->expenseAccount->name }}
                                    </span>

                                    <span class="block text-xs text-slate-500">
                                        {{ $expense->expenseAccount->code }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-300">
                                    {{ $expense->description ?? '—' }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-semibold text-white">
                                    ₦{{ number_format($expense->amount, 2) }}
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-300">
                                    {{ $expense->payment_method }}

                                    @if($expense->payment_reference)
                                        <span class="block text-xs text-slate-500">
                                            {{ $expense->payment_reference }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-300">
                                    {{ $expense->createdBy->name }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <p class="text-sm text-slate-400">
                                        No expenses recorded yet.
                                    </p>

                                    <a href="{{ route('accounting.expenses.create') }}"
                                       class="mt-3 inline-block text-sm font-medium text-blue-400 hover:text-blue-300">
                                        Record your first expense
                                    </a>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            @if($expenses->hasPages())
                <div class="border-t border-slate-700 px-6 py-4">
                    {{ $expenses->links() }}
                </div>
            @endif

        </div>

    </div>

</x-app-layout>