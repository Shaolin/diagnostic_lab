<x-app-layout>

    <x-slot name="header">

        <div>

            <h2 class="text-xl font-semibold text-white">
                Reports
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                View laboratory activity and financial performance for a selected period.
            </p>

        </div>

    </x-slot>


    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-7xl px-4 text-white sm:px-6 lg:px-8">


            {{-- Date Filter --}}
            <div class="mb-6 overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                <div class="border-b border-slate-700 px-6 py-5">

                    <h3 class="text-lg font-semibold text-white">
                        Report Period
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Select the period you want to analyse.
                    </p>

                </div>


                <form
                    method="GET"
                    action="{{ route('reports.index') }}"
                    class="p-6"
                >

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:items-end">


                        {{-- From --}}
                        <div>

                            <label
                                for="from"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                From
                            </label>

                            <input
                                type="date"
                                name="from"
                                id="from"
                                value="{{ $from }}"
                                class="block w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>


                        {{-- To --}}
                        <div>

                            <label
                                for="to"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                To
                            </label>

                            <input
                                type="date"
                                name="to"
                                id="to"
                                value="{{ $to }}"
                                class="block w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-3 text-white focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>


                        {{-- Generate --}}
                        <div>

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                            >

                                <svg
                                    class="mr-2 h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 12.414V19a1 1 0 01-.553.894l-4 2A1 1 0 019 21v-8.586L3.293 6.707A1 1 0 013 6V4z"
                                    />
                                </svg>

                                Generate Report

                            </button>

                        </div>

                    </div>


                    {{-- Quick Periods --}}
                    <div class="mt-5 flex flex-wrap gap-2">

                        <a
                            href="{{ route('reports.index', [
                                'from' => now()->format('Y-m-d'),
                                'to' => now()->format('Y-m-d'),
                            ]) }}"
                            class="rounded-lg border border-slate-600 px-3 py-2 text-xs font-semibold text-slate-300 transition hover:bg-slate-700 hover:text-white"
                        >
                            Today
                        </a>


                        <a
                            href="{{ route('reports.index', [
                                'from' => now()->startOfWeek()->format('Y-m-d'),
                                'to' => now()->format('Y-m-d'),
                            ]) }}"
                            class="rounded-lg border border-slate-600 px-3 py-2 text-xs font-semibold text-slate-300 transition hover:bg-slate-700 hover:text-white"
                        >
                            This Week
                        </a>


                        <a
                            href="{{ route('reports.index', [
                                'from' => now()->startOfMonth()->format('Y-m-d'),
                                'to' => now()->format('Y-m-d'),
                            ]) }}"
                            class="rounded-lg border border-slate-600 px-3 py-2 text-xs font-semibold text-slate-300 transition hover:bg-slate-700 hover:text-white"
                        >
                            This Month
                        </a>

                    </div>

                </form>

            </div>


            {{-- Report Period --}}
            <div class="mb-6 rounded-lg border border-indigo-800 bg-indigo-900/20 px-5 py-4">

                <p class="text-sm text-indigo-300">

                    Showing report for

                    <span class="font-semibold text-white">
                        {{ \Carbon\Carbon::parse($from)->format('d M Y') }}
                    </span>

                    to

                    <span class="font-semibold text-white">
                        {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
                    </span>

                </p>

            </div>


            {{-- Financial Summary --}}
            <div class="mb-6">

                <div class="mb-4">

                    <h3 class="text-lg font-semibold text-white">
                        Financial Summary
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Financial activity for the selected period.
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">


                    {{-- Total Billed --}}
                    <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-slate-400">
                                    Total Billed
                                </p>

                                <p class="mt-2 text-2xl font-bold text-white">
                                    ₦{{ number_format((float) $totalBilled, 2) }}
                                </p>

                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-blue-900/30">

                                <span class="text-xl">
                                    🧾
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- Total Collected --}}
                    <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-slate-400">
                                    Total Collected
                                </p>

                                <p class="mt-2 text-2xl font-bold text-emerald-400">
                                    ₦{{ number_format((float) $totalCollected, 2) }}
                                </p>

                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-green-900/30">

                                <span class="text-xl">
                                    💰
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- Outstanding --}}
                    <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm font-medium text-slate-400">
                                    Outstanding Balance
                                </p>

                                <p class="mt-2 text-2xl font-bold text-yellow-400">
                                    ₦{{ number_format((float) $totalOutstanding, 2) }}
                                </p>

                            </div>

                            <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-yellow-900/30">

                                <span class="text-xl">
                                    ⏳
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Test Request Summary --}}
            <div class="mb-6 overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                <div class="border-b border-slate-700 px-6 py-5">

                    <h3 class="text-lg font-semibold text-white">
                        Test Request Summary
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Test requests created during the selected period.
                    </p>

                </div>


                <div class="grid grid-cols-2 divide-x divide-slate-700 md:grid-cols-5">


                    {{-- Total --}}
                    <div class="px-5 py-6">

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Total
                        </p>

                        <p class="mt-2 text-2xl font-bold text-white">
                            {{ number_format($requestSummary['total']) }}
                        </p>

                    </div>


                    {{-- Pending --}}
                    <div class="px-5 py-6">

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Pending
                        </p>

                        <p class="mt-2 text-2xl font-bold text-yellow-400">
                            {{ number_format($requestSummary['pending']) }}
                        </p>

                    </div>


                    {{-- In Progress --}}
                    <div class="border-t border-slate-700 px-5 py-6 md:border-t-0">

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            In Progress
                        </p>

                        <p class="mt-2 text-2xl font-bold text-blue-400">
                            {{ number_format($requestSummary['in_progress']) }}
                        </p>

                    </div>


                    {{-- Completed --}}
                    <div class="border-t border-slate-700 px-5 py-6 md:border-t-0">

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Completed
                        </p>

                        <p class="mt-2 text-2xl font-bold text-emerald-400">
                            {{ number_format($requestSummary['completed']) }}
                        </p>

                    </div>


                    {{-- Partially Completed --}}
                    <div class="border-t border-slate-700 px-5 py-6 md:border-t-0">

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Partially Completed
                        </p>

                        <p class="mt-2 text-2xl font-bold text-orange-400">
                            {{ number_format($requestSummary['partially_completed']) }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Payment Methods --}}
            <div class="mb-6 overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                <div class="border-b border-slate-700 px-6 py-5">

                    <h3 class="text-lg font-semibold text-white">
                        Payment Methods
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Payments received during the selected period, grouped by method.
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-900">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Payment Method
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Amount Collected
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-700">

                            @forelse($paymentMethods as $method)

                                <tr class="transition hover:bg-slate-700/40">

                                    <td class="px-6 py-4">

                                        <span class="text-sm font-medium text-white">
                                            {{ $method->payment_method }}
                                        </span>

                                    </td>

                                    <td class="px-6 py-4 text-right">

                                        <span class="font-semibold text-emerald-400">
                                            ₦{{ number_format((float) $method->total, 2) }}
                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="2"
                                        class="px-6 py-10 text-center text-sm text-slate-500"
                                    >
                                        No payments were recorded during this period.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Most Requested Tests --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                <div class="border-b border-slate-700 px-6 py-5">

                    <h3 class="text-lg font-semibold text-white">
                        Most Requested Tests
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        The most frequently requested tests during the selected period.
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-900">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    #
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Test
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Requests
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Revenue
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-700">

                            @forelse($mostRequestedTests as $index => $test)

                                <tr class="transition hover:bg-slate-700/40">

                                    <td class="px-6 py-4 text-sm text-slate-500">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="px-6 py-4">

                                        <span class="text-sm font-medium text-white">
                                            {{ $test->test_name }}
                                        </span>

                                    </td>

                                    <td class="px-6 py-4 text-right">

                                        <span class="text-sm font-semibold text-indigo-400">
                                            {{ number_format($test->requests_count) }}
                                        </span>

                                    </td>

                                    <td class="px-6 py-4 text-right">

                                        <span class="text-sm font-semibold text-emerald-400">
                                            ₦{{ number_format((float) $test->revenue, 2) }}
                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="4"
                                        class="px-6 py-10 text-center text-sm text-slate-500"
                                    >
                                        No test requests were recorded during this period.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


        </div>

    </div>

</x-app-layout>