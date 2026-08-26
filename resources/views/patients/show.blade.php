<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <div class="flex items-center gap-3">

                    <a
                        href="{{ route('patients.index') }}"
                        class="rounded-lg border border-slate-600 bg-slate-800 px-3 py-2 text-sm text-slate-300 transition hover:bg-slate-700"
                    >
                        ← Back
                    </a>

                    <div>

                        <h2 class="text-xl font-semibold text-white">
                            Patient Profile
                        </h2>

                        <p class="mt-1 text-sm text-slate-400">
                            View patient information and medical history.
                        </p>

                    </div>

                </div>

            </div>

            <div class="flex flex-wrap gap-3">

                <a
                    href="{{ route('patients.edit', $patient) }}"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                >
                    Edit Patient
                </a>

                <button
                    type="button"
                    disabled
                    class="cursor-not-allowed rounded-lg bg-slate-700 px-5 py-2.5 text-sm font-semibold text-slate-400"
                >
                    + New Test Request
                </button>

            </div>

        </div>

    </x-slot>

    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            {{-- Patient Summary --}}
<div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="p-8">

        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">

            {{-- Left Side --}}
            <div>

                {{-- Patient Number --}}
                <span
                    class="inline-flex rounded-full bg-indigo-600/20 px-4 py-1 text-sm font-bold tracking-wider text-indigo-300 ring-1 ring-indigo-500/30"
                >
                    {{ $patient->patient_number }}
                </span>

                {{-- Name --}}
                <h1 class="mt-4 text-3xl font-bold text-white">
                    {{ $patient->full_name }}
                </h1>

                {{-- Gender • Age • Status --}}
                <div class="mt-4 flex flex-wrap items-center gap-3">

                    <span class="text-slate-300">
                        {{ $patient->gender }}
                    </span>

                    <span class="text-slate-600">
                        •
                    </span>

                    <span class="text-slate-300">

                        @if ($patient->date_of_birth)

                            {{ $patient->age }} Years

                        @else

                            Age Unknown

                        @endif

                    </span>

                    <span class="text-slate-600">
                        •
                    </span>

                    @if ($patient->is_active)

                        <span class="inline-flex items-center rounded-full bg-green-900/30 px-3 py-1 text-xs font-semibold text-green-400 ring-1 ring-green-500/20">

                            <span class="mr-2 h-2 w-2 rounded-full bg-green-400"></span>

                            Active

                        </span>

                    @else

                        <span class="inline-flex items-center rounded-full bg-red-900/30 px-3 py-1 text-xs font-semibold text-red-400 ring-1 ring-red-500/20">

                            <span class="mr-2 h-2 w-2 rounded-full bg-red-400"></span>

                            Inactive

                        </span>

                    @endif

                </div>

            </div>

            {{-- Right Side --}}
            <div class="space-y-3 rounded-xl border border-slate-700 bg-slate-900 p-5 lg:w-80">

                <div>

                    <p class="text-xs uppercase tracking-widest text-slate-500">
                        Registered On
                    </p>

                    <p class="mt-1 font-medium text-white">
                        {{ $patient->created_at->format('d F, Y') }}
                    </p>

                </div>

                <div>

                    <p class="text-xs uppercase tracking-widest text-slate-500">
                        Registered Time
                    </p>

                    <p class="mt-1 font-medium text-white">
                        {{ $patient->created_at->format('h:i A') }}
                    </p>

                </div>

                <div>

                    <p class="text-xs uppercase tracking-widest text-slate-500">
                        Registered By
                    </p>

                    <p class="mt-1 font-medium text-white">
                        {{ $patient->creator?->name ?? 'System' }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>
{{-- Patient Details --}}
<div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="border-b border-slate-700 px-6 py-4">

        <h3 class="text-lg font-semibold text-white">
            Patient Information
        </h3>

        <p class="mt-1 text-sm text-slate-400">
            Personal and medical information.
        </p>

    </div>

    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

        {{-- Phone --}}
        <div>

            <p class="text-xs uppercase tracking-wider text-slate-500">
                Phone Number
            </p>

            <p class="mt-2">

                <a
                    href="tel:{{ $patient->phone }}"
                    class="font-medium text-indigo-400 hover:underline"
                >
                    {{ $patient->phone }}
                </a>

            </p>

        </div>

        {{-- Email --}}
        <div>

            <p class="text-xs uppercase tracking-wider text-slate-500">
                Email Address
            </p>

            <p class="mt-2">

                @if($patient->email)

                    <a
                        href="mailto:{{ $patient->email }}"
                        class="font-medium text-indigo-400 hover:underline"
                    >
                        {{ $patient->email }}
                    </a>

                @else

                    <span class="italic text-slate-500">
                        Not Provided
                    </span>

                @endif

            </p>

        </div>

        {{-- Date of Birth --}}
        <div>

            <p class="text-xs uppercase tracking-wider text-slate-500">
                Date of Birth
            </p>

            <p class="mt-2 font-medium text-white">

                @if($patient->date_of_birth)

                    {{ $patient->date_of_birth->format('d F, Y') }}

                @else

                    <span class="italic text-slate-500">
                        Not Provided
                    </span>

                @endif

            </p>

        </div>

        {{-- Age --}}
        <div>

            <p class="text-xs uppercase tracking-wider text-slate-500">
                Age
            </p>

            <p class="mt-2 font-medium text-white">
                {{ $patient->age }}
            </p>

        </div>

        {{-- Blood Group --}}
        <div>

            <p class="text-xs uppercase tracking-wider text-slate-500">
                Blood Group
            </p>

            <p class="mt-2 font-medium text-white">

                {{ $patient->blood_group ?: 'Not Recorded' }}

            </p>

        </div>

        {{-- Genotype --}}
        <div>

            <p class="text-xs uppercase tracking-wider text-slate-500">
                Genotype
            </p>

            <p class="mt-2 font-medium text-white">

                {{ $patient->genotype ?: 'Not Recorded' }}

            </p>

        </div>

        {{-- Address --}}
        <div class="md:col-span-2">

            <p class="text-xs uppercase tracking-wider text-slate-500">
                Address
            </p>

            <div class="mt-2 rounded-lg border border-slate-700 bg-slate-900 p-4">

                @if($patient->address)

                    <p class="leading-relaxed text-white">
                        {{ $patient->address }}
                    </p>

                @else

                    <p class="italic text-slate-500">
                        No address provided.
                    </p>

                @endif

            </div>

        </div>

    </div>

</div>
{{-- Emergency Contact --}}
<div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="border-b border-slate-700 px-6 py-4">

        <h3 class="text-lg font-semibold text-white">
            Emergency Contact
        </h3>

        <p class="mt-1 text-sm text-slate-400">
            Person to contact in case of an emergency.
        </p>

    </div>

    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

        {{-- Contact Name --}}
        <div>

            <p class="text-xs uppercase tracking-wider text-slate-500">
                Contact Name
            </p>

            <p class="mt-2 font-medium text-white">

                {{ $patient->emergency_contact_name ?: 'Not Provided' }}

            </p>

        </div>

        {{-- Contact Phone --}}
        <div>

            <p class="text-xs uppercase tracking-wider text-slate-500">
                Contact Phone
            </p>

            <p class="mt-2">

                @if($patient->emergency_contact_phone)

                    <a
                        href="tel:{{ $patient->emergency_contact_phone }}"
                        class="font-medium text-indigo-400 hover:underline"
                    >
                        {{ $patient->emergency_contact_phone }}
                    </a>

                @else

                    <span class="italic text-slate-500">
                        Not Provided
                    </span>

                @endif

            </p>

        </div>

    </div>

</div>
{{-- Notes --}}
<div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="border-b border-slate-700 px-6 py-4">

        <h3 class="text-lg font-semibold text-white">
            Notes
        </h3>

        <p class="mt-1 text-sm text-slate-400">
            Additional information recorded during patient registration.
        </p>

    </div>

    <div class="p-6">

        @if($patient->notes)

            <div class="rounded-lg border border-slate-700 bg-slate-900 p-5">

                <p class="whitespace-pre-line leading-relaxed text-slate-200">
                    {{ $patient->notes }}
                </p>

            </div>

        @else

            <div class="rounded-lg border border-dashed border-slate-700 bg-slate-900 p-8 text-center">

                <p class="italic text-slate-500">
                    No notes available.
                </p>

            </div>

        @endif

    </div>

</div>

{{-- Test Requests --}}
<div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    {{-- Section Header --}}
    <div class="flex flex-col gap-4 border-b border-slate-700 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h3 class="text-lg font-semibold text-white">
                Test Requests
            </h3>

            <p class="mt-1 text-sm text-slate-400">
                Laboratory tests requested for this patient.
            </p>
        </div>

        <div class="rounded-lg bg-slate-900 px-4 py-2 text-sm text-slate-300">
            <span class="font-semibold text-white">
                {{ $patient->testRequests->count() }}
            </span>
            {{ Str::plural('Request', $patient->testRequests->count()) }}
        </div>

    </div>


    {{-- Requests --}}
    @if ($patient->testRequests->isNotEmpty())

        <div class="divide-y divide-slate-700">

            @foreach ($patient->testRequests as $request)

                <div class="p-6 transition hover:bg-slate-750">

                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                        {{-- Request Information --}}
                        <div class="min-w-0 flex-1">

                            <div class="flex flex-wrap items-center gap-3">

                                {{-- Tracking Code --}}
                                <span class="inline-flex rounded-lg bg-indigo-600/20 px-3 py-1.5 text-sm font-bold tracking-wide text-indigo-300 ring-1 ring-indigo-500/30">
                                    {{ $request->tracking_code }}
                                </span>

                                {{-- Status --}}
                                @php
                                    $statusClasses = match ($request->overall_status) {
                                        \App\Models\TestRequest::STATUS_PENDING =>
                                            'bg-yellow-900/30 text-yellow-400 ring-yellow-500/20',

                                        \App\Models\TestRequest::STATUS_IN_PROGRESS =>
                                            'bg-blue-900/30 text-blue-400 ring-blue-500/20',

                                        \App\Models\TestRequest::STATUS_PARTIALLY_COMPLETED =>
                                            'bg-orange-900/30 text-orange-400 ring-orange-500/20',

                                        \App\Models\TestRequest::STATUS_COMPLETED =>
                                            'bg-green-900/30 text-green-400 ring-green-500/20',

                                        default =>
                                            'bg-slate-700 text-slate-300 ring-slate-600/30',
                                    };
                                @endphp

                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 {{ $statusClasses }}">
                                    {{ $request->overall_status }}
                                </span>

                            </div>


                            {{-- Date --}}
                            <p class="mt-3 text-sm text-slate-400">
                                Requested on
                                <span class="font-medium text-slate-300">
                                    {{ $request->created_at->format('d F, Y') }}
                                </span>

                                <span class="mx-1 text-slate-600">•</span>

                                {{ $request->created_at->format('h:i A') }}
                            </p>


                            {{-- Tests --}}
                            <div class="mt-4">

                                <p class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Tests Requested
                                </p>

                                <div class="flex flex-wrap gap-2">

                                    @forelse ($request->items as $item)

                                        <span class="inline-flex rounded-lg border border-slate-600 bg-slate-900 px-3 py-1.5 text-sm text-slate-300">
                                            {{ $item->test_name }}
                                        </span>

                                    @empty

                                        <span class="text-sm italic text-slate-500">
                                            No test items recorded.
                                        </span>

                                    @endforelse

                                </div>

                            </div>

                        </div>


                        {{-- Amount + Action --}}
                        <div class="flex flex-col gap-4 border-t border-slate-700 pt-5 sm:flex-row sm:items-center lg:border-t-0 lg:pt-0">

                            {{-- Amount --}}
                            <div class="lg:min-w-[140px] lg:text-right">

                                <p class="text-xs uppercase tracking-wider text-slate-500">
                                    Total Amount
                                </p>

                                <p class="mt-1 text-lg font-semibold text-white">
                                    ₦{{ number_format((float) $request->total_amount, 2) }}
                                </p>

                            </div>


                            {{-- View Button --}}
                            <div>

                                <a
                                    href="{{ route('test-requests.show', $request) }}"
                                    class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 sm:w-auto"
                                >
                                    View Request

                                    <svg
                                        class="ml-2 h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5l7 7-7 7"
                                        />
                                    </svg>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        {{-- Empty State --}}
        <div class="p-12 text-center">

            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-900">

                <svg
                    class="h-8 w-8 text-slate-500"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    />
                </svg>

            </div>

            <h4 class="mt-6 text-lg font-semibold text-white">
                No Test Requests Yet
            </h4>

            <p class="mt-2 text-slate-400">
                This patient has no laboratory test requests.
            </p>

        </div>

    @endif

</div>

        </div>

    </div>

</x-app-layout>