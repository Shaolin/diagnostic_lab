<x-app-layout>

    <div class="p-6">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">
                Stock Movement
            </h1>

            <p class="mt-1 text-sm text-slate-400">
                View inventory receipts, issues, adjustments and transfers.
            </p>
        </div>

        {{-- Filters --}}
        <div class="mb-6 rounded-xl bg-slate-800 p-5 shadow-lg">

            <form method="GET" action="{{ route('accounting.inventory.stock-movements.index') }}">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">

                    {{-- Branch --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-300">
                            Branch
                        </label>

                        <select name="branch_id"
                            class="w-full rounded-lg border-slate-700 bg-slate-900 text-white focus:border-blue-500 focus:ring-blue-500">

                            <option value="">All Branches</option>

                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Inventory Item --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-300">
                            Inventory Item
                        </label>

                        <select name="inventory_item_id"
                            class="w-full rounded-lg border-slate-700 bg-slate-900 text-white focus:border-blue-500 focus:ring-blue-500">

                            <option value="">All Items</option>

                            @foreach($items as $item)
                                <option value="{{ $item->id }}"
                                    {{ request('inventory_item_id') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- Movement Type --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-300">
                            Movement Type
                        </label>

                        <select name="type"
                            class="w-full rounded-lg border-slate-700 bg-slate-900 text-white focus:border-blue-500 focus:ring-blue-500">

                            <option value="">All Types</option>
                            <option value="receipt" {{ request('type') === 'receipt' ? 'selected' : '' }}>
                                Receipt
                            </option>
                            <option value="issue" {{ request('type') === 'issue' ? 'selected' : '' }}>
                                Issue
                            </option>
                            <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>
                                Adjustment
                            </option>
                            <option value="transfer_in" {{ request('type') === 'transfer_in' ? 'selected' : '' }}>
                                Transfer In
                            </option>
                            <option value="transfer_out" {{ request('type') === 'transfer_out' ? 'selected' : '' }}>
                                Transfer Out
                            </option>

                        </select>
                    </div>

                    {{-- From --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-300">
                            From
                        </label>

                        <input type="date"
                            name="from"
                            value="{{ request('from') }}"
                            class="w-full rounded-lg border-slate-700 bg-slate-900 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    {{-- To --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-300">
                            To
                        </label>

                        <input type="date"
                            name="to"
                            value="{{ request('to') }}"
                            class="w-full rounded-lg border-slate-700 bg-slate-900 text-white focus:border-blue-500 focus:ring-blue-500">
                    </div>

                </div>

                <div class="mt-4 flex gap-3">

                    <button type="submit"
                        class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
                        Filter
                    </button>

                    <a href="{{ route('accounting.inventory.stock-movements.index') }}"
                        class="rounded-lg bg-slate-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-slate-600">
                        Clear
                    </a>

                </div>

            </form>

        </div>

        {{-- Movement Table --}}
        <div class="overflow-hidden rounded-xl bg-slate-800 shadow-lg">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-700">

                    <thead class="bg-slate-900">

                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">
                                Date
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">
                                Item
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">
                                Branch
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">
                                Type
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-400">
                                Quantity
                            </th>

                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-400">
                                Unit Cost
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">
                                Reference
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">
                                Description
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-700">

                        @forelse($movements as $movement)

                            <tr class="hover:bg-slate-750">

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-300">
                                    {{ \Carbon\Carbon::parse($movement->movement_date)->format('d M Y') }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-white">
                                    {{ $movement->inventoryItem->name }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-300">
                                    {{ $movement->branch->name }}
                                </td>

                                <td class="px-6 py-4">

                                    @if($movement->type === 'receipt')
                                        <span class="rounded-full bg-green-900/40 px-3 py-1 text-xs font-medium text-green-400">
                                            Receipt
                                        </span>

                                    @elseif($movement->type === 'issue')
                                        <span class="rounded-full bg-red-900/40 px-3 py-1 text-xs font-medium text-red-400">
                                            Issue
                                        </span>

                                    @elseif($movement->type === 'adjustment')
                                        <span class="rounded-full bg-yellow-900/40 px-3 py-1 text-xs font-medium text-yellow-400">
                                            Adjustment
                                        </span>

                                    @elseif($movement->type === 'transfer_in')
                                        <span class="rounded-full bg-blue-900/40 px-3 py-1 text-xs font-medium text-blue-400">
                                            Transfer In
                                        </span>

                                    @elseif($movement->type === 'transfer_out')
                                        <span class="rounded-full bg-purple-900/40 px-3 py-1 text-xs font-medium text-purple-400">
                                            Transfer Out
                                        </span>

                                    @else
                                        <span class="text-slate-400">
                                            {{ ucfirst(str_replace('_', ' ', $movement->type)) }}
                                        </span>
                                    @endif

                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium text-white">
                                    {{ number_format($movement->quantity, 2) }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-slate-300">
                                    ₦{{ number_format($movement->unit_cost, 2) }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-400">
                                    {{ $movement->reference ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-400">
                                    {{ $movement->description ?? '—' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-sm text-slate-400">
                                    No stock movements found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if($movements->hasPages())

                <div class="border-t border-slate-700 px-6 py-4">
                    {{ $movements->links() }}
                </div>

            @endif

        </div>

    </div>

</x-app-layout>