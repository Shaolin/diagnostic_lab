<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <h2 class="text-xl font-semibold text-white">
                {{ __('Branches') }}
            </h2>

            <a href="{{ route('branches.create') }}"
               class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-700">
                + New Branch
            </a>

        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-4 rounded-lg border border-green-700 bg-green-900/30 px-4 py-3 text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-lg border border-red-700 bg-red-900/30 px-4 py-3 text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <div class="rounded-xl border border-slate-700 bg-slate-800 shadow-xl">

                {{-- Table --}}
                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-900">

                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                                    Branch
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                                    Code
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                                    Phone
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-300">
                                    Status
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-300">
                                    Actions
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-slate-700 bg-slate-800">

                            @forelse($branches as $branch)

                                <tr>

                                    {{-- Branch --}}
                                    <td class="px-6 py-4">

                                        <p class="font-medium text-white">
                                            {{ $branch->name }}
                                        </p>

                                        @if($branch->address)
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ $branch->address }}
                                            </p>
                                        @endif

                                    </td>

                                    {{-- Code --}}
                                    <td class="px-6 py-4 text-slate-400">
                                        {{ $branch->code ?? '-' }}
                                    </td>

                                    {{-- Phone --}}
                                    <td class="px-6 py-4 text-slate-400">
                                        {{ $branch->phone ?? '-' }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4">

                                        @if($branch->is_active)

                                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                Active
                                            </span>

                                        @else

                                            <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4">

                                        <div class="flex justify-end gap-2">

                                            <a href="{{ route('branches.show', $branch) }}"
                                               class="rounded bg-blue-600 px-3 py-1 text-sm text-white hover:bg-blue-700">
                                                View
                                            </a>

                                            <a href="{{ route('branches.edit', $branch) }}"
                                               class="rounded bg-yellow-500 px-3 py-1 text-sm text-white hover:bg-yellow-600">
                                                Edit
                                            </a>
                                            <form method="POST"
      action="{{ route('branches.destroy', $branch) }}"
      class="inline"
      onsubmit="return confirm('Are you sure you want to delete this branch?');">

    @csrf
    @method('DELETE')

    <button type="submit"
            class="inline-flex items-center rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-600 transition">
        Delete
    </button>

</form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5"
                                        class="px-6 py-10 text-center text-slate-500">

                                        No branches found.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}
                @if($branches->hasPages())

                    <div class="border-t border-slate-700 p-6">

                        {{ $branches->links() }}

                    </div>

                @endif

            </div>

        </div>
    </div>

</x-app-layout>