<x-app-layout>
    <div class="p-6 space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">
                    Fixed Assets
                </h1>
                <p class="text-sm text-slate-400 mt-1">
                    Manage and track laboratory fixed assets.
                </p>
            </div>

        <div class="flex items-center gap-3">
    <a href="{{ route('accounting.fixed-assets.depreciation.create') }}"
       class="inline-flex items-center px-4 py-2.5 rounded-lg bg-amber-600 text-white font-medium hover:bg-amber-500 transition">
        📉 Record Depreciation
    </a>

    <a href="{{ route('accounting.fixed-assets.create') }}"
       class="inline-flex items-center px-4 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-500 transition">
        + Add Fixed Asset
    </a>
</div>
        </div>

        {{-- Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div class="rounded-xl bg-slate-800 border border-slate-700 p-5">
                <p class="text-sm text-slate-400">Total Acquisition Cost</p>
                <p class="mt-2 text-2xl font-bold text-white">
                    ₦{{ number_format($totalCost, 2) }}
                </p>
            </div>

            <div class="rounded-xl bg-slate-800 border border-slate-700 p-5">
                <p class="text-sm text-slate-400">Current Book Value</p>
                <p class="mt-2 text-2xl font-bold text-white">
                    ₦{{ number_format($totalBookValue, 2) }}
                </p>
            </div>

        </div>

        {{-- Filters --}}
        <div class="rounded-xl bg-slate-800 border border-slate-700 p-5">

            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>
                    <label class="block text-sm text-slate-300 mb-1">
                        Branch
                    </label>

                    <select name="branch_id"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white">
                        <option value="">All Branches</option>

                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}"
                                {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-slate-300 mb-1">
                        Status
                    </label>

                    <select name="status"
                            class="w-full rounded-lg bg-slate-900 border-slate-600 text-white">
                        <option value="">All Statuses</option>
                        <option value="active"
                            {{ request('status') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="disposed"
                            {{ request('status') === 'disposed' ? 'selected' : '' }}>
                            Disposed
                        </option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                            class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                        Filter
                    </button>
                </div>

                <div class="flex items-end">
                    <a href="{{ route('accounting.fixed-assets.index') }}"
                       class="w-full text-center rounded-lg bg-slate-700 px-4 py-2.5 text-sm font-semibold text-slate-200 hover:bg-slate-600">
                        Reset
                    </a>
                </div>

            </form>

        </div>

        {{-- Assets Table --}}
        <div class="rounded-xl bg-slate-800 border border-slate-700 overflow-hidden">

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">

                    <thead class="bg-slate-900 text-slate-300">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold">
                                Asset
                            </th>

                            <th class="px-5 py-3 text-left font-semibold">
                                Branch
                            </th>

                            <th class="px-5 py-3 text-left font-semibold">
                                Acquisition Date
                            </th>

                            <th class="px-5 py-3 text-right font-semibold">
                                Cost
                            </th>

                            <th class="px-5 py-3 text-right font-semibold">
                                Book Value
                            </th>

                            <th class="px-5 py-3 text-center font-semibold">
                                Status
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-700">

                        @forelse($assets as $asset)

                            <tr class="hover:bg-slate-750">

                                <td class="px-5 py-4">
                                    <div class="font-medium text-white">
                                        {{ $asset->name }}
                                    </div>

                                    @if($asset->category)
                                        <div class="text-xs text-slate-400 mt-1">
                                            {{ $asset->category }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-slate-300">
                                    {{ $asset->branch?->name ?? 'Head Office' }}
                                </td>

                                <td class="px-5 py-4 text-slate-300">
                                    {{ $asset->acquisition_date->format('d M Y') }}
                                </td>

                                <td class="px-5 py-4 text-right text-white">
                                    ₦{{ number_format($asset->acquisition_cost, 2) }}
                                </td>

                                <td class="px-5 py-4 text-right text-white">
                                    ₦{{ number_format($asset->current_book_value, 2) }}
                                </td>

                                <td class="px-5 py-4 text-center">

                                    @if($asset->status === 'active')

                                        <span class="inline-flex rounded-full bg-green-500/10 px-3 py-1 text-xs font-medium text-green-400">
                                            Active
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-red-500/10 px-3 py-1 text-xs font-medium text-red-400">
                                            {{ ucfirst($asset->status) }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6"
                                    class="px-5 py-10 text-center text-slate-400">
                                    No fixed assets recorded yet.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

            @if($assets->hasPages())
                <div class="border-t border-slate-700 px-5 py-4">
                    {{ $assets->links() }}
                </div>
            @endif

        </div>

    </div>
</x-app-layout>