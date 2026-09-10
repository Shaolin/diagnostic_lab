
<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-white">
                Super Admin Dashboard
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Overview of all laboratories registered on the system.
            </p>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-7xl px-4 text-white sm:px-6 lg:px-8">

            {{-- ================================================================
                MAIN STATISTICS
            ================================================================= --}}

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">

                {{-- Total Laboratories --}}
                <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-medium text-slate-400">
                                Total Laboratories
                            </p>

                            <p class="mt-2 text-3xl font-bold text-white">
                                {{ number_format($totalLaboratories) }}
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-900/30">
                            <span class="text-2xl">🏥</span>
                        </div>

                    </div>

                </div>


                {{-- Active Laboratories --}}
                <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-medium text-slate-400">
                                Active Laboratories
                            </p>

                            <p class="mt-2 text-3xl font-bold text-green-400">
                                {{ number_format($activeLaboratories) }}
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-900/30">
                            <span class="text-2xl">✓</span>
                        </div>

                    </div>

                </div>


                {{-- Inactive Laboratories --}}
                <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-medium text-slate-400">
                                Inactive Laboratories
                            </p>

                            <p class="mt-2 text-3xl font-bold text-red-400">
                                {{ number_format($inactiveLaboratories) }}
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-red-900/30">
                            <span class="text-2xl">⏸</span>
                        </div>

                    </div>

                </div>


                {{-- Total Users --}}
                <div class="rounded-xl border border-slate-700 bg-slate-800 p-6 shadow-xl">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm font-medium text-slate-400">
                                Total Users
                            </p>

                            <p class="mt-2 text-3xl font-bold text-indigo-400">
                                {{ number_format($totalUsers) }}
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-900/30">
                            <span class="text-2xl">👥</span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================================
                LABORATORIES
            ================================================================= --}}

            <div class="mt-8 overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                <div class="border-b border-slate-700 px-6 py-5">

                    <h3 class="text-lg font-semibold text-white">
                        Registered Laboratories
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        All laboratories currently registered on the platform.
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-900">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Laboratory
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Owner / Admin
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Users
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                                    Registered
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-700">

                            @forelse($laboratories as $laboratory)

                                <tr class="transition hover:bg-slate-700/40">

                                    {{-- Laboratory --}}
                                    <td class="px-6 py-4">

                                        <p class="text-sm font-semibold text-white">
                                            {{ $laboratory->name }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $laboratory->subdomain }}
                                        </p>

                                    </td>


                                    {{-- Owner --}}
                                    <td class="px-6 py-4">

                                        @php
                                            $owner = $laboratory->users->first();
                                        @endphp

                                        @if($owner)

                                            <p class="text-sm font-medium text-white">
                                                {{ $owner->name }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $owner->email }}
                                            </p>

                                        @else

                                            <span class="text-sm text-slate-500">
                                                No admin assigned
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Users --}}
                                    <td class="px-6 py-4">

                                        <span class="text-sm text-slate-300">
                                            {{ number_format($laboratory->users_count) }}
                                            {{ $laboratory->users_count === 1 ? 'user' : 'users' }}
                                        </span>

                                    </td>


                                    {{-- Status --}}
                                    <td class="px-6 py-4">

                                        @if($laboratory->is_active)

                                            <span class="inline-flex rounded-full bg-green-900/30 px-2.5 py-1 text-xs font-semibold text-green-400 ring-1 ring-green-700">
                                                Active
                                            </span>

                                        @else

                                            <span class="inline-flex rounded-full bg-red-900/30 px-2.5 py-1 text-xs font-semibold text-red-400 ring-1 ring-red-700">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Registered --}}
                                    <td class="px-6 py-4">

                                        <span class="text-sm text-slate-300">
                                            {{ $laboratory->created_at?->format('d M Y') ?? '—' }}
                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="5"
                                        class="px-6 py-12 text-center"
                                    >

                                        <p class="text-sm text-slate-500">
                                            No laboratories registered yet.
                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($laboratories->hasPages())

                    <div class="border-t border-slate-700 px-6 py-4">
                        {{ $laboratories->links() }}
                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>

