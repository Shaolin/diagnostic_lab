<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Add Branch Stock Item
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-slate-800 shadow-sm rounded-lg p-6">

                <form method="POST"
                      action="{{ route('accounting.inventory.stocks.store') }}">

                    @csrf

                    <div class="space-y-6">

                        {{-- Branch --}}
                        <div>
                            <label for="branch_id"
                                   class="block text-sm font-medium text-slate-300">
                                Branch
                            </label>

                            <select name="branch_id"
                                    id="branch_id"
                                    required
                                    class="mt-1 block w-full bg-slate-700 border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                                <option value="">Select Branch</option>

                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach

                            </select>

                            @error('branch_id')
                                <p class="mt-1 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Inventory Item --}}
                        <div>
                            <label for="inventory_item_id"
                                   class="block text-sm font-medium text-slate-300">
                                Inventory Item
                            </label>

                            <select name="inventory_item_id"
                                    id="inventory_item_id"
                                    required
                                    class="mt-1 block w-full bg-slate-700 border-slate-600 text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

                                <option value="">Select Item</option>

                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('inventory_item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }} ({{ $item->unit }})
                                    </option>
                                @endforeach

                            </select>

                            @error('inventory_item_id')
                                <p class="mt-1 text-sm text-red-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3">

                        <a href="{{ route('accounting.inventory.stocks.index') }}"
                           class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-lg text-sm">
                            Cancel
                        </a>

                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">
                            Create Stock Record
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>