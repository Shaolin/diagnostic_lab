
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Insurance Claim Details
            </h2>
            <p class="mt-1 text-sm text-slate-400">
                Review claim information, outstanding balances, and insurer settlements.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-900 py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-700 bg-green-950/50 p-4 text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">
                <div class="p-6">

                    {{-- Header and Actions --}}
                    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-white">
                                {{ $insuranceClaim->insurance_company }}
                            </h3>
                            <p class="mt-1 text-sm text-slate-400">
                                Tracking Code:
                                <span class="font-medium text-slate-300">
                                    {{ $insuranceClaim->testRequest->tracking_code ?? 'N/A' }}
                                </span>
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            @if (!in_array($insuranceClaim->status, ['Paid', 'Rejected'], true))
                                <a href="{{ route('insurance-claims.payments.create', $insuranceClaim) }}"
                                   class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Record Settlement
                                </a>
                            @endif

                            <a href="{{ route('insurance-claims.index') }}"
                               class="inline-flex items-center justify-center rounded-lg border border-slate-600 bg-slate-700 px-4 py-2.5 text-sm font-medium text-slate-200 transition hover:bg-slate-600">
                                Back to Claims
                            </a>
                        </div>
                    </div>

                    {{-- Claim Status --}}
                    @php
                        $statusClasses = [
                            'Pending' => 'border-yellow-700 bg-yellow-950/50 text-yellow-300',
                            'Submitted' => 'border-blue-700 bg-blue-950/50 text-blue-300',
                            'Partially Paid' => 'border-purple-700 bg-purple-950/50 text-purple-300',
                            'Paid' => 'border-green-700 bg-green-950/50 text-green-300',
                            'Rejected' => 'border-red-700 bg-red-950/50 text-red-300',
                        ];
                    @endphp

                    <div class="mb-8 flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center rounded-full border px-3 py-1.5 text-sm font-semibold {{ $statusClasses[$insuranceClaim->status] ?? 'border-slate-600 bg-slate-700 text-slate-300' }}">
                            {{ $insuranceClaim->status }}
                        </span>

                        <span class="text-sm text-slate-400">
                            Registered
                            {{ $insuranceClaim->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                        </span>
                    </div>

                    {{-- Patient and Insurance Information --}}
                    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">

                        <div class="rounded-xl border border-slate-700 bg-slate-900/60 p-5">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-500/10">
                                    <svg class="h-5 w-5 text-indigo-400" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="1.8"
                                              d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m6-10a4 4 0 100-8 4 4 0 000 8zm10 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-white">
                                    Patient Information
                                </h4>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-slate-400">Patient</p>
                                    <p class="mt-1 font-medium text-white">
                                        {{ $insuranceClaim->testRequest->patient->full_name ?? 'N/A' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-slate-400">Tracking Code</p>
                                    <p class="mt-1 font-medium text-slate-200">
                                        {{ $insuranceClaim->testRequest->tracking_code ?? 'N/A' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-slate-400">Test Request Total</p>
                                    <p class="mt-1 font-medium text-white">
                                        ₦{{ number_format((float) $insuranceClaim->testRequest->total_amount, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-slate-700 bg-slate-900/60 p-5">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-500/10">
                                    <svg class="h-5 w-5 text-purple-400" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="1.8"
                                              d="M9 12l2 2 4-4m5.6-4.1A9 9 0 1112 3a9 9 0 018.6 2.9z"/>
                                    </svg>
                                </div>
                                <h4 class="font-semibold text-white">
                                    Insurance Information
                                </h4>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-slate-400">Company / HMO</p>
                                    <p class="mt-1 font-medium text-white">
                                        {{ $insuranceClaim->insurance_company }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-slate-400">Membership Number</p>
                                    <p class="mt-1 font-medium text-slate-200">
                                        {{ $insuranceClaim->membership_number ?: 'Not provided' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-slate-400">Registered On</p>
                                    <p class="mt-1 font-medium text-slate-200">
                                        {{ $insuranceClaim->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Financial Summary --}}
                    <div class="mb-4">
                        <h4 class="text-lg font-semibold text-white">
                            Claim Financial Summary
                        </h4>
                        <p class="mt-1 text-sm text-slate-400">
                            Amounts related to the claim and settlements received from the insurer.
                        </p>
                    </div>

                    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                        <div class="rounded-xl border border-slate-700 bg-slate-900/60 p-5">
                            <p class="text-sm text-slate-400">Insurance Coverage</p>
                            <p class="mt-2 text-xl font-bold text-white">
                                ₦{{ number_format((float) $insuranceClaim->covered_amount, 2) }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500">Expected from insurer</p>
                        </div>

                        <div class="rounded-xl border border-slate-700 bg-slate-900/60 p-5">
                            <p class="text-sm text-slate-400">Patient Contribution</p>
                            <p class="mt-2 text-xl font-bold text-white">
                                ₦{{ number_format((float) $insuranceClaim->patient_contribution, 2) }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500">Patient's share</p>
                        </div>

                        <div class="rounded-xl border border-green-800 bg-green-950/20 p-5">
                            <p class="text-sm text-slate-400">Amount Settled</p>
                            <p class="mt-2 text-xl font-bold text-green-400">
                                ₦{{ number_format((float) $insuranceClaim->totalSettled(), 2) }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500">Received from insurer</p>
                        </div>

                        <div class="rounded-xl border border-red-800 bg-red-950/20 p-5">
                            <p class="text-sm text-slate-400">Outstanding Claim</p>
                            <p class="mt-2 text-xl font-bold text-red-400">
                                ₦{{ number_format((float) $insuranceClaim->outstandingAmount(), 2) }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500">Still expected from insurer</p>
                        </div>

                    </div>

                    {{-- Remarks --}}
                    <div class="mb-8">
                        <h4 class="mb-3 text-lg font-semibold text-white">
                            Remarks
                        </h4>

                        <div class="rounded-xl border border-slate-700 bg-slate-900/60 p-5 text-sm leading-6 text-slate-300">
                            {{ $insuranceClaim->remarks ?: 'No remarks provided.' }}
                        </div>
                    </div>

                    {{-- Settlement History --}}
                    <div>
                        <div class="mb-4">
                            <h4 class="text-lg font-semibold text-white">
                                Insurance Settlement History
                            </h4>
                            <p class="mt-1 text-sm text-slate-400">
                                A record of payments received from the insurance company.
                            </p>
                        </div>

                        @if ($insuranceClaim->payments->isEmpty())
                            <div class="rounded-xl border border-dashed border-slate-600 bg-slate-900/50 p-6 text-center">
                                <p class="font-medium text-slate-300">
                                    No settlements recorded yet
                                </p>
                                <p class="mt-1 text-sm text-slate-400">
                                    Settlements you record for this claim will appear here.
                                </p>
                            </div>
                        @else
                            <div class="overflow-x-auto rounded-lg border border-slate-700">
                                <table class="min-w-full divide-y divide-slate-700">
                                    <thead class="bg-slate-900">
                                        <tr>
                                            <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                                Date Received
                                            </th>
                                            <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                                Reference
                                            </th>
                                            <th class="whitespace-nowrap px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                                Amount
                                            </th>
                                            <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                                Received By
                                            </th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                                Remarks
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-slate-700 bg-slate-800">
                                        @foreach ($insuranceClaim->payments as $payment)
                                            <tr class="transition hover:bg-slate-700/40">
                                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-300">
                                                    {{ $payment->paid_at?->format('d M Y, h:i A') ?? 'N/A' }}
                                                </td>

                                                <td class="px-4 py-4 text-sm text-slate-300">
                                                    {{ $payment->payment_reference ?: '—' }}
                                                </td>

                                                <td class="whitespace-nowrap px-4 py-4 text-right text-sm font-semibold text-green-400">
                                                    ₦{{ number_format((float) $payment->amount, 2) }}
                                                </td>

                                                <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-300">
                                                    {{ $payment->receivedBy->name ?? 'N/A' }}
                                                </td>

                                                <td class="px-4 py-4 text-sm text-slate-300">
                                                    {{ $payment->remarks ?: '—' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
