<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-white">
                    Suppliers
                </h2>
                <p class="mt-1 text-sm text-slate-400">
                    Manage your laboratory suppliers.
                </p>
            </div>

            <a href="{{ route('suppliers.create') }}"
               class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                + Add Supplier
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-700 bg-green-900/30 px-4 py-3 text-sm text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-900 shadow-sm">

                <div class="border-b border-slate-700 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">
                        Supplier List
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">
                                    Supplier
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">
                                    Contact Person
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">
                                    Phone
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">
                                    Email
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">
                                    Status
                                </th>

                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-400">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-700 bg-slate-900">

                            @forelse ($suppliers as $supplier)

                                <tr class="hover:bg-slate-800/70">

                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm font-medium text-white">
                                            {{ $supplier->name }}
                                        </div>

                                        @if ($supplier->address)
                                            <div class="mt-1 text-xs text-slate-500">
                                                {{ $supplier->address }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-300">
                                        {{ $supplier->contact_person ?: '—' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-300">
                                        {{ $supplier->phone ?: '—' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-300">
                                        {{ $supplier->email ?: '—' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">
                                        @if ($supplier->is_active)
                                            <span class="inline-flex rounded-full bg-green-900/40 px-3 py-1 text-xs font-medium text-green-400">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-red-900/40 px-3 py-1 text-xs font-medium text-red-400">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">

                                        <a href="{{ route('suppliers.edit', $supplier) }}"
                                           class="mr-3 text-indigo-400 hover:text-indigo-300">
                                            Edit
                                        </a>

                                        <form action="{{ route('suppliers.toggle-status', $supplier) }}"
                                              method="POST"
                                              class="inline">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="{{ $supplier->is_active
                                                        ? 'text-red-400 hover:text-red-300'
                                                        : 'text-green-400 hover:text-green-300' }}">
                                                {{ $supplier->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="text-sm text-slate-400">
                                            No suppliers found.
                                        </div>

                                        <a href="{{ route('suppliers.create') }}"
                                           class="mt-3 inline-block text-sm text-indigo-400 hover:text-indigo-300">
                                            Add your first supplier
                                        </a>
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>
                </div>

                @if ($suppliers->hasPages())
                    <div class="border-t border-slate-700 px-6 py-4">
                        {{ $suppliers->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>