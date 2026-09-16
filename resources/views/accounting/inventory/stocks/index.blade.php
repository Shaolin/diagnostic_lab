<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Branch Stock
            </h2>

            <a href="{{ route('accounting.inventory.stocks.create') }}"
               class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">
                Add Stock Item
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
                        Branch Stock
                    </h3>

                    <p class="mt-1 text-sm text-slate-400">
                        View inventory items assigned to each branch.
                    </p>
                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Branch
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Item
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Quantity
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Average Cost
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Stock Value
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-700">

                            @forelse ($stocks as $stock)

                                <tr class="hover:bg-slate-700/50">

                                    <td class="px-6 py-4 text-sm text-white">
                                        {{ $stock->branch->name }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-white">
                                            {{ $stock->inventoryItem->name }}
                                        </div>

                                        <div class="text-xs text-slate-400">
                                            {{ $stock->inventoryItem->unit }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ number_format($stock->quantity, 2) }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        ₦{{ number_format($stock->average_cost, 2) }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        ₦{{ number_format($stock->quantity * $stock->average_cost, 2) }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-10 text-center text-slate-400">
                                        No branch stock records have been created yet.
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