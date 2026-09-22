<x-app-layout>

    <div class="p-6">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">
                Cash Flow Statement
            </h1>

            <p class="text-sm text-slate-400 mt-1">
                Cash inflows and outflows for the selected period.
            </p>
        </div>

        {{-- Date Filter --}}
        <div class="bg-slate-800 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('accounting.cash-flow') }}"
                  class="flex flex-wrap items-end gap-4">

                <div>
                    <label class="block text-sm text-slate-300 mb-1">
                        From
                    </label>

                    <input
                        type="date"
                        name="from"
                        value="{{ $from }}"
                        class="bg-slate-700 border-slate-600 text-white rounded-md"
                    >
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-1">
                        To
                    </label>

                    <input
                        type="date"
                        name="to"
                        value="{{ $to }}"
                        class="bg-slate-700 border-slate-600 text-white rounded-md"
                    >
                </div>

                <button
                    type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md"
                >
                    Generate Report
                </button>

            </form>
        </div>

        {{-- Cash Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

            <div class="bg-slate-800 rounded-lg p-5">
                <p class="text-sm text-slate-400">
                    Opening Cash & Bank
                </p>

                <p class="text-2xl font-bold text-white mt-2">
                    ₦{{ number_format($openingBalance, 2) }}
                </p>
            </div>

            <div class="bg-slate-800 rounded-lg p-5">
                <p class="text-sm text-slate-400">
                    Net Cash Flow
                </p>

                <p class="text-2xl font-bold text-white mt-2">
                    ₦{{ number_format($netCashFlow, 2) }}
                </p>
            </div>

            <div class="bg-slate-800 rounded-lg p-5">
                <p class="text-sm text-slate-400">
                    Closing Cash & Bank
                </p>

                <p class="text-2xl font-bold text-white mt-2">
                    ₦{{ number_format($closingBalance, 2) }}
                </p>
            </div>

        </div>

        {{-- Operating Activities --}}
        <div class="bg-slate-800 rounded-lg mb-6 overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-700">
                <h2 class="text-lg font-semibold text-white">
                    Operating Activities
                </h2>
            </div>

            <div class="p-5 space-y-3">

                <div class="flex justify-between">
                    <span class="text-slate-300">
                        Cash Received from Laboratory Services & Other Income
                    </span>

                    <span class="text-green-400">
                        ₦{{ number_format($operatingInflows, 2) }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-300">
                        Operating Expenses Paid
                    </span>

                    <span class="text-red-400">
                        (₦{{ number_format($operatingOutflows, 2) }})
                    </span>
                </div>

                <div class="border-t border-slate-700 pt-3 flex justify-between font-semibold">
                    <span class="text-white">
                        Net Cash from Operating Activities
                    </span>

                    <span class="text-white">
                        ₦{{ number_format($operatingNet, 2) }}
                    </span>
                </div>

            </div>
        </div>

        {{-- Investing Activities --}}
        <div class="bg-slate-800 rounded-lg mb-6 overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-700">
                <h2 class="text-lg font-semibold text-white">
                    Investing Activities
                </h2>
            </div>

            <div class="p-5 space-y-3">

                <div class="flex justify-between">
                    <span class="text-slate-300">
                        Fixed Asset Purchases
                    </span>

                    <span class="text-red-400">
                        (₦{{ number_format($investingOutflows, 2) }})
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-300">
                        Asset Disposal Proceeds
                    </span>

                    <span class="text-green-400">
                        ₦{{ number_format($investingInflows, 2) }}
                    </span>
                </div>

                <div class="border-t border-slate-700 pt-3 flex justify-between font-semibold">
                    <span class="text-white">
                        Net Cash from Investing Activities
                    </span>

                    <span class="text-white">
                        ₦{{ number_format($investingNet, 2) }}
                    </span>
                </div>

            </div>
        </div>

        {{-- Financing Activities --}}
        <div class="bg-slate-800 rounded-lg mb-6 overflow-hidden">

            <div class="px-5 py-4 border-b border-slate-700">
                <h2 class="text-lg font-semibold text-white">
                    Financing Activities
                </h2>
            </div>

            <div class="p-5 space-y-3">

                <div class="flex justify-between">
                    <span class="text-slate-300">
                        Financing Inflows
                    </span>

                    <span class="text-green-400">
                        ₦{{ number_format($financingInflows, 2) }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-slate-300">
                        Financing Outflows
                    </span>

                    <span class="text-red-400">
                        (₦{{ number_format($financingOutflows, 2) }})
                    </span>
                </div>

                <div class="border-t border-slate-700 pt-3 flex justify-between font-semibold">
                    <span class="text-white">
                        Net Cash from Financing Activities
                    </span>

                    <span class="text-white">
                        ₦{{ number_format($financingNet, 2) }}
                    </span>
                </div>

            </div>
        </div>

        {{-- Final Summary --}}
        <div class="bg-slate-900 border border-slate-700 rounded-lg p-6">

            <div class="flex justify-between mb-3">
                <span class="text-slate-300">
                    Opening Cash & Bank
                </span>

                <span class="text-white">
                    ₦{{ number_format($openingBalance, 2) }}
                </span>
            </div>

            <div class="flex justify-between mb-3">
                <span class="text-slate-300">
                    Net Cash Flow
                </span>

                <span class="text-white">
                    ₦{{ number_format($netCashFlow, 2) }}
                </span>
            </div>

            <div class="border-t border-slate-700 pt-4 flex justify-between">
                <span class="text-lg font-bold text-white">
                    Closing Cash & Bank
                </span>

                <span class="text-lg font-bold text-white">
                    ₦{{ number_format($closingBalance, 2) }}
                </span>
            </div>

        </div>

    </div>

</x-app-layout>