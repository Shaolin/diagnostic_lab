<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            
            <h2 class="text-xl font-semibold text-white">
                {{ __('Laboratories') }}
            </h2>

           @if(auth()->user()->isSuperAdmin())

    <a href="{{ route('laboratories.create') }}"
       class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-700">
        + New Laboratory
    </a>

@endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-100 px-4 py-3 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-lg border border-red-200 bg-red-100 px-4 py-3 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                {{-- Search --}}
                <div class="border-b border-slate-700 p-6">

                    <form action="{{ route('laboratories.index') }}" method="GET">

                        <div class="flex flex-col md:flex-row gap-4">

                            <input
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Search laboratory name or subdomain..."
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500">

                            <button
                                type="submit"
                                class="rounded-md bg-indigo-600 px-5 py-2 text-white hover:bg-indigo-700">
                                Search
                            </button>

                            @if($search)
                                <a href="{{ route('laboratories.index') }}"
                                   class="rounded-md bg-gray-500 px-5 py-2 text-white hover:bg-gray-600">
                                    Clear
                                </a>
                            @endif

                        </div>

                    </form>

                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-900">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300 text-slate-300">
                                    Logo
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                                    Laboratory
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                                    Subdomain
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                                    Country
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                                    Currency
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                                    Status
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                                    Created
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-300">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-700 bg-slate-800">

                            @forelse($laboratories as $laboratory)

                                <tr>

                                    {{-- Logo --}}
                                    <td class="px-6 py-4">

                                        @if($laboratory->logo_url)
                                            <img src="{{ $laboratory->logo_url }}"
                                                 alt="{{ $laboratory->name }}"
                                                 class="h-12 w-12 rounded-full object-cover">
                                        @else
                                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-200 text-sm font-bold text-slate-400">
                                                {{ strtoupper(substr($laboratory->name, 0, 1)) }}
                                            </div>
                                        @endif

                                    </td>

                                    {{-- Name --}}
                                    <td class="px-6 py-4 font-medium text-white">
                                        {{ $laboratory->name }}
                                    </td>
                                    

                                    {{-- Subdomain --}}
                                    <td class="px-6 py-4 text-slate-400">
                                        {{ $laboratory->subdomain }}
                                    </td>

                                    {{-- Country --}}
                                    <td class="px-6 py-4 text-slate-400">
                                        {{ $laboratory->country ?? '-' }}
                                    </td>

                                    {{-- Currency --}}
                                    <td class="px-6 py-4 text-slate-400">
                                        {{ $laboratory->currency_symbol }}
                                        ({{ $laboratory->currency_code }})
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4">

                                        @if($laboratory->is_active)

                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                Active
                                            </span>

                                        @else

                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>

                                    {{-- Created --}}
                                    <td class="px-6 py-4 text-slate-400">
                                        {{ $laboratory->created_at->format('d M Y') }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4">

                                        <div class="flex justify-end gap-2">

                                            <a href="{{ route('laboratories.show', $laboratory) }}"
                                               class="rounded bg-blue-600 px-3 py-1 text-sm text-white hover:bg-blue-700">
                                                View
                                            </a>

                                            <a href="{{ route('laboratories.edit', $laboratory) }}"
                                               class="rounded bg-yellow-500 px-3 py-1 text-sm text-white hover:bg-yellow-600">
                                                Edit
                                            </a>

                                           @if(auth()->user()->isSuperAdmin())

<form action="{{ route('laboratories.destroy', $laboratory) }}"
      method="POST"
      onsubmit="return confirm('Delete this laboratory?')">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    class="rounded bg-red-600 px-3 py-1 text-sm text-white hover:bg-red-700">
                                                    Delete
                                                </button>

                                            </form>
                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8"
                                        class="px-6 py-10 text-center text-gray-500">

                                        No laboratories found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}
                @if($laboratories->hasPages())

                    <div class="border-t p-6">

                        {{ $laboratories->links() }}

                    </div>

                @endif

            </div>

        </div>
    </div>

</x-app-layout>