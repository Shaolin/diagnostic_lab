<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>

                <h2 class="text-xl font-semibold text-white">
                    Test Requests Management
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Manage laboratory test requests and monitor their progress.
                </p>

            </div>

            <a
                href="{{ route('test-requests.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
            >
                + New Test Request
            </a>

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


            {{-- Search & Filters --}}
            <div class="mb-6 rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                <form
                    method="GET"
                    action="{{ route('test-requests.index') }}"
                >

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                        {{-- Tracking Code --}}
                        <div>

                            <label
                                for="tracking_code"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Tracking Code
                            </label>

                            <input
                                type="text"
                                name="tracking_code"
                                id="tracking_code"
                                value="{{ request('tracking_code') }}"
                                placeholder="TRK-202600001"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>


                        {{-- Patient Name --}}
                        <div>

                            <label
                                for="patient"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Patient Name
                            </label>

                            <input
                                type="text"
                                name="patient"
                                id="patient"
                                value="{{ request('patient') }}"
                                placeholder="Search patient..."
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>


                        {{-- Status --}}
                        <div>

                            <label
                                for="status"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Status
                            </label>

                            <select
                                name="status"
                                id="status"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white focus:border-indigo-500 focus:ring-indigo-500"
                            >

                                <option value="">
                                    All Statuses
                                </option>

                                <option
                                    value="Pending"
                                    @selected(request('status') == 'Pending')
                                >
                                    Pending
                                </option>

                                <option
                                    value="In Progress"
                                    @selected(request('status') == 'In Progress')
                                >
                                    In Progress
                                </option>

                                <option
                                    value="Partially Completed"
                                    @selected(request('status') == 'Partially Completed')
                                >
                                    Partially Completed
                                </option>

                                <option
                                    value="Completed"
                                    @selected(request('status') == 'Completed')
                                >
                                    Completed
                                </option>

                            </select>

                        </div>


                        {{-- Date Requested --}}
                        <div>

                            <label
                                for="date"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Date Requested
                            </label>

                            <input
                                type="date"
                                name="date"
                                id="date"
                                value="{{ request('date') }}"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>

                    </div>


                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                        >
                            Search
                        </button>

                        <a
                            href="{{ route('test-requests.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-600 bg-slate-900 px-5 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700"
                        >
                            Reset
                        </a>

                    </div>

                </form>

            </div>

            {{-- Table Starts Here --}}

            {{-- Table --}}
<div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="overflow-x-auto">

        <table class="min-w-full divide-y divide-slate-700">

            {{-- Table Header --}}
            <thead class="bg-slate-900">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Tracking Code
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Patient
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Tests
                    </th>

                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Total Amount
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Status
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Date Requested
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Actions
                    </th>

                </tr>

            </thead>


            {{-- Table Body --}}
            <tbody class="divide-y divide-slate-700">

                @forelse($testRequests as $testRequest)

                    <tr class="transition hover:bg-slate-700/40">

                        {{-- Tracking Code --}}
                        <td class="whitespace-nowrap px-6 py-4">

                            <span class="font-mono font-semibold text-indigo-400">

                                {{ $testRequest->tracking_code }}

                            </span>

                        </td>


                        {{-- Patient --}}
                        <td class="px-6 py-4">

                            <div class="font-semibold text-white">

                                {{ $testRequest->patient->full_name }}

                            </div>

                            <div class="mt-1 text-xs text-slate-400">

                                {{ $testRequest->patient->patient_number }}

                            </div>

                            <div class="text-xs text-slate-500">

                                {{ $testRequest->patient->phone }}

                            </div>

                        </td>


                        {{-- Number of Tests --}}
                        <td class="whitespace-nowrap px-6 py-4 text-center">

                            <span class="inline-flex rounded-full bg-indigo-900/30 px-3 py-1 text-xs font-semibold text-indigo-300 ring-1 ring-indigo-700">

                                {{ $testRequest->items_count }}
                                {{ Str::plural('Test', $testRequest->items_count) }}

                            </span>

                        </td>


                        {{-- Total Amount --}}
                        <td class="whitespace-nowrap px-6 py-4 text-right font-semibold text-emerald-400">

                            ₦{{ number_format($testRequest->total_amount, 2) }}

                        </td>


                        {{-- Overall Status --}}
                        <td class="whitespace-nowrap px-6 py-4 text-center">

                            @switch($testRequest->overall_status)

                                @case('Pending')

                                    <span class="inline-flex rounded-full bg-yellow-900/30 px-3 py-1 text-xs font-semibold text-yellow-400 ring-1 ring-yellow-700">

                                        Pending

                                    </span>

                                    @break


                                @case('In Progress')

                                    <span class="inline-flex rounded-full bg-blue-900/30 px-3 py-1 text-xs font-semibold text-blue-400 ring-1 ring-blue-700">

                                        In Progress

                                    </span>

                                    @break


                                @case('Partially Completed')

                                    <span class="inline-flex rounded-full bg-orange-900/30 px-3 py-1 text-xs font-semibold text-orange-400 ring-1 ring-orange-700">

                                        Partially Completed

                                    </span>

                                    @break


                                @case('Completed')

                                    <span class="inline-flex rounded-full bg-green-900/30 px-3 py-1 text-xs font-semibold text-green-400 ring-1 ring-green-700">

                                        Completed

                                    </span>

                                    @break


                                @default

                                    <span class="inline-flex rounded-full bg-slate-700 px-3 py-1 text-xs font-semibold text-slate-300">

                                        {{ $testRequest->overall_status }}

                                    </span>

                            @endswitch

                        </td>


                        {{-- Date Requested --}}
                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-slate-400">

                            {{ $testRequest->created_at->format('d M Y') }}

                        </td>


                        {{-- Actions --}}
                        <td class="whitespace-nowrap px-6 py-4 text-center">

                            <div class="flex items-center justify-center gap-2">

                                {{-- View --}}
                                <a
                                    href="{{ route('test-requests.show', $testRequest) }}"
                                    class="rounded-lg bg-sky-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-sky-700"
                                >
                                    View
                                </a>

                                {{-- Edit --}}
                                <a
                                    href="{{ route('test-requests.edit', $testRequest) }}"
                                    class="rounded-lg bg-amber-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-amber-600"
                                >
                                    Edit
                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
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
                                        d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"
                                    />
                                </svg>

                                <h3 class="mt-4 text-lg font-semibold text-white">

                                    No Test Requests Found

                                </h3>

                                <p class="mt-2 text-sm text-slate-500">

                                    No laboratory test requests match your current filters.

                                </p>

                                <a
                                    href="{{ route('test-requests.create') }}"
                                    class="mt-6 inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                                >
                                    Create Your First Test Request
                                </a>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
{{-- Pagination --}}
 @if ($testRequests->hasPages())
  <div class="mt-6">

  
        {{ $testRequests->links() }}

    </div>

@endif 



        </div>

    </div>

</x-app-layout>