
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                    Insurance Payments
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Track insurance claims, settlements, and outstanding balances.
                </p>
            </div>

            <a href="{{ route('insurance-claims.index') }}"
               class="inline-flex items-center justify-center rounded-lg bg-slate-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-600">
                All Insurance Claims
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-900 py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="rounded-lg border border-emerald-700 bg-emerald-900/40 px-4 py-3 text-sm text-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Summary cards --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5 shadow-lg">
                    <p class="text-sm text-slate-400">Total Claims</p>
                    <p class="mt-2 text-2xl font-bold text-white">
                        ₦{{ number_format((float) $totalClaimed, 2) }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">Total insurance coverage claimed</p>
                </div>

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5 shadow-lg">
                    <p class="text-sm text-slate-400">Amount Received</p>
                    <p class="mt-2 text-2xl font-bold text-emerald-400">
                        ₦{{ number_format((float) $totalReceived, 2) }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">Recorded insurer payments</p>
                </div>

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5 shadow-lg">
                    <p class="text-sm text-slate-400">Estimated Outstanding</p>
                    <p class="mt-2 text-2xl font-bold text-amber-400">
                        ₦{{ number_format((float) $totalOutstanding, 2) }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">Claims less recorded receipts</p>
                </div>

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5 shadow-lg">
                    <p class="text-sm text-slate-400">Unsettled Claims</p>
                    <p class="mt-2 text-2xl font-bold text-indigo-400">
                        {{ number_format($pendingClaims) }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">Pending, submitted, or partly paid</p>
                </div>

            </div>

            {{-- Claims table --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">
                <div class="border-b border-slate-700 px-6 py-5">
                    <h3 class="text-lg font-semibold text-white">
                        Insurance Claims and Settlements
                    </h3>
                    <p class="mt-1 text-sm text-slate-400">
                        Amounts received reflect settlements recorded in the system, not merely claims submitted.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700">
                        <thead class="bg-slate-900/70">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Patient / Test
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Insurance Company
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Amount Claimed
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Received
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Outstanding
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-700">
                            @forelse ($claims as $claim)
                                @php
                                    $received = (float) $claim->payments->sum('amount');
                                    $claimed = (float) $claim->covered_amount;
                                    $outstanding = max(0, $claimed - $received);

                                    $statusClasses = match ($claim->status) {
                                        'Paid' => 'bg-emerald-900/50 text-emerald-300 ring-emerald-700',
                                        'Partially Paid' => 'bg-amber-900/50 text-amber-300 ring-amber-700',
                                        'Rejected' => 'bg-red-900/50 text-red-300 ring-red-700',
                                        'Submitted' => 'bg-blue-900/50 text-blue-300 ring-blue-700',
                                        default => 'bg-slate-700 text-slate-300 ring-slate-600',
                                    };
                                @endphp

                                <tr class="transition hover:bg-slate-700/40">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="font-medium text-white">
                                            {{ $claim->testRequest?->patient?->full_name ?? 'Patient unavailable' }}
                                        </div>
                                        <div class="mt-1 text-xs text-slate-400">
                                            Test Request #{{ $claim->test_request_id }}
                                        </div>
                                        @if ($claim->membership_number)
                                            <div class="mt-1 text-xs text-slate-500">
                                                Member: {{ $claim->membership_number }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-300">
                                        {{ $claim->insurance_company }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium text-white">
                                        ₦{{ number_format($claimed, 2) }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium text-emerald-400">
                                        ₦{{ number_format($received, 2) }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium {{ $outstanding > 0 ? 'text-amber-400' : 'text-slate-400' }}">
                                        ₦{{ number_format($outstanding, 2) }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusClasses }}">
                                            {{ $claim->status }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <a href="{{ route('insurance-claims.show', $claim) }}"
                                           class="inline-flex items-center rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-indigo-500">
                                            View Claim
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="text-slate-300">
                                            No insurance claims found.
                                        </div>
                                        <p class="mt-2 text-sm text-slate-500">
                                            Create an insurance claim from a test request to see it here.
                                        </p>
                                        <a href="{{ route('insurance-claims.index') }}"
                                           class="mt-4 inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                                            View Insurance Claims
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($claims->hasPages())
                    <div class="border-t border-slate-700 px-6 py-4">
                        {{ $claims->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
