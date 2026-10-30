
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-white leading-tight">
                Insurance Claims
            </h2>
            <p class="mt-1 text-sm text-slate-400">
                Track insurance claims, payments, and outstanding balances.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-900 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-700 bg-green-950/50 p-4 text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Main Card --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">
                <div class="p-6">

                    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-white">
                                All Insurance Claims
                            </h3>
                            <p class="mt-1 text-sm text-slate-400">
                                View the status of claims submitted to insurance companies and HMOs.
                            </p>
                        </div>

                        <a href="{{ route('test-requests.index') }}"
                           class="inline-flex items-center justify-center rounded-lg border border-slate-600 bg-slate-700 px-4 py-2.5 text-sm font-medium text-slate-200 transition hover:bg-slate-600">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Test Requests
                        </a>
                    </div>

                    @if ($claims->isEmpty())
                        <div class="rounded-lg border border-dashed border-slate-600 bg-slate-900/50 px-6 py-12 text-center">
                            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-slate-700">
                                <svg class="h-6 w-6 text-slate-300" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="1.8"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/>
                                </svg>
                            </div>

                            <h4 class="text-base font-semibold text-white">
                                No insurance claims yet
                            </h4>
                            <p class="mt-2 text-sm text-slate-400">
                                Insurance claims you register will appear here.
                            </p>

                            <a href="{{ route('test-requests.index') }}"
                               class="mt-5 inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                View Test Requests
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-lg border border-slate-700">
                            <table class="min-w-full divide-y divide-slate-700">
                                <thead class="bg-slate-900">
                                    <tr>
                                        <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Patient
                                        </th>
                                        <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Tracking Code
                                        </th>
                                        <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Insurance Company
                                        </th>
                                        <th class="whitespace-nowrap px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Covered Amount
                                        </th>
                                        <th class="whitespace-nowrap px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Settled
                                        </th>
                                        <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Status
                                        </th>
                                        <th class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                            Action
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-700 bg-slate-800">
                                    @foreach ($claims as $claim)
                                        @php
                                            $statusClasses = [
                                                'Pending' => 'border-yellow-700 bg-yellow-950/50 text-yellow-300',
                                                'Submitted' => 'border-blue-700 bg-blue-950/50 text-blue-300',
                                                'Partially Paid' => 'border-purple-700 bg-purple-950/50 text-purple-300',
                                                'Paid' => 'border-green-700 bg-green-950/50 text-green-300',
                                                'Rejected' => 'border-red-700 bg-red-950/50 text-red-300',
                                            ];
                                        @endphp

                                        <tr class="transition hover:bg-slate-700/40">
                                            <td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-white">
                                                {{ $claim->testRequest->patient->full_name ?? 'N/A' }}
                                            </td>

                                            <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-300">
                                                {{ $claim->testRequest->tracking_code ?? 'N/A' }}
                                            </td>

                                            <td class="px-4 py-4 text-sm text-slate-300">
                                                {{ $claim->insurance_company }}
                                            </td>

                                            <td class="whitespace-nowrap px-4 py-4 text-right text-sm text-slate-300">
                                                ₦{{ number_format((float) $claim->covered_amount, 2) }}
                                            </td>

                                            <td class="whitespace-nowrap px-4 py-4 text-right text-sm text-slate-300">
                                                ₦{{ number_format((float) $claim->totalSettled(), 2) }}
                                            </td>

                                            <td class="whitespace-nowrap px-4 py-4 text-sm">
                                                <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$claim->status] ?? 'border-slate-600 bg-slate-700 text-slate-300' }}">
                                                    {{ $claim->status }}
                                                </span>
                                            </td>

                                            <td class="whitespace-nowrap px-4 py-4 text-sm">
                                                <a href="{{ route('insurance-claims.show', $claim) }}"
                                                   class="inline-flex items-center rounded-lg border border-indigo-500/40 bg-indigo-500/10 px-3 py-1.5 font-medium text-indigo-300 transition hover:bg-indigo-500/20 hover:text-indigo-200">
                                                    View Details
                                                    <svg class="ml-1.5 h-4 w-4" fill="none"
                                                         stroke="currentColor" viewBox="0 0 24 24"
                                                         aria-hidden="true">
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $claims->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
