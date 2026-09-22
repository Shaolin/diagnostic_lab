<x-app-layout>

    <div class="p-6">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">
                Monthly Financial Report
            </h1>

            <p class="text-sm text-slate-400 mt-1">
                Financial summary for the selected month
            </p>
        </div>


        {{-- Month Filter --}}
        <div class="bg-slate-900 rounded-xl p-5 mb-6 shadow-lg">

            <form method="GET"
                  action="{{ route('accounting.monthly-financial-report') }}"
                  class="flex flex-wrap items-end gap-4">

                <div>
                    <label class="block text-sm text-slate-400 mb-2">
                        Month
                    </label>

                    <input
                        type="month"
                        name="month"
                        value="{{ $month }}"
                        class="rounded-lg bg-slate-800 border-slate-700 text-white"
                    >
                </div>

                <button
                    type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                    Generate Report
                </button>

            </form>

        </div>


        {{-- Profit & Loss Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

            <div class="bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-sm text-slate-400">
                    Total Income
                </p>

                <p class="text-2xl font-bold text-green-400 mt-2">
                    ₦{{ number_format($income, 2) }}
                </p>
            </div>


            <div class="bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-sm text-slate-400">
                    Total Expenses
                </p>

                <p class="text-2xl font-bold text-red-400 mt-2">
                    ₦{{ number_format($expenses, 2) }}
                </p>
            </div>


            <div class="bg-slate-900 rounded-xl p-5 shadow-lg">
                <p class="text-sm text-slate-400">
                    Net Profit / Loss
                </p>

                <p class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }} mt-2">
                    ₦{{ number_format($netProfit, 2) }}
                </p>
            </div>

        </div>


        {{-- Profit & Loss --}}
        <div class="bg-slate-900 rounded-xl shadow-lg mb-6">

            <div class="p-5 border-b border-slate-800">
                <h2 class="text-lg font-semibold text-white">
                    Profit & Loss Summary
                </h2>
            </div>

            <div class="p-5">

                <div class="flex justify-between py-3 border-b border-slate-800">
                    <span class="text-slate-300">
                        Total Income
                    </span>

                    <span class="text-green-400 font-medium">
                        ₦{{ number_format($income, 2) }}
                    </span>
                </div>


                <div class="flex justify-between py-3 border-b border-slate-800">
                    <span class="text-slate-300">
                        Total Expenses
                    </span>

                    <span class="text-red-400 font-medium">
                        (₦{{ number_format($expenses, 2) }})
                    </span>
                </div>


                <div class="flex justify-between py-4 font-bold text-lg">
                    <span class="text-white">
                        Net Profit / Loss
                    </span>

                    <span class="{{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">
                        ₦{{ number_format($netProfit, 2) }}
                    </span>
                </div>

            </div>

        </div>


        {{-- Cash Summary --}}
        <div class="bg-slate-900 rounded-xl shadow-lg">

            <div class="p-5 border-b border-slate-800">
                <h2 class="text-lg font-semibold text-white">
                    Cash & Bank Summary
                </h2>
            </div>

            <div class="p-5">

                <div class="flex justify-between py-3 border-b border-slate-800">
                    <span class="text-slate-300">
                        Cash Inflows
                    </span>

                    <span class="text-green-400 font-medium">
                        ₦{{ number_format($cashInflows, 2) }}
                    </span>
                </div>


                <div class="flex justify-between py-3 border-b border-slate-800">
                    <span class="text-slate-300">
                        Cash Outflows
                    </span>

                    <span class="text-red-400 font-medium">
                        (₦{{ number_format($cashOutflows, 2) }})
                    </span>
                </div>


                <div class="flex justify-between py-4 font-bold text-lg">
                    <span class="text-white">
                        Net Cash Flow
                    </span>

                    <span class="{{ $netCashFlow >= 0 ? 'text-green-400' : 'text-red-400' }}">
                        ₦{{ number_format($netCashFlow, 2) }}
                    </span>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>