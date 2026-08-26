<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="text-xl font-semibold text-white">
                Dashboard
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Overview of your laboratory's current activity.
            </p>
        </div>

    </x-slot>


    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-7xl px-4 text-white sm:px-6 lg:px-8">


            {{-- ================================================================
                MAIN STATISTICS
            ================================================================= --}}

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">


                {{-- Total Patients --}}
                <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-slate-400">
                                Total Patients
                            </p>

                            <p class="mt-2 text-3xl font-bold text-white">
                                {{ number_format($totalPatients) }}
                            </p>

                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-900/30">
                            <span class="text-2xl">🩺</span>
                        </div>

                    </div>

                </div>


                {{-- Total Test Requests --}}
                <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-slate-400">
                                Test Requests
                            </p>

                            <p class="mt-2 text-3xl font-bold text-white">
                                {{ number_format($totalTestRequests) }}
                            </p>

                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-900/30">
                            <span class="text-2xl">🧾</span>
                        </div>

                    </div>

                </div>


                {{-- Pending Requests --}}
                <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-slate-400">
                                Pending Requests
                            </p>

                            <p class="mt-2 text-3xl font-bold text-yellow-400">
                                {{ number_format($pendingRequests) }}
                            </p>

                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-yellow-900/30">
                            <span class="text-2xl">⏳</span>
                        </div>

                    </div>

                </div>


                {{-- Outstanding Balance --}}
                <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm font-medium text-slate-400">
                                Outstanding Balance
                            </p>

                            <p class="mt-2 text-2xl font-bold text-red-400">
                                ₦{{ number_format((float) $outstandingBalance, 2) }}
                            </p>

                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-900/30">
                            <span class="text-2xl">💰</span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================================
                TODAY'S ACTIVITY
            ================================================================= --}}

            <div class="mt-8">

                <div class="mb-4">

                    <h3 class="text-lg font-semibold text-white">
                        Today's Activity
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Quick overview of today's laboratory activity.
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">


                    {{-- New Patients --}}
                    <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">

                        <p class="text-sm text-slate-400">
                            New Patients
                        </p>

                        <p class="mt-2 text-2xl font-bold text-white">
                            {{ number_format($newPatientsToday) }}
                        </p>

                    </div>


                    {{-- Test Requests --}}
                    <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">

                        <p class="text-sm text-slate-400">
                            Test Requests
                        </p>

                        <p class="mt-2 text-2xl font-bold text-indigo-400">
                            {{ number_format($testRequestsToday) }}
                        </p>

                    </div>


                    {{-- Payments --}}
                    <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">

                        <p class="text-sm text-slate-400">
                            Payments Received
                        </p>

                        <p class="mt-2 text-2xl font-bold text-emerald-400">
                            ₦{{ number_format((float) $paymentsToday, 2) }}
                        </p>

                    </div>


                    {{-- Results --}}
                    <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">

                        <p class="text-sm text-slate-400">
                            Results Completed
                        </p>

                        <p class="mt-2 text-2xl font-bold text-blue-400">
                            {{ number_format($resultsToday) }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- ================================================================
                QUICK ACTIONS
            ================================================================= --}}

            <div class="mt-8 rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                <div class="mb-5">

                    <h3 class="text-lg font-semibold text-white">
                        Quick Actions
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Common actions for laboratory staff.
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">


                    {{-- Register Patient --}}
                    <a
                        href="{{ route('patients.create') }}"
                        class="flex items-center gap-3 rounded-lg border border-slate-700 bg-slate-900 px-4 py-4 transition hover:border-blue-500 hover:bg-slate-700"
                    >

                        <span class="text-xl">
                            🩺
                        </span>

                        <div>

                            <p class="text-sm font-semibold text-white">
                                Register Patient
                            </p>

                            <p class="text-xs text-slate-500">
                                Add a new patient
                            </p>

                        </div>

                    </a>


                    {{-- New Test Request --}}
                    <a
                        href="{{ route('test-requests.create') }}"
                        class="flex items-center gap-3 rounded-lg border border-slate-700 bg-slate-900 px-4 py-4 transition hover:border-indigo-500 hover:bg-slate-700"
                    >

                        <span class="text-xl">
                            🧾
                        </span>

                        <div>

                            <p class="text-sm font-semibold text-white">
                                New Test Request
                            </p>

                            <p class="text-xs text-slate-500">
                                Request laboratory tests
                            </p>

                        </div>

                    </a>


                    {{-- Record Payment --}}
                    <a
                        href="{{ route('payments.index') }}"
                        class="flex items-center gap-3 rounded-lg border border-slate-700 bg-slate-900 px-4 py-4 transition hover:border-emerald-500 hover:bg-slate-700"
                    >

                        <span class="text-xl">
                            💳
                        </span>

                        <div>

                            <p class="text-sm font-semibold text-white">
                                Payments
                            </p>

                            <p class="text-xs text-slate-500">
                                Manage payments
                            </p>

                        </div>

                    </a>


                    {{-- Results --}}
                    <a
                        href="{{ route('results.index') }}"
                        class="flex items-center gap-3 rounded-lg border border-slate-700 bg-slate-900 px-4 py-4 transition hover:border-purple-500 hover:bg-slate-700"
                    >

                        <span class="text-xl">
                            📄
                        </span>

                        <div>

                            <p class="text-sm font-semibold text-white">
                                Results
                            </p>

                            <p class="text-xs text-slate-500">
                                Manage test results
                            </p>

                        </div>

                    </a>

                </div>

            </div>


            {{-- ================================================================
                RECENT TEST REQUESTS + RECENT PAYMENTS
            ================================================================= --}}

            <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">


                {{-- Recent Test Requests --}}
                <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                    <div class="flex items-center justify-between border-b border-slate-700 px-6 py-5">

                        <div>

                            <h3 class="text-lg font-semibold text-white">
                                Recent Test Requests
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Latest laboratory requests.
                            </p>

                        </div>


                        <a
                            href="{{ route('test-requests.index') }}"
                            class="text-sm font-semibold text-indigo-400 hover:text-indigo-300"
                        >
                            View All
                        </a>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-slate-700">

                            <thead class="bg-slate-900">

                                <tr>

                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Patient
                                    </th>

                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Tests
                                    </th>

                                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-slate-700">

                                @forelse($recentTestRequests as $testRequest)

                                    <tr class="transition hover:bg-slate-700/40">

                                        <td class="px-5 py-4">

                                            <a
                                                href="{{ route('test-requests.show', $testRequest) }}"
                                                class="text-sm font-medium text-white hover:text-indigo-400"
                                            >
                                                {{ $testRequest->patient->full_name ?? 'Unknown Patient' }}
                                            </a>

                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $testRequest->tracking_code }}
                                            </p>

                                        </td>


                                        <td class="px-5 py-4">

                                            <span class="text-sm text-slate-300">
                                                {{ $testRequest->items->count() }}
                                                {{ $testRequest->items->count() === 1 ? 'test' : 'tests' }}
                                            </span>

                                        </td>


                                        <td class="px-5 py-4 text-right">

                                            @if($testRequest->overall_status === 'Completed')

                                                <span class="inline-flex rounded-full bg-green-900/30 px-2.5 py-1 text-xs font-semibold text-green-400 ring-1 ring-green-700">
                                                    Completed
                                                </span>

                                            @elseif($testRequest->overall_status === 'In Progress')

                                                <span class="inline-flex rounded-full bg-blue-900/30 px-2.5 py-1 text-xs font-semibold text-blue-400 ring-1 ring-blue-700">
                                                    In Progress
                                                </span>

                                            @elseif($testRequest->overall_status === 'Partially Completed')

                                                <span class="inline-flex rounded-full bg-orange-900/30 px-2.5 py-1 text-xs font-semibold text-orange-400 ring-1 ring-orange-700">
                                                    Partially Completed
                                                </span>

                                            @else

                                                <span class="inline-flex rounded-full bg-yellow-900/30 px-2.5 py-1 text-xs font-semibold text-yellow-400 ring-1 ring-yellow-700">
                                                    Pending
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="3"
                                            class="px-5 py-10 text-center text-sm text-slate-500"
                                        >
                                            No test requests found.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- Recent Payments --}}
                <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                    <div class="flex items-center justify-between border-b border-slate-700 px-6 py-5">

                        <div>

                            <h3 class="text-lg font-semibold text-white">
                                Recent Payments
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Latest payment transactions.
                            </p>

                        </div>


                        <a
                            href="{{ route('payments.index') }}"
                            class="text-sm font-semibold text-indigo-400 hover:text-indigo-300"
                        >
                            View All
                        </a>

                    </div>


                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-slate-700">

                            <thead class="bg-slate-900">

                                <tr>

                                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Patient
                                    </th>

                                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Amount
                                    </th>

                                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                        Method
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-slate-700">

                                @forelse($recentPayments as $payment)

                                    <tr class="transition hover:bg-slate-700/40">

                                        <td class="px-5 py-4">

                                            <a
                                                href="{{ route('payments.show', $payment) }}"
                                                class="text-sm font-medium text-white hover:text-indigo-400"
                                            >
                                                {{ $payment->testRequest->patient->full_name ?? 'Unknown Patient' }}
                                            </a>

                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $payment->paid_at?->format('d M Y, h:i A') ?? '—' }}
                                            </p>

                                        </td>


                                        <td class="px-5 py-4 text-right">

                                            <span class="text-sm font-semibold text-emerald-400">
                                                ₦{{ number_format((float) $payment->amount, 2) }}
                                            </span>

                                        </td>


                                        <td class="px-5 py-4 text-right">

                                            <span class="inline-flex rounded-full bg-slate-700 px-2.5 py-1 text-xs font-semibold text-slate-300">
                                                {{ $payment->payment_method }}
                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="3"
                                            class="px-5 py-10 text-center text-sm text-slate-500"
                                        >
                                            No payments found.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- ================================================================
                ACTIVE TESTS
            ================================================================= --}}

            <div class="mt-8 overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                <div class="flex items-center justify-between border-b border-slate-700 px-6 py-5">

                    <div>

                        <h3 class="text-lg font-semibold text-white">
                            Active Tests
                        </h3>

                        <p class="mt-1 text-sm text-slate-500">
                            Tests currently waiting to be completed.
                        </p>

                    </div>


                    <a
                        href="{{ route('test-requests.index') }}"
                        class="text-sm font-semibold text-indigo-400 hover:text-indigo-300"
                    >
                        View Requests
                    </a>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-900">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Patient
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Test
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Sample
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-700">

                            @forelse($activeTests as $test)

                                <tr class="transition hover:bg-slate-700/40">

                                    <td class="px-6 py-4">

                                        <span class="text-sm font-medium text-white">
                                            {{ $test->testRequest->patient->full_name ?? 'Unknown Patient' }}
                                        </span>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $test->testRequest->tracking_code }}
                                        </p>

                                    </td>


                                    <td class="px-6 py-4">

                                        <span class="text-sm text-slate-300">
                                            {{ $test->test_name }}
                                        </span>

                                    </td>


                                    <td class="px-6 py-4">

                                        @if($test->sample_status === 'Collected')

                                            <span class="inline-flex rounded-full bg-green-900/30 px-2.5 py-1 text-xs font-semibold text-green-400 ring-1 ring-green-700">
                                                Collected
                                            </span>

                                        @else

                                            <span class="inline-flex rounded-full bg-yellow-900/30 px-2.5 py-1 text-xs font-semibold text-yellow-400 ring-1 ring-yellow-700">
                                                Pending
                                            </span>

                                        @endif

                                    </td>


                                    <td class="px-6 py-4 text-right">

                                        @if($test->status === 'In Progress')

                                            <span class="inline-flex rounded-full bg-blue-900/30 px-2.5 py-1 text-xs font-semibold text-blue-400 ring-1 ring-blue-700">
                                                In Progress
                                            </span>

                                        @else

                                            <span class="inline-flex rounded-full bg-yellow-900/30 px-2.5 py-1 text-xs font-semibold text-yellow-400 ring-1 ring-yellow-700">
                                                Pending
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="4"
                                        class="px-6 py-10 text-center text-sm text-slate-500"
                                    >
                                        No active tests at the moment.
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