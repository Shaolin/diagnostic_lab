<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>

                <h2 class="text-xl font-semibold text-white">
                    Test Request Details
                </h2>

                <p class="mt-1 text-sm text-slate-500">

                    Tracking Code:
                    <span class="font-mono font-semibold text-indigo-400">
                        {{ $testRequest->tracking_code }}
                    </span>

                </p>

            </div>

            <div class="flex items-center gap-3">

                <a
                    href="{{ route('test-requests.index') }}"
                    class="inline-flex items-center rounded-lg border border-slate-600 bg-slate-800 px-5 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700"
                >
                    ← Back
                </a>

                <a
                    href="{{ route('test-requests.edit', $testRequest) }}"
                    class="inline-flex items-center rounded-lg bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600"
                >
                    Edit Request
                </a>

            </div>

        </div>

    </x-slot>



    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">



            {{-- Success Message --}}
            @if(session('success'))

                <div class="mb-6 rounded-lg border border-green-700 bg-green-900/30 px-4 py-3 text-green-300 shadow">

                    <div class="flex items-center">

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
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                        {{ session('success') }}

                    </div>

                </div>

            @endif



            {{-- Error Message --}}
            @if(session('error'))

                <div class="mb-6 rounded-lg border border-red-700 bg-red-900/30 px-4 py-3 text-red-300 shadow">

                    <div class="flex items-center">

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
                                d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"
                            />
                        </svg>

                        {{ session('error') }}

                    </div>

                </div>

            @endif



            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="mb-6 rounded-lg border border-red-700 bg-red-900/30 p-4 shadow">

                    <h4 class="mb-3 font-semibold text-red-300">

                        Please correct the following errors:

                    </h4>

                    <ul class="list-disc space-y-1 pl-5 text-sm text-red-200">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif



            {{-- Information Cards Start Here --}}
            <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">

    {{-- Patient Information --}}
    <div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

        <div class="border-b border-slate-700 px-6 py-4">

            <h3 class="text-lg font-semibold text-white">
                Patient Information
            </h3>

            <p class="mt-1 text-sm text-slate-400">
                Details of the patient associated with this test request.
            </p>

        </div>

        <div class="space-y-5 p-6">

            {{-- Patient Number --}}
            <div class="flex items-start justify-between">

                <span class="text-sm font-medium text-slate-400">
                    Patient Number
                </span>

                <span class="font-mono font-semibold text-indigo-400">
                    {{ $testRequest->patient->patient_number }}
                </span>

            </div>

            {{-- Full Name --}}
            <div class="flex items-start justify-between">

                <span class="text-sm font-medium text-slate-400">
                    Full Name
                </span>

                <span class="text-right font-semibold text-white">
                    {{ $testRequest->patient->full_name }}
                </span>

            </div>

            {{-- Phone --}}
            <div class="flex items-start justify-between">

                <span class="text-sm font-medium text-slate-400">
                    Phone Number
                </span>

                <span class="text-white">

                    {{ $testRequest->patient->phone }}

                </span>

            </div>

            {{-- Gender --}}
            <div class="flex items-start justify-between">

                <span class="text-sm font-medium text-slate-400">
                    Gender
                </span>

                <span class="text-white">

                    {{ $testRequest->patient->gender }}

                </span>

            </div>

            {{-- Date of Birth --}}
            <div class="flex items-start justify-between">

                <span class="text-sm font-medium text-slate-400">
                    Date of Birth
                </span>

                <span class="text-white">

                    {{ optional($testRequest->patient->date_of_birth)->format('d M Y') ?? '-' }}

                </span>

            </div>

        </div>

    </div>



    {{-- Request Information --}}
    <div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

        <div class="border-b border-slate-700 px-6 py-4">

            <h3 class="text-lg font-semibold text-white">
                Request Information
            </h3>

            <p class="mt-1 text-sm text-slate-400">
                General information about this laboratory request.
            </p>

        </div>

        <div class="space-y-5 p-6">

            {{-- Tracking Code --}}
            <div class="flex items-start justify-between">

                <span class="text-sm font-medium text-slate-400">
                    Tracking Code
                </span>

                <span class="font-mono font-semibold text-indigo-400">

                    {{ $testRequest->tracking_code }}

                </span>

            </div>

            {{-- Date Requested --}}
            <div class="flex items-start justify-between">

                <span class="text-sm font-medium text-slate-400">
                    Date Requested
                </span>

                <span class="text-white">

                    {{ $testRequest->created_at->format('d M Y h:i A') }}

                </span>

            </div>

            {{-- Requested By --}}
            <div class="flex items-start justify-between">

                <span class="text-sm font-medium text-slate-400">
                    Requested By
                </span>

                <span class="text-white">

                    {{ $testRequest->requestedBy->name }}

                </span>

            </div>

            {{-- Overall Status --}}
            <div class="flex items-start justify-between">

                <span class="text-sm font-medium text-slate-400">
                    Overall Status
                </span>

                <span>

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

                </span>

            </div>

            {{-- Total Amount --}}
            <div class="flex items-start justify-between border-t border-slate-700 pt-5">

                <span class="text-base font-semibold text-slate-300">
                    Total Amount
                </span>

                <span class="text-xl font-bold text-emerald-400">

                    ₦{{ number_format($testRequest->total_amount, 2) }}

                </span>

            </div>

            {{-- Remarks --}}
            @if($testRequest->remarks)

                <div class="border-t border-slate-700 pt-5">

                    <p class="mb-2 text-sm font-medium text-slate-400">
                        Remarks
                    </p>

                    <div class="rounded-lg bg-slate-900 p-4 text-sm text-slate-300">

                        {{ $testRequest->remarks }}

                    </div>

                </div>

            @endif

        </div>

    </div>

</div>

{{-- Requested Tests Table Starts Here --}}
{{-- Requested Tests --}}
<div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="border-b border-slate-700 px-6 py-4">

        <h3 class="text-lg font-semibold text-white">
            Requested Laboratory Tests
        </h3>

        <p class="mt-1 text-sm text-slate-400">
            Monitor the progress of each laboratory test in this request.
        </p>

    </div>


    <div class="overflow-x-auto">

        <table class="min-w-full divide-y divide-slate-700">

            <thead class="bg-slate-900">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Test
                    </th>

                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Price
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Sample
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Test
                    </th>


                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-700">

                @forelse($testRequest->items as $item)

                    <tr class="transition hover:bg-slate-700/40">

                        {{-- Test Name --}}
                        <td class="px-6 py-4">

                            <div class="font-semibold text-white">

                                {{ $item->testType->name }}

                            </div>

                            @if($item->completed_at)

                                <div class="mt-1 text-xs text-slate-500">

                                    Completed:
                                    {{ $item->completed_at->format('d M Y h:i A') }}

                                </div>

                            @endif

                        </td>


                        {{-- Price --}}
                        <td class="px-6 py-4 text-right font-semibold text-emerald-400">

                            ₦{{ number_format($item->price, 2) }}

                        </td>


                        {{-- Sample Status --}}
                        <td class="px-6 py-4 text-center">

                            @if($item->sample_status === \App\Models\TestRequestItem::SAMPLE_PENDING)

                                <span class="inline-flex rounded-full bg-yellow-900/30 px-3 py-1 text-xs font-semibold text-yellow-400 ring-1 ring-yellow-700">
                                    Pending
                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-green-900/30 px-3 py-1 text-xs font-semibold text-green-400 ring-1 ring-green-700">
                                    Collected
                                </span>

                            @endif

                        </td>


                        {{-- Test Status --}}
                        <td class="px-6 py-4 text-center">

                            @switch($item->status)

                                @case(\App\Models\TestRequestItem::STATUS_PENDING)

                                    <span class="inline-flex rounded-full bg-yellow-900/30 px-3 py-1 text-xs font-semibold text-yellow-400 ring-1 ring-yellow-700">
                                        Pending
                                    </span>

                                    @break

                                @case(\App\Models\TestRequestItem::STATUS_IN_PROGRESS)

                                    <span class="inline-flex rounded-full bg-blue-900/30 px-3 py-1 text-xs font-semibold text-blue-400 ring-1 ring-blue-700">
                                        In Progress
                                    </span>

                                    @break

                                @case(\App\Models\TestRequestItem::STATUS_COMPLETED)

                                    <span class="inline-flex rounded-full bg-green-900/30 px-3 py-1 text-xs font-semibold text-green-400 ring-1 ring-green-700">
                                        Completed
                                    </span>

                                    @break

                            @endswitch

                        </td>




                     
                       {{-- Actions --}}

<td class="px-6 py-4">

    <div class="flex flex-col items-center gap-2">

        {{-- Collect Sample --}}
        @if($item->sample_status === \App\Models\TestRequestItem::SAMPLE_PENDING)

            <form
                action="{{ route('test-request-items.collect-sample', $item) }}"
                method="POST"
                class="w-full"
            >
                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="w-full rounded-lg bg-purple-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-purple-700"
                >
                    Collect Sample
                </button>

            </form>

        @endif


        {{-- Start Test --}}
        @if(
            $item->sample_status === \App\Models\TestRequestItem::SAMPLE_COLLECTED &&
            $item->status === \App\Models\TestRequestItem::STATUS_PENDING
        )

            <form
                action="{{ route('test-request-items.start', $item) }}"
                method="POST"
                class="w-full"
            >
                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700"
                >
                    Start Test
                </button>

            </form>

        @endif


        {{-- Complete Test --}}
        @if($item->status === \App\Models\TestRequestItem::STATUS_IN_PROGRESS)

            <form
                action="{{ route('test-request-items.complete', $item) }}"
                method="POST"
                class="w-full"
            >
                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="w-full rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-green-700"
                >
                    Complete Test
                </button>

            </form>

        @endif


        {{-- Completed --}}
        @if($item->status === \App\Models\TestRequestItem::STATUS_COMPLETED)

            <span class="inline-flex items-center rounded-lg bg-green-900/30 px-3 py-2 text-xs font-semibold text-green-400 ring-1 ring-green-700">
                ✓ Completed
            </span>

        @endif

    </div>

</td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="px-6 py-12 text-center">

                            <div class="text-slate-400">

                                No tests found for this request.

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


</div>

</div>

</div>

</div>

</x-app-layout>