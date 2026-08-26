<x-app-layout>

    <x-slot name="header">

        <div>
            <h2 class="font-semibold text-xl text-slate-300 leading-tight">
                Results
            </h2>

            <p class="text-sm text-slate-400 mt-1">
                Manage uploaded laboratory results
            </p>
        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filters --}}
            <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-sm mb-6">

                <div class="p-5">

                    <form
                        method="GET"
                        action="{{ route('results.index') }}"
                        class="grid grid-cols-1 md:grid-cols-4 gap-4"
                    >

                        {{-- Search --}}
                        <div class="md:col-span-2">

                            <label
                                for="search"
                                class="block text-sm font-medium text-slate-300 mb-1"
                            >
                                Search
                            </label>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Patient, patient number, test or tracking code..."
                                class="w-full rounded-lg
                                       bg-slate-800
                                       border-slate-700
                                       text-slate-200
                                       placeholder-slate-500
                                       focus:border-indigo-500
                                       focus:ring-indigo-500"
                            >

                        </div>


                        {{-- Status --}}
                        <div>

                            <label
                                for="status"
                                class="block text-sm font-medium text-slate-300 mb-1"
                            >
                                Verification Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="w-full rounded-lg
                                       bg-slate-800
                                       border-slate-700
                                       text-slate-200
                                       focus:border-indigo-500
                                       focus:ring-indigo-500"
                            >

                                <option value="">All Results</option>

                                <option
                                    value="verified"
                                    {{ request('status') === 'verified' ? 'selected' : '' }}
                                >
                                    Verified
                                </option>

                                <option
                                    value="pending"
                                    {{ request('status') === 'pending' ? 'selected' : '' }}
                                >
                                    Awaiting Verification
                                </option>

                            </select>

                        </div>


                        {{-- Buttons --}}
                        <div class="flex items-end gap-2">

                            <button
                                type="submit"
                                class="px-4 py-2 rounded-lg
                                       bg-indigo-600
                                       text-white
                                       text-sm font-semibold
                                       hover:bg-indigo-700
                                       transition"
                            >
                                Search
                            </button>

                            <a
                                href="{{ route('results.index') }}"
                                class="px-4 py-2 rounded-lg
                                       bg-slate-800
                                       border border-slate-700
                                       text-slate-300
                                       text-sm font-semibold
                                       hover:bg-slate-700
                                       hover:text-white
                                       transition"
                            >
                                Reset
                            </a>

                        </div>

                    </form>

                </div>

            </div>


            {{-- Results Table --}}
            <div class="bg-slate-900 border border-slate-800 rounded-xl shadow-sm overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-800">

                        <thead class="bg-slate-800/70">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Patient
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Tracking Code
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Test
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Uploaded
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Status
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-800">

                            @forelse($results as $result)

                                <tr class="hover:bg-slate-800/40 transition">

                                    {{-- Patient --}}
                                    <td class="px-6 py-4">

                                        <div class="font-medium text-slate-200">
                                            {{ $result->testRequestItem->testRequest->patient->full_name }}
                                        </div>

                                        <div class="text-xs text-slate-500 mt-1">
                                            {{ $result->testRequestItem->testRequest->patient->patient_number }}
                                        </div>

                                    </td>


                                    {{-- Tracking Code --}}
                                    <td class="px-6 py-4">

                                        <span class="text-sm text-slate-300">
                                            {{ $result->testRequestItem->testRequest->tracking_code }}
                                        </span>

                                    </td>


                                    {{-- Test --}}
                                    <td class="px-6 py-4">

                                        <span class="text-sm text-slate-300">
                                            {{ $result->testRequestItem->test_name }}
                                        </span>

                                    </td>


                                    {{-- Uploaded --}}
                                    <td class="px-6 py-4">

                                        <div class="text-sm text-slate-300">
                                            {{ $result->uploaded_at?->format('d M Y') ?? 'N/A' }}
                                        </div>

                                        <div class="text-xs text-slate-500 mt-1">
                                            {{ $result->uploaded_at?->format('h:i A') }}
                                        </div>

                                    </td>


                                    {{-- Status --}}
                                    <td class="px-6 py-4">

                                        @if($result->verified_at)

                                            <span class="inline-flex items-center rounded-full
                                                         bg-green-900/30
                                                         px-3 py-1
                                                         text-xs font-semibold
                                                         text-green-400
                                                         ring-1 ring-green-700">

                                                ✓ Verified

                                            </span>

                                        @else

                                            <span class="inline-flex items-center rounded-full
                                                         bg-yellow-900/30
                                                         px-3 py-1
                                                         text-xs font-semibold
                                                         text-yellow-400
                                                         ring-1 ring-yellow-700">

                                                Awaiting Verification

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Actions --}}
                                    <td class="px-6 py-4">

                                        <div class="flex flex-wrap justify-end gap-2">

                                            {{-- Download --}}
                                            <a
                                                href="{{ route('results.download', $result) }}"
                                                class="rounded-lg
                                                       bg-slate-700
                                                       px-3 py-2
                                                       text-xs font-semibold
                                                       text-slate-200
                                                       hover:bg-slate-600
                                                       transition"
                                            >
                                                Download
                                            </a>


                                            {{-- Verify --}}
                                            @if(!$result->verified_at)

                                                <form
                                                    action="{{ route('results.verify', $result) }}"
                                                    method="POST"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="rounded-lg
                                                               bg-green-600
                                                               px-3 py-2
                                                               text-xs font-semibold
                                                               text-white
                                                               hover:bg-green-700
                                                               transition"
                                                    >
                                                        Verify
                                                    </button>

                                                </form>

                                            @endif


                                            {{-- Replace --}}
                                            <a
                                                href="{{ route('results.edit', $result) }}"
                                                class="rounded-lg
                                                       bg-amber-600
                                                       px-3 py-2
                                                       text-xs font-semibold
                                                       text-white
                                                       hover:bg-amber-700
                                                       transition"
                                            >
                                                Replace
                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="6"
                                        class="px-6 py-12 text-center"
                                    >

                                        <div class="text-slate-500">
                                            No laboratory results found.
                                        </div>

                                        @if(request()->hasAny(['search', 'status']))

                                            <a
                                                href="{{ route('results.index') }}"
                                                class="inline-block mt-3 text-sm text-indigo-400 hover:text-indigo-300"
                                            >
                                                Clear filters
                                            </a>

                                        @endif

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($results->hasPages())

                    <div class="border-t border-slate-800 px-6 py-4">

                        {{ $results->links() }}

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>