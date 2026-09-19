<x-app-layout>

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-white">Balance Sheet</h1>
            <p class="text-sm text-slate-400">
                Financial position as at the selected date.
            </p>
        </div>

        {{-- Filters --}}
        <div class="rounded-xl border border-slate-700 bg-slate-900 p-5">
            <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3">

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
                    <label class="mb-1 block text-sm text-slate-400">As At</label>
                    <input type="date"
                           name="as_at"
                           value="{{ $asAt }}"
                           class="w-full rounded-lg border-slate-700 bg-slate-800 text-white">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                        Filter
                    </button>

                    <a href="{{ route('accounting.balance-sheet.index') }}"
                       class="rounded-lg bg-slate-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-600">
                        Reset
                    </a>
                </div>

            </form>
        </div>

        {{-- Date --}}
        <div class="text-sm text-slate-400">
            As at <span class="font-medium text-white">
                {{ \Carbon\Carbon::parse($asAt)->format('d M Y') }}
            </span>
        </div>

        {{-- Main Report --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Assets --}}
            <div class="rounded-xl border border-slate-700 bg-slate-900">

                <div class="border-b border-slate-700 px-5 py-4">
                    <h2 class="font-semibold text-white">Assets</h2>
                </div>

                <div class="p-5">

                    @forelse ($assets as $item)

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
                                ₦{{ number_format($item['balance'], 2) }}
                            </p>
                        </div>

                    @empty

                        <p class="py-4 text-sm text-slate-500">
                            No assets recorded.
                        </p>

                    @endforelse

                    <div class="mt-4 flex justify-between border-t border-slate-700 pt-4">
                        <span class="font-semibold text-white">Total Assets</span>
                        <span class="font-bold text-green-400">
                            ₦{{ number_format($totalAssets, 2) }}
                        </span>
                    </div>

                </div>
            </div>

            {{-- Liabilities & Equity --}}
            <div class="space-y-6">

                {{-- Liabilities --}}
                <div class="rounded-xl border border-slate-700 bg-slate-900">

                    <div class="border-b border-slate-700 px-5 py-4">
                        <h2 class="font-semibold text-white">Liabilities</h2>
                    </div>

                    <div class="p-5">

                        @forelse ($liabilities as $item)

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
                                    ₦{{ number_format(abs($item['balance']), 2) }}
                                </p>
                            </div>

                        @empty

                            <p class="py-4 text-sm text-slate-500">
                                No liabilities recorded.
                            </p>

                        @endforelse

                        <div class="mt-4 flex justify-between border-t border-slate-700 pt-4">
                            <span class="font-semibold text-white">Total Liabilities</span>
                            <span class="font-bold text-red-400">
                                ₦{{ number_format(abs($totalLiabilities), 2) }}
                            </span>
                        </div>

                    </div>
                </div>

                {{-- Equity --}}
                <div class="rounded-xl border border-slate-700 bg-slate-900">

                    <div class="border-b border-slate-700 px-5 py-4">
                        <h2 class="font-semibold text-white">Equity</h2>
                    </div>

                    <div class="p-5">

                        @forelse ($equity as $item)

                            <div class="flex items-center justify-between border-b border-slate-800 py-3 last:border-0">
                                <div>
                                    <p class="text-sm font-medium text-white">
                                        {{ $item['account']->name }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        {{ $item['account']->code }}
                                    </p>
                                </div>

                                <p class="text-sm font-semibold text-blue-400">
                                    ₦{{ number_format(abs($item['balance']), 2) }}
                                </p>
                            </div>

                        @empty

                            <p class="py-4 text-sm text-slate-500">
                                No equity recorded.
                            </p>

                        @endforelse

                        <div class="flex justify-between border-b border-slate-700 py-3">
                            <span class="text-sm text-slate-300">
                                Current Period Profit / (Loss)
                            </span>

                            <span class="text-sm font-semibold {{ $currentProfitLoss >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                ₦{{ number_format(abs($currentProfitLoss), 2) }}
                            </span>
                        </div>

                        <div class="mt-4 flex justify-between border-t border-slate-700 pt-4">
                            <span class="font-semibold text-white">Total Equity</span>
                            <span class="font-bold text-blue-400">
                                ₦{{ number_format(abs($totalEquityWithProfit), 2) }}
                            </span>
                        </div>

                    </div>
                </div>

            </div>

        </div>

        {{-- Balance Check --}}
        <div class="rounded-xl border border-slate-700 bg-slate-900 p-6">

            <div class="flex items-center justify-between">
                <span class="text-lg font-semibold text-white">
                    Total Liabilities + Equity
                </span>

                <span class="text-2xl font-bold text-white">
                    ₦{{ number_format(abs($totalLiabilitiesAndEquity), 2) }}
                </span>
            </div>

        </div>

    </div>

</x-app-layout>