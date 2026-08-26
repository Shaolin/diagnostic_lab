<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>

                <h2 class="text-xl font-semibold text-white">
                    Payment Details
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    View details of this payment transaction.
                </p>

            </div>


            <a
                href="{{ route('payments.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-slate-600 px-4 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700 hover:text-white"
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
                        d="M15 19l-7-7 7-7"
                    />
                </svg>

                Back to Payments

            </a>

        </div>

    </x-slot>


    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-5xl px-4 text-white sm:px-6 lg:px-8">


            {{-- Payment Status --}}
            <div class="mb-6 rounded-xl border border-green-700 bg-green-900/20 px-6 py-4">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-900/40">

                        <svg
                            class="h-5 w-5 text-green-400"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                    </div>

                    <div>

                        <p class="font-semibold text-green-400">
                            Payment Recorded
                        </p>

                        <p class="text-sm text-slate-400">
                            This payment has been successfully recorded.
                        </p>

                    </div>

                </div>

            </div>


            {{-- Payment Information --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                <div class="border-b border-slate-700 px-6 py-5">

                    <h3 class="text-lg font-semibold text-white">
                        Payment Information
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Transaction details and payment information.
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-6 px-6 py-6 md:grid-cols-2">


                    {{-- Amount --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Amount Paid
                        </p>

                        <p class="mt-2 text-3xl font-bold text-emerald-400">
                            ₦{{ number_format((float) $payment->amount, 2) }}
                        </p>

                    </div>


                    {{-- Payment Method --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Payment Method
                        </p>

                        <div class="mt-2">

                            @switch($payment->payment_method)

                                @case('Cash')

                                    <span class="inline-flex rounded-full bg-green-900/30 px-3 py-1 text-sm font-semibold text-green-400 ring-1 ring-green-700">
                                        Cash
                                    </span>

                                    @break

                                @case('Transfer')

                                    <span class="inline-flex rounded-full bg-blue-900/30 px-3 py-1 text-sm font-semibold text-blue-400 ring-1 ring-blue-700">
                                        Transfer
                                    </span>

                                    @break

                                @case('POS')

                                    <span class="inline-flex rounded-full bg-purple-900/30 px-3 py-1 text-sm font-semibold text-purple-400 ring-1 ring-purple-700">
                                        POS
                                    </span>

                                    @break

                                @default

                                    <span class="inline-flex rounded-full bg-slate-700 px-3 py-1 text-sm font-semibold text-slate-300">
                                        {{ $payment->payment_method }}
                                    </span>

                            @endswitch

                        </div>

                    </div>


                    {{-- Payment Date --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Payment Date
                        </p>

                        <p class="mt-2 text-sm font-medium text-white">
                            {{ $payment->paid_at?->format('d M Y') ?? '—' }}
                        </p>

                        @if($payment->paid_at)

                            <p class="mt-1 text-xs text-slate-500">
                                {{ $payment->paid_at->format('h:i A') }}
                            </p>

                        @endif

                    </div>


                    {{-- Reference --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Payment Reference
                        </p>

                        <p class="mt-2 font-mono text-sm text-slate-300">
                            {{ $payment->payment_reference ?: '—' }}
                        </p>

                    </div>


                    {{-- Received By --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Received By
                        </p>

                        <p class="mt-2 text-sm font-medium text-white">
                            {{ $payment->receivedBy?->name ?? '—' }}
                        </p>

                    </div>


                    {{-- Created At --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Recorded At
                        </p>

                        <p class="mt-2 text-sm font-medium text-white">
                            {{ $payment->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </p>

                    </div>


                </div>


                {{-- Remarks --}}
                @if($payment->remarks)

                    <div class="border-t border-slate-700 px-6 py-5">

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Remarks
                        </p>

                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-300">
                            {{ $payment->remarks }}
                        </p>

                    </div>

                @endif

            </div>


            {{-- Test Request Information --}}
            <div class="mt-6 overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                <div class="border-b border-slate-700 px-6 py-5">

                    <h3 class="text-lg font-semibold text-white">
                        Test Request
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        The laboratory request associated with this payment.
                    </p>

                </div>


                <div class="grid grid-cols-1 gap-6 px-6 py-6 md:grid-cols-2">


                    {{-- Tracking Code --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Tracking Code
                        </p>

                        <p class="mt-2 font-mono font-semibold text-indigo-400">
                            {{ $payment->testRequest->tracking_code }}
                        </p>

                    </div>


                    {{-- Patient --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Patient
                        </p>

                        <p class="mt-2 font-semibold text-white">
                            {{ $payment->testRequest->patient->full_name }}
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            {{ $payment->testRequest->patient->patient_number }}
                        </p>

                    </div>


                    {{-- Total Amount --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Total Request Amount
                        </p>

                        <p class="mt-2 text-lg font-semibold text-white">
                            ₦{{ number_format((float) $payment->testRequest->total_amount, 2) }}
                        </p>

                    </div>


                    {{-- Total Paid --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Total Paid
                        </p>

                        <p class="mt-2 text-lg font-semibold text-emerald-400">
                            ₦{{ number_format($payment->testRequest->totalPaid(), 2) }}
                        </p>

                    </div>


                    {{-- Balance --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Outstanding Balance
                        </p>

                        <p class="mt-2 text-lg font-semibold text-yellow-400">
                            ₦{{ number_format($payment->testRequest->balance(), 2) }}
                        </p>

                    </div>


                    {{-- Payment Status --}}
                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Payment Status
                        </p>

                        <div class="mt-2">

                            @if($payment->testRequest->isPaid())

                                <span class="inline-flex rounded-full bg-green-900/30 px-3 py-1 text-sm font-semibold text-green-400 ring-1 ring-green-700">
                                    Paid
                                </span>

                            @elseif($payment->testRequest->totalPaid() > 0)

                                <span class="inline-flex rounded-full bg-yellow-900/30 px-3 py-1 text-sm font-semibold text-yellow-400 ring-1 ring-yellow-700">
                                    Partially Paid
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-red-900/30 px-3 py-1 text-sm font-semibold text-red-400 ring-1 ring-red-700">
                                    Unpaid
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- View Test Request --}}
                <div class="border-t border-slate-700 px-6 py-5">

                    <a
                        href="{{ route('test-requests.show', $payment->testRequest) }}"
                        class="inline-flex items-center rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700"
                    >

                        View Test Request

                        <svg
                            class="ml-2 h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>

                    </a>

                </div>

            </div>


            {{-- Bottom Actions --}}
            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-between">

                <a
                    href="{{ route('payments.index') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-600 px-5 py-3 text-sm font-semibold text-slate-300 transition hover:bg-slate-700 hover:text-white"
                >
                    Back to Payments
                </a>


                @if(!$payment->testRequest->isPaid())

                    <a
                        href="{{ route('payments.create', $payment->testRequest) }}"
                        class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
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
                                d="M12 4v16m8-8H4"
                            />
                        </svg>

                        Record Another Payment

                    </a>

                @endif

            </div>


        </div>

    </div>

</x-app-layout>