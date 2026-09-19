<x-app-layout>

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-white">Profit & Loss</h1>
            <p class="text-sm text-slate-400">
                View income, expenses and net profit for the selected period.
            </p>
        </div>

        {{-- Filters --}}
        <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
            <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4">

                <div>
                    <label class="mb-1 block text-sm text-slate-400">Branch</label>
                    <select name="branch_id"
                            class="w-full rounded-lg border-slate-700 bg-slate-800 text-white">
                        <option value="">All Branches</option>

                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm text-slate-400">From</label>
                    <input type="date"
                           name="from"
                           value="{{ request('from') }}"
                           class="w-full rounded-lg border-slate-700 bg-slate-800 text-white">
                </div>

                <div>
                    <label class="mb-1 block text-sm text-slate-400">To</label>
                    <input type="date"
                           name="to"
                           value="{{ request('to') }}"
                           class="w-full rounded-lg border-slate-700 bg-slate-800 text-white">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                        Filter
                    </button>

                    <a href="{{ route('accounting.profit-loss.index') }}"
                       class="rounded-lg bg-slate-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-600">
                        Reset
                    </a>
                </div>

            </form>
        </div>

        {{-- Summary --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
                <p class="text-sm text-slate-400">Total Income</p>
                <p class="mt-2 text-2xl font-bold text-green-400">
                    ₦{{ number_format($totalIncome, 2) }}
                </p>
            </div>

            <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
                <p class="text-sm text-slate-400">Total Expenses</p>
                <p class="mt-2 text-2xl font-bold text-red-400">
                    ₦{{ number_format($totalExpenses, 2) }}
                </p>
            </div>

            <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
                <p class="text-sm text-slate-400">Net Profit / (Loss)</p>
                <p class="mt-2 text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">
                    ₦{{ number_format($netProfit, 2) }}
                </p>
            </div>

        </div>

        {{-- Income and Expenses --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Income --}}
            <div class="rounded-xl border border-slate-700 bg-slate-900">

                <div class="border-b border-slate-700 px-5 py-4">
                    <h2 class="font-semibold text-white">Income</h2>
                </div>

                <div class="p-5">

                    @forelse ($income as $item)

                        <div class="flex items-center justify-between border-b border-slate-800 py-3 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-white">
                                    {{ $item['account']->name }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ $item['account']->code }}
                                </p>
                            </div>

                            <p class="text-sm font-semibold text-green-400">
                                ₦{{ number_format($item['amount'], 2) }}
                            </p>
                        </div>

                    @empty

                        <p class="py-4 text-sm text-slate-500">
                            No income recorded for this period.
                        </p>

                    @endforelse

                    <div class="mt-4 flex justify-between border-t border-slate-700 pt-4">
                        <span class="font-semibold text-white">Total Income</span>
                        <span class="font-bold text-green-400">
                            ₦{{ number_format($totalIncome, 2) }}
                        </span>
                    </div>

                </div>
            </div>

            {{-- Expenses --}}
            <div class="rounded-xl border border-slate-700 bg-slate-900">

                <div class="border-b border-slate-700 px-5 py-4">
                    <h2 class="font-semibold text-white">Expenses</h2>
                </div>

                <div class="p-5">

                    @forelse ($expenses as $item)

                        <div class="flex items-center justify-between border-b border-slate-800 py-3 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-white">
                                    {{ $item['account']->name }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ $item['account']->code }}
                                </p>
                            </div>

                            <p class="text-sm font-semibold text-red-400">
                                ₦{{ number_format($item['amount'], 2) }}
                            </p>
                        </div>

                    @empty

                        <p class="py-4 text-sm text-slate-500">
                            No expenses recorded for this period.
                        </p>

                    @endforelse

                    <div class="mt-4 flex justify-between border-t border-slate-700 pt-4">
                        <span class="font-semibold text-white">Total Expenses</span>
                        <span class="font-bold text-red-400">
                            ₦{{ number_format($totalExpenses, 2) }}
                        </span>
                    </div>

                </div>
            </div>

        </div>

        {{-- Net Result --}}
        <div class="rounded-xl border border-slate-700 bg-slate-900 p-6">

            <div class="flex items-center justify-between">
                <span class="text-lg font-semibold text-white">
                    Net Profit / (Loss)
                </span>

                <span class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">
                    ₦{{ number_format($netProfit, 2) }}
                </span>
            </div>

        </div>

    </div>

</x-app-layout>