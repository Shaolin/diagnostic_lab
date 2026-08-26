<x-app-layout>

    <x-slot name="header">

       <div class="border-b border-slate-700 px-6 py-5">

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>

            <h3 class="text-lg font-semibold text-white">
                Payment History
            </h3>

            <p class="mt-1 text-sm text-slate-500">
                Recent payments recorded by laboratory staff.
            </p>

        </div>


        <a
            href="{{ route('test-requests.index') }}"
            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
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

            Record Payment

        </a>

    </div>

</div>

    </x-slot>


    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-7xl px-4 text-white sm:px-6 lg:px-8">


            {{-- Flash Success Message --}}
            @if(session('success'))

                <div class="mb-6 rounded-lg border border-green-700 bg-green-900/30 px-4 py-3 text-green-300 shadow-sm">

                    <div class="flex items-center">

                        <svg
                            class="mr-2 h-5 w-5 text-green-600"
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

                        <span>
                            {{ session('success') }}
                        </span>

                    </div>

                </div>

            @endif


            {{-- Flash Error Message --}}
            @if(session('error'))

                <div class="mb-6 rounded-lg border border-red-700 bg-red-900/30 px-4 py-3 text-red-300 shadow-sm">

                    <div class="flex items-center">

                        <svg
                            class="mr-2 h-5 w-5 text-red-600"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"
                            />
                        </svg>

                        <span>
                            {{ session('error') }}
                        </span>

                    </div>

                </div>

            @endif


            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="mb-6 rounded-lg border border-red-700 bg-red-900/30 px-4 py-3 text-red-300 shadow-sm">

                    <div class="flex items-start">

                        <svg
                            class="mr-2 mt-0.5 h-5 w-5 text-red-600"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"
                            />
                        </svg>

                        <div>

                            <p class="font-semibold">
                                Please fix the following errors:
                            </p>

                            <ul class="mt-2 list-disc pl-5 text-sm">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif


            {{-- Payment History Card --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                {{-- Card Header --}}
                <div class="border-b border-slate-700 px-6 py-5">

                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

                        <div>

                            <h3 class="text-lg font-semibold text-white">
                                Payment History
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Recent payments recorded by laboratory staff.
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Table --}}
                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-900">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Date
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Tracking Code
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Patient
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Amount
                                </th>

                                <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Method
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Reference
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Received By
                                </th>

                                <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-700">

                            @forelse($payments as $payment)

                                <tr class="transition hover:bg-slate-700/40">


                                    {{-- Date --}}
                                    <td class="whitespace-nowrap px-6 py-4">

                                        <div class="text-sm font-medium text-white">
                                            {{ $payment->paid_at?->format('d M Y') }}
                                        </div>

                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ $payment->paid_at?->format('h:i A') }}
                                        </div>

                                    </td>


                                    {{-- Tracking Code --}}
                                    <td class="whitespace-nowrap px-6 py-4">

                                        <span class="font-mono font-semibold text-indigo-400">
                                            {{ $payment->testRequest->tracking_code }}
                                        </span>

                                    </td>


                                    {{-- Patient --}}
                                    <td class="px-6 py-4">

                                        <div class="font-semibold text-white">
                                            {{ $payment->testRequest->patient->full_name }}
                                        </div>

                                        <div class="mt-1 text-xs text-slate-400">
                                            {{ $payment->testRequest->patient->patient_number }}
                                        </div>

                                        <div class="text-xs text-slate-500">
                                            {{ $payment->testRequest->patient->phone }}
                                        </div>

                                    </td>


                                    {{-- Amount --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-right">

                                        <span class="font-semibold text-emerald-400">
                                            ₦{{ number_format((float) $payment->amount, 2) }}
                                        </span>

                                    </td>


                                    {{-- Payment Method --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-center">

                                        @switch($payment->payment_method)

                                            @case('Cash')

                                                <span class="inline-flex rounded-full bg-green-900/30 px-3 py-1 text-xs font-semibold text-green-400 ring-1 ring-green-700">
                                                    Cash
                                                </span>

                                                @break

                                            @case('Transfer')

                                                <span class="inline-flex rounded-full bg-blue-900/30 px-3 py-1 text-xs font-semibold text-blue-400 ring-1 ring-blue-700">
                                                    Transfer
                                                </span>

                                                @break

                                            @case('POS')

                                                <span class="inline-flex rounded-full bg-purple-900/30 px-3 py-1 text-xs font-semibold text-purple-400 ring-1 ring-purple-700">
                                                    POS
                                                </span>

                                                @break

                                            @default

                                                <span class="inline-flex rounded-full bg-slate-700 px-3 py-1 text-xs font-semibold text-slate-300">
                                                    {{ $payment->payment_method }}
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Reference --}}
                                    <td class="px-6 py-4">

                                        @if($payment->payment_reference)

                                            <span class="font-mono text-sm text-slate-300">
                                                {{ $payment->payment_reference }}
                                            </span>

                                        @else

                                            <span class="text-sm text-slate-500">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Received By --}}
                                    <td class="whitespace-nowrap px-6 py-4">

                                        <span class="text-sm text-slate-300">
                                            {{ $payment->receivedBy?->name ?? '—' }}
                                        </span>

                                    </td>


                                    {{-- Actions --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-center">

                                        <div class="flex items-center justify-center gap-2">

                                            <a
                                                href="{{ route('payments.show', $payment) }}"
                                                class="rounded-lg bg-sky-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-sky-700"
                                            >
                                                View
                                            </a>

                                        </div>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td
                                        colspan="8"
                                        class="px-6 py-16 text-center"
                                    >

                                        <div class="mx-auto max-w-md">

                                            <svg
                                                class="mx-auto h-14 w-14 text-slate-600"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.5"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M12 6v12m-6-6h12"
                                                />
                                            </svg>


                                            <h3 class="mt-4 text-lg font-semibold text-white">
                                                No Payments Found
                                            </h3>


                                            <p class="mt-2 text-sm text-slate-500">
                                                Payments recorded for laboratory test requests will appear here.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- Pagination --}}
            @if ($payments->hasPages())

                <div class="mt-6">

                    {{ $payments->links() }}

                </div>

            @endif


        </div>

    </div>

</x-app-layout>