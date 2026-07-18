<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>

                <h2 class="text-xl font-semibold text-white">
                    Patient Management
                </h2>

                <p class="mt-1 text-sm text-slate-400">
                    Manage patient records for your laboratory.
                </p>

            </div>

            <a
                href="{{ route('patients.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
            >
                + New Patient
            </a>

        </div>

    </x-slot>

    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if (session('success'))

                <div class="mb-6 rounded-lg border border-green-700 bg-green-900/30 px-4 py-3 text-green-300 shadow-sm">

                    <div class="flex items-center">

                        <svg class="mr-2 h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"
                            />

                        </svg>

                        <span>{{ session('success') }}</span>

                    </div>

                </div>

            @endif

            {{-- Error Messages --}}
            @if ($errors->any())

                <div class="mb-6 rounded-lg border border-red-700 bg-red-900/30 px-4 py-3 text-red-300 shadow-sm">

                    <div class="flex items-start">

                        <svg class="mr-2 mt-0.5 h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2"
                             viewBox="0 0 24 24">

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

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif
                        {{-- Search Card --}}
            <div class="mb-6 rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                <form
                    method="GET"
                    action="{{ route('patients.index') }}"
                >

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                        {{-- Search Input --}}
                        <div class="md:col-span-3">

                            <label
                                for="search"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Search Patient
                            </label>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search by patient number, name or phone number..."
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-4 py-2.5 text-white placeholder-slate-500 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >

                        </div>

                        {{-- Buttons --}}
                        <div class="flex items-end gap-3">

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                            >
                                Search
                            </button>

                            <a
                                href="{{ route('patients.index') }}"
                                class="inline-flex w-full items-center justify-center rounded-lg border border-slate-600 bg-slate-900 px-5 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700"
                            >
                                Reset
                            </a>

                        </div>

                    </div>

                </form>

            </div>
            {{-- Patients Table --}}
<div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="overflow-x-auto">

        <table class="min-w-full divide-y divide-slate-700">

            <thead class="bg-slate-900">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Patient ID
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Patient Information
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Contact
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Status
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Date Registered
                    </th>

                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-slate-700 bg-slate-800">

                @forelse ($patients as $patient)

                    <tr class="transition hover:bg-slate-700/40">

                       {{-- ============================
     Patient Number
============================ --}}
<td class="whitespace-nowrap px-6 py-4">

    <div class="inline-flex flex-col">

        <span class="text-[10px] uppercase tracking-widest text-slate-500">
            Patient ID
        </span>

        <a
            href="{{ route('patients.show', $patient) }}"
            class="mt-1 inline-flex rounded-md bg-indigo-600/15 px-3 py-1 font-mono text-sm font-bold tracking-wider text-indigo-300 ring-1 ring-indigo-500/30 transition hover:bg-indigo-600/25 hover:text-indigo-200"
        >
            {{ $patient->patient_number }}
        </a>

    </div>

</td>


{{-- ============================
     Patient Information
============================ --}}
<td class="px-6 py-4">

    <div class="space-y-1">

        <a
            href="{{ route('patients.show', $patient) }}"
            class="font-semibold text-white transition hover:text-indigo-400"
        >
            {{ $patient->full_name }}
        </a>

        <div class="flex items-center gap-2 text-sm text-slate-400">

            <span>{{ $patient->gender }}</span>

            <span class="text-slate-600">&bull;</span>

            @if ($patient->date_of_birth)

                <span>{{ $patient->age }} Years</span>

            @else

                <span>Age Unknown</span>

            @endif

        </div>

    </div>

</td>


{{-- ============================
     Contact
============================ --}}
<td class="px-6 py-4">

    <div class="space-y-1">

        <a
            href="tel:{{ $patient->phone }}"
            class="block text-sm text-slate-200 transition hover:text-indigo-400"
        >
            {{ $patient->phone }}
        </a>

        @if ($patient->email)

            <a
                href="mailto:{{ $patient->email }}"
                class="block text-sm text-slate-400 transition hover:text-indigo-400"
            >
                {{ $patient->email }}
            </a>

        @else

            <span class="block text-sm italic text-slate-500">
                No email
            </span>

        @endif

    </div>

</td>


{{-- ============================
     Status
============================ --}}
<td class="whitespace-nowrap px-6 py-4">

    @if ($patient->is_active)

        <span class="inline-flex items-center rounded-full bg-green-900/30 px-3 py-1 text-xs font-semibold text-green-400 ring-1 ring-green-500/20">

            <span class="mr-2 h-2 w-2 rounded-full bg-green-400"></span>

            {{ $patient->status_label }}

        </span>

    @else

        <span class="inline-flex items-center rounded-full bg-red-900/30 px-3 py-1 text-xs font-semibold text-red-400 ring-1 ring-red-500/20">

            <span class="mr-2 h-2 w-2 rounded-full bg-red-400"></span>

            {{ $patient->status_label }}

        </span>

    @endif

</td>


{{-- ============================
     Date Registered
============================ --}}
<td class="whitespace-nowrap px-6 py-4">

    <div class="space-y-1">

        <div class="text-sm font-medium text-white">
            {{ $patient->created_at->format('d M Y') }}
        </div>

        <div class="text-xs text-slate-400">
            {{ $patient->created_at->format('h:i A') }}
        </div>

    </div>

</td>


{{-- ============================
     Actions
============================ --}}
<td class="whitespace-nowrap px-6 py-4 text-right">

    <div class="flex items-center justify-end gap-2">

        <a
            href="{{ route('patients.show', $patient) }}"
            class="rounded-lg border border-slate-600 bg-slate-700 px-3 py-2 text-xs font-medium text-slate-200 transition hover:bg-slate-600"
        >
            View
        </a>

        <a
            href="{{ route('patients.edit', $patient) }}"
            class="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-medium text-white transition hover:bg-indigo-700"
        >
            Edit
        </a>

        <form
            action="{{ route('patients.toggle-status', $patient) }}"
            method="POST"
            onsubmit="return confirm('Are you sure you want to {{ $patient->is_active ? 'deactivate' : 'activate' }} this patient?');"
            class="inline"
        >
            @csrf
            @method('PATCH')

            <button
                type="submit"
                class="rounded-lg px-3 py-2 text-xs font-medium text-white transition {{ $patient->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}"
            >
                {{ $patient->is_active ? 'Deactivate' : 'Activate' }}
            </button>

        </form>

    </div>

</td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="px-6 py-12 text-center">

                            <div class="flex flex-col items-center">

                                <svg class="mb-4 h-12 w-12 text-slate-600"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="1.5"
                                     viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.964 0a9 9 0 10-11.964 0m11.964 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275"
                                    />

                                </svg>

                                <h3 class="text-lg font-semibold text-white">
                                    No patients found
                                </h3>

                                <p class="mt-2 text-sm text-slate-400">
                                    Register your first patient to get started.
                                </p>

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

</x-app-layout>