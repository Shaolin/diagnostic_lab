<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>
                <h2 class="text-xl font-semibold text-white">
                    Test Type Details
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    View detailed information about this diagnostic test.
                </p>
            </div>

            <div class="flex gap-3">

                <a
                    href="{{ route('test-types.edit', $testType) }}"
                    class="rounded-lg bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600"
                >
                    Edit
                </a>

                <a
                    href="{{ route('test-types.index') }}"
                    class="rounded-lg border border-slate-600 bg-slate-800 px-5 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700"
                >
                    Back
                </a>

            </div>

        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Success Message --}}
            @if(session('success'))

                <div class="rounded-lg border border-green-700 bg-green-900/30 px-4 py-3 text-green-300">

                    {{ session('success') }}

                </div>

            @endif

            {{-- Main Details Card --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                <div class="border-b border-slate-700 px-6 py-5">

                    <h3 class="text-lg font-semibold text-white">
                        Test Information
                    </h3>

                </div>

                <div class="grid grid-cols-1 divide-y divide-slate-700 md:grid-cols-2 md:divide-x md:divide-y-0">

                    {{-- Test Code --}}
                    <div class="p-6">
                        <p class="text-sm text-slate-400">Test Code</p>

                        <p class="mt-2 font-mono text-lg font-bold text-indigo-400">
                            {{ $testType->code }}
                        </p>
                    </div>

                    {{-- Status --}}
                    <div class="p-6">
                        <p class="text-sm text-slate-400">Status</p>

                        <div class="mt-2">

                            @if($testType->is_active)

                                <span class="rounded-full bg-green-900/30 px-3 py-1 text-sm font-semibold text-green-400 ring-1 ring-green-700">
                                    Active
                                </span>

                            @else

                                <span class="rounded-full bg-red-900/30 px-3 py-1 text-sm font-semibold text-red-400 ring-1 ring-red-700">
                                    Inactive
                                </span>

                            @endif

                        </div>

                    </div>

                    {{-- Name --}}
                    <div class="p-6">
                        <p class="text-sm text-slate-400">Test Name</p>

                        <p class="mt-2 text-lg font-semibold text-white">
                            {{ $testType->name }}
                        </p>
                    </div>

                    {{-- Category --}}
                    <div class="p-6">
                        <p class="text-sm text-slate-400">Category</p>

                        <p class="mt-2 text-white">
                            {{ $testType->category ?: '—' }}
                        </p>
                    </div>

                    {{-- Price --}}
                    <div class="p-6">
                        <p class="text-sm text-slate-400">Default Price</p>

                        <p class="mt-2 text-lg font-bold text-emerald-400">
                            ₦{{ number_format($testType->default_price, 2) }}
                        </p>
                    </div>

                    {{-- Turnaround --}}
                    <div class="p-6">
                        <p class="text-sm text-slate-400">
                            Estimated Turnaround
                        </p>

                        <p class="mt-2 text-white">
                            {{ $testType->estimated_turnaround_hours
                                ? $testType->estimated_turnaround_hours . ' Hour(s)'
                                : '—' }}
                        </p>
                    </div>

                </div>

                {{-- Description --}}
                <div class="border-t border-slate-700 p-6">

                    <h4 class="mb-3 text-sm font-semibold uppercase tracking-wide text-slate-400">
                        Description
                    </h4>

                    <p class="leading-7 text-slate-300">

                        {{ $testType->description ?: 'No description provided.' }}

                    </p>

                </div>

            </div>


            {{-- Audit Information --}}
            <div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                <div class="border-b border-slate-700 px-6 py-5">

                    <h3 class="text-lg font-semibold text-white">
                        Audit Information
                    </h3>

                </div>

                <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-3">

                    <div>
                        <p class="text-sm text-slate-400">Created By</p>

                        <p class="mt-2 text-white">
                            {{ $testType->creator->name ?? 'N/A' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-400">Date Created</p>

                        <p class="mt-2 text-white">
                            {{ $testType->created_at->format('d M Y h:i A') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-slate-400">Last Updated</p>

                        <p class="mt-2 text-white">
                            {{ $testType->updated_at->format('d M Y h:i A') }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- Future Test Requests --}}
            <div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                <div class="border-b border-slate-700 px-6 py-5">

                    <h3 class="text-lg font-semibold text-white">
                        Recent Test Requests
                    </h3>

                </div>

                <div class="p-8 text-center">

                    <p class="text-slate-400">

                        No test requests available yet.

                    </p>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>