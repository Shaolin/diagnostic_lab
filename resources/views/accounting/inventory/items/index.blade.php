<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Inventory Items
            </h2>

            <a href="{{ route('accounting.inventory.items.create') }}"
               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">
                Add Item
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 px-4 py-3 bg-green-700/30 border border-green-600 text-green-300 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-slate-800 shadow-sm rounded-lg overflow-hidden">

                <div class="p-6 border-b border-slate-700">
                    <h3 class="text-lg font-semibold text-white">
                        Inventory Items
                    </h3>

                    <p class="mt-1 text-sm text-slate-400">
                        Manage laboratory inventory items and their minimum stock levels.
                    </p>
                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Item
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Category
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Unit
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Minimum Stock
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-700">

                            @forelse ($items as $item)

                                <tr class="hover:bg-slate-700/50">

                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-white">
                                            {{ $item->name }}
                                        </div>

                                        @if ($item->description)
                                            <div class="text-xs text-slate-400 mt-1">
                                                {{ $item->description }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ $item->category ?: '—' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ $item->unit }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ number_format($item->minimum_stock, 2) }}
                                    </td>

                                    <td class="px-6 py-4">
                                        @if ($item->is_active)
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-700/30 text-green-300">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-slate-700 text-slate-400">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                        No inventory items have been added yet.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>