<x-app-layout>
    <div class="min-h-screen bg-slate-900 py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-white">
                    Accounts Receivable
                </h1>
                <p class="mt-1 text-sm text-slate-400">
                    Track outstanding amounts owed by patients.
                </p>
            </div>

            {{-- Summary Cards --}}
            <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">
                    <p class="text-sm text-slate-400">Total Receivable</p>
                    <p class="mt-2 text-2xl font-bold text-white">
                        ₦{{ number_format($totalReceivable, 2) }}
                    </p>
                </div>

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">
                    <p class="text-sm text-slate-400">Total Paid</p>
                    <p class="mt-2 text-2xl font-bold text-white">
                        ₦{{ number_format($totalPaid, 2) }}
                    </p>
                </div>

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">
                    <p class="text-sm text-slate-400">Outstanding</p>
                    <p class="mt-2 text-2xl font-bold text-red-400">
                        ₦{{ number_format($totalOutstanding, 2) }}
                    </p>
                </div>
                

            </div>

          {{-- Filters --}}
<div class="mb-6 rounded-xl border border-slate-700 bg-slate-800 p-5">

    <form method="GET"
          action="{{ route('accounting.accounts-receivable') }}"
          class="grid grid-cols-1 gap-4 md:grid-cols-4">

        {{-- Patient --}}
        <div>
            <label class="mb-1 block text-sm text-slate-300">
                Patient
            </label>

            <input type="text"
                   name="patient"
                   value="{{ request('patient') }}"
                   placeholder="Name or patient number"
                   class="w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-white placeholder-slate-500">
        </div>

        {{-- Branch --}}
        <div>
            <label class="mb-1 block text-sm text-slate-300">
                Branch
            </label>

            <select name="branch_id"
                    class="w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-white">
                <option value="">All Branches</option>

                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}"
                        @selected(request('branch_id') == $branch->id)>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- From Date --}}
        <div>
            <label class="mb-1 block text-sm text-slate-300">
                From Date
            </label>

            <input type="date"
                   name="from"
                   value="{{ request('from') }}"
                   class="w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-white">
        </div>

        {{-- To Date --}}
        <div>
            <label class="mb-1 block text-sm text-slate-300">
                To Date
            </label>

            <input type="date"
                   name="to"
                   value="{{ request('to') }}"
                   class="w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-white">
        </div>

        {{-- Buttons --}}
        <div class="flex items-end gap-2 md:col-span-4">

            <button type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Filter
            </button>

            <a href="{{ route('accounting.accounts-receivable') }}"
               class="rounded-lg border border-slate-600 px-5 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700">
                Reset
            </a>

        </div>

    </form>

</div>

            {{-- Receivables Table --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800">

                <div class="overflow-x-auto">

                    <table class="min-w-full text-sm">

                        <thead class="bg-slate-900 text-left text-slate-400">
                            <tr>
                                <th class="px-4 py-3">Test Request</th>
                                <th class="px-4 py-3">Patient</th>
                                <th class="px-4 py-3">Branch</th>
                                <th class="px-4 py-3 text-right">Amount Billed</th>
                                <th class="px-4 py-3 text-right">Paid</th>
                                <th class="px-4 py-3 text-right">Outstanding</th>
                                <th class="px-4 py-3">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-700">

                            @forelse($testRequests as $testRequest)

                                <tr class="hover:bg-slate-700/40">

                                    <td class="px-4 py-3 font-medium text-white">
                                        {{ $testRequest->tracking_code }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-300">
                                        {{ $testRequest->patient->full_name ?? 'N/A' }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-300">
                                        {{ $testRequest->branch->name ?? 'Head Office' }}
                                    </td>

                                    <td class="px-4 py-3 text-right text-slate-300">
                                        ₦{{ number_format($testRequest->total_amount, 2) }}
                                    </td>

                                    <td class="px-4 py-3 text-right text-slate-300">
                                        ₦{{ number_format($testRequest->totalPaid(), 2) }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-semibold text-red-400">
                                        ₦{{ number_format($testRequest->balance(), 2) }}
                                    </td>
                                    <td class="px-4 py-3">
    <a href="{{ route('accounting.accounts-receivable.show', $testRequest) }}"
       class="text-indigo-400 hover:text-indigo-300">
        View
    </a>
</td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7"
                                        class="px-4 py-8 text-center text-slate-400">
                                        No outstanding receivables found.
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