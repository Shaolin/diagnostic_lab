<x-app-layout>
    <div class="min-h-screen bg-slate-900 py-6">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-white">
                        Accounts Receivable
                    </h1>
                    <p class="mt-1 text-sm text-slate-400">
                        Receivable details for {{ $testRequest->tracking_code }}
                    </p>
                </div>
<div class="flex items-center gap-2">

    <a href="{{ route('payments.create', $testRequest) }}"
       class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
        Record Payment
    </a>

    <a href="{{ route('accounting.accounts-receivable') }}"
       class="rounded-lg border border-slate-600 px-4 py-2 text-sm text-slate-300 hover:bg-slate-700">
        Back
    </a>

</div>
            </div>

            {{-- Patient & Request --}}
            <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-2">

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">
                    <h2 class="mb-4 text-lg font-semibold text-white">
                        Patient
                    </h2>

                    <p class="text-sm text-slate-400">Name</p>
                    <p class="text-white">
                        {{ $testRequest->patient->full_name ?? 'N/A' }}
                    </p>

                    <p class="mt-3 text-sm text-slate-400">Patient Number</p>
                    <p class="text-white">
                        {{ $testRequest->patient->patient_number ?? 'N/A' }}
                    </p>
                </div>

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">
                    <h2 class="mb-4 text-lg font-semibold text-white">
                        Test Request
                    </h2>

                    <p class="text-sm text-slate-400">Tracking Code</p>
                    <p class="text-white">
                        {{ $testRequest->tracking_code }}
                    </p>

                    <p class="mt-3 text-sm text-slate-400">Branch</p>
                    <p class="text-white">
                        {{ $testRequest->branch->name ?? 'Head Office' }}
                    </p>
                </div>

            </div>
             {{-- Tests / Services --}}
<div class="mb-6 overflow-hidden rounded-xl border border-slate-700 bg-slate-800">

    <div class="border-b border-slate-700 px-5 py-4">
        <h2 class="text-lg font-semibold text-white">
            Tests / Services
        </h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">

            <thead class="bg-slate-900 text-left text-slate-400">
                <tr>
                    <th class="px-4 py-3">Test</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Amount</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-700">

                @foreach($testRequest->items as $item)
                    <tr>
                        <td class="px-4 py-3 text-white">
                            {{ $item->test_name }}
                        </td>

                        <td class="px-4 py-3 text-slate-300">
                            {{ $item->status }}
                        </td>

                        <td class="px-4 py-3 text-right text-white">
                            ₦{{ number_format($item->price, 2) }}
                        </td>
                    </tr>
                @endforeach

            </tbody>

        </table>
    </div>

</div>

            {{-- Financial Summary --}}
            <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">
                    <p class="text-sm text-slate-400">Amount Billed</p>
                    <p class="mt-2 text-2xl font-bold text-white">
                        ₦{{ number_format($testRequest->total_amount, 2) }}
                    </p>
                </div>

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">
                    <p class="text-sm text-slate-400">Total Paid</p>
                    <p class="mt-2 text-2xl font-bold text-white">
                        ₦{{ number_format($testRequest->totalPaid(), 2) }}
                    </p>
                </div>

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">
                    <p class="text-sm text-slate-400">Outstanding</p>
                    <p class="mt-2 text-2xl font-bold text-red-400">
                        ₦{{ number_format($testRequest->balance(), 2) }}
                    </p>
                </div>

            </div>

            {{-- Payment History --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800">

                <div class="border-b border-slate-700 px-5 py-4">
                    <h2 class="text-lg font-semibold text-white">
                        Payment History
                    </h2>
                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full text-sm">

                        <thead class="bg-slate-900 text-left text-slate-400">
                            <tr>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Method</th>
                                <th class="px-4 py-3">Reference</th>
                                <th class="px-4 py-3">Received By</th>
                                <th class="px-4 py-3 text-right">Amount</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-700">

                            @forelse($testRequest->payments as $payment)

                                <tr>
                                    <td class="px-4 py-3 text-slate-300">
                                        {{ $payment->paid_at?->format('d M Y, h:i A') }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-300">
                                        {{ $payment->payment_method }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-300">
                                        {{ $payment->payment_reference ?? '—' }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-300">
                                        {{ $payment->receivedBy->name ?? 'N/A' }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-medium text-white">
                                        ₦{{ number_format($payment->amount, 2) }}
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-8 text-center text-slate-400">
                                        No payments recorded.
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