<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

            <div>
                <h2 class="text-xl font-semibold text-white">
                    Test Types Management
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Manage the diagnostic tests available in your laboratory.
                </p>
            </div>

            <a
                href="{{ route('test-types.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
            >
                + New Test Type
            </a>

        </div>
    </x-slot>


    <div class="min-h-screen bg-slate-900 py-8">

        <div class="mx-auto max-w-7xl px-4 text-white sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
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
                    action="{{ route('test-types.index') }}"
                >

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                        {{-- Search --}}
                        <div class="md:col-span-2">

                            <label
                                for="search"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Search
                            </label>

                            <input
                                type="text"
                                name="search"
                                id="search"
                                value="{{ request('search') }}"
                                placeholder="Search by test code or test name..."
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>


                        {{-- Category --}}
                        <div>

                            <label
                                for="category"
                                class="mb-2 block text-sm font-medium text-slate-300"
                            >
                                Category
                            </label>

                            <select
                                name="category"
                                id="category"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white focus:border-indigo-500 focus:ring-indigo-500"
                            >

                                <option value="">
                                    All Categories
                                </option>

                                @foreach($categories as $category)

                                    <option
                                        value="{{ $category }}"
                                        @selected(request('category') == $category)
                                    >
                                        {{ $category }}
                                    </option>

                                @endforeach

                            </select>

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
                                    All Status
                                </option>

                                <option
                                    value="1"
                                    @selected(request('status') === '1')
                                >
                                    Active
                                </option>

                                <option
                                    value="0"
                                    @selected(request('status') === '0')
                                >
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>


                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">

                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                        >
                            Filter
                        </button>


                        <a
                            href="{{ route('test-types.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-600 bg-slate-900 px-5 py-2.5 text-sm font-semibold text-slate-300 transition hover:bg-slate-700"
                        >
                            Reset
                        </a>

                    </div>

                </form>

            </div>

            {{-- Table starts here --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

    <div class="overflow-x-auto">

        <table class="min-w-full divide-y divide-slate-700">

            {{-- Table Header --}}
            <thead class="bg-slate-900">

                <tr>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Test Code
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Test Name
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Category
                    </th>

                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Default Price
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Turnaround
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Status
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Created
                    </th>

                    <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Actions
                    </th>

                </tr>

            </thead>


            {{-- Table Body --}}
            <tbody class="divide-y divide-slate-700">

                @forelse($testTypes as $testType)

                    <tr class="transition hover:bg-slate-700/40">

                        {{-- Code --}}
                        <td class="whitespace-nowrap px-6 py-4">

                            <span class="font-mono font-semibold text-indigo-400">
                                {{ $testType->code }}
                            </span>

                        </td>


                        {{-- Name --}}
                        <td class="px-6 py-4">

                            <div class="font-medium text-white">
                                {{ $testType->name }}
                            </div>

                            @if($testType->description)

                                <div class="mt-1 max-w-xs truncate text-xs text-slate-500">
                                    {{ $testType->description }}
                                </div>

                            @endif

                        </td>


                        {{-- Category --}}
                        <td class="whitespace-nowrap px-6 py-4 text-slate-300">

                            {{ $testType->category ?: '—' }}

                        </td>


                        {{-- Price --}}
                        <td class="whitespace-nowrap px-6 py-4 text-right font-semibold text-emerald-400">

                            ₦{{ number_format($testType->default_price, 2) }}

                        </td>


                        {{-- Turnaround --}}
                        <td class="whitespace-nowrap px-6 py-4 text-center text-slate-300">

                            @if($testType->estimated_turnaround_hours)

                                {{ $testType->estimated_turnaround_hours }}
                                {{ Str::plural('Hour', $testType->estimated_turnaround_hours) }}

                            @else

                                —

                            @endif

                        </td>


                        {{-- Status --}}
                        <td class="whitespace-nowrap px-6 py-4 text-center">

                            @if($testType->is_active)

                                <span class="inline-flex rounded-full bg-green-900/30 px-3 py-1 text-xs font-semibold text-green-400 ring-1 ring-green-700">

                                    Active

                                </span>

                            @else

                                <span class="inline-flex rounded-full bg-red-900/30 px-3 py-1 text-xs font-semibold text-red-400 ring-1 ring-red-700">

                                    Inactive

                                </span>

                            @endif

                        </td>


                        {{-- Created --}}
                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-slate-400">

                            {{ $testType->created_at->format('d M Y') }}

                        </td>


                        {{-- Actions --}}
                       <td class="whitespace-nowrap px-6 py-4 text-center">

    <div class="flex items-center justify-center gap-2">

        {{-- View --}}
        <a
            href="{{ route('test-types.show', $testType) }}"
            class="rounded-lg bg-sky-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-sky-700"
        >
            View
        </a>

        {{-- Edit --}}
        <a
            href="{{ route('test-types.edit', $testType) }}"
            class="rounded-lg bg-amber-500 px-3 py-2 text-xs font-semibold text-white transition hover:bg-amber-600"
        >
            Edit
        </a>

        @if($testType->is_active)

            <form
                action="{{ route('test-types.deactivate', $testType) }}"
                method="POST"
                class="inline"
                onsubmit="return confirm('Are you sure you want to deactivate this Test Type?');"
            >
                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-red-700"
                >
                    Deactivate
                </button>

            </form>

        @else

            <form
                action="{{ route('test-types.activate', $testType) }}"
                method="POST"
                class="inline"
                onsubmit="return confirm('Activate this Test Type?');"
            >
                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-green-700"
                >
                    Activate
                </button>

            </form>

        @endif

    </div>

</td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
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
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A3.375 3.375 0 0011.25 11.625v2.625m8.25 0v2.25A2.25 2.25 0 0117.25 18.75H6.75A2.25 2.25 0 014.5 16.5v-2.25m15 0H4.5"
                                    />
                                </svg>

                                <h3 class="mt-4 text-lg font-semibold text-white">
                                    No Test Types Found
                                </h3>

                                <p class="mt-2 text-sm text-slate-500">

                                    No diagnostic test types match your current filters.

                                </p>

                                <a
                                    href="{{ route('test-types.create') }}"
                                    class="mt-6 inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700"
                                >
                                    Create Your First Test Type
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
@if ($testTypes->hasPages())

    <div class="mt-6">

        {{ $testTypes->withQueryString()->links() }}

    </div>

@endif

</div>

</div>

</x-app-layout>