<x-app-layout>
    <div class="min-h-screen bg-slate-900 py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
               <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white">
            Accounts Payable
        </h1>

        <p class="mt-1 text-sm text-slate-400">
            Track outstanding amounts owed to suppliers.
        </p>
    </div>

    <a href="{{ route('accounting.accounts-payable.create') }}"
       class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition">
        <span>＋</span>
        Add Supplier Invoice
    </a>
</div>


@if(session('success'))
    <div class="mb-6 rounded-lg bg-green-500/10 border border-green-500/30 px-4 py-3 text-sm text-green-400">
        {{ session('success') }}
    </div>
@endif

            {{-- Summary Cards --}}
            <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">
                    <p class="text-sm text-slate-400">Total Payable</p>
                    <p class="mt-2 text-2xl font-bold text-white">
                        ₦{{ number_format($totalPayable, 2) }}
                    </p>
                </div>

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">
                    <p class="text-sm text-slate-400">Total Paid</p>
                    <p class="mt-2 text-2xl font-bold text-white">
                        ₦{{ number_format($totalPaid, 2) }}
                    </p>
                </div>

                <div class="rounded-xl border border-slate-700 bg-slate-800 p-5">
                    <p class="text-sm text-slate-400">Outstanding</p>
                    <p class="mt-2 text-2xl font-bold text-red-400">
                        ₦{{ number_format($totalOutstanding, 2) }}
                    </p>
                </div>

            </div>

            {{-- Filters --}}
            <div class="mb-6 rounded-xl border border-slate-700 bg-slate-800 p-5">

                <form method="GET"
                      action="{{ route('accounting.accounts-payable') }}"
                      class="grid grid-cols-1 gap-4 md:grid-cols-4">

                    {{-- Supplier --}}
                    <div>
                        <label class="mb-1 block text-sm text-slate-300">
                            Supplier
                        </label>

                        <input type="text"
                               name="supplier"
                               value="{{ request('supplier') }}"
                               placeholder="Supplier or invoice number"
                               class="w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-white placeholder-slate-500">
                    </div>

                    {{-- Branch --}}
                    <div>
                        <label class="mb-1 block text-sm text-slate-300">
                            Branch
                        </label>

                        <select name="branch_id"
                                class="w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-white">
                            <option value="">All Branches</option>

                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    @selected(request('branch_id') == $branch->id)>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- From Date --}}
                    <div>
                        <label class="mb-1 block text-sm text-slate-300">
                            From Date
                        </label>

                        <input type="date"
                               name="from"
                               value="{{ request('from') }}"
                               class="w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-white">
                    </div>

                    {{-- To Date --}}
                    <div>
                        <label class="mb-1 block text-sm text-slate-300">
                            To Date
                        </label>

                        <input type="date"
                               name="to"
                               value="{{ request('to') }}"
                               class="w-full rounded-lg border border-slate-600 bg-slate-900 px-3 py-2 text-white">
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-end gap-2 md:col-span-4">

                        <button type="submit"
                                class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                            Filter
                        </button>

                        <a href="{{ route('accounting.accounts-payable') }}"
                           class="rounded-lg border border-slate-600 px-5 py-2 text-sm font-medium text-slate-300 hover:bg-slate-700">
                            Reset
                        </a>

                    </div>

                </form>

            </div>

            {{-- Payables Table --}}
            <div class="overflow-hidden rounded-xl border border-slate-700 bg-slate-800">

                <div class="overflow-x-auto">

                    <table class="min-w-full text-sm">

                        <thead class="bg-slate-900 text-left text-slate-400">
                            <tr>
                                <th class="px-4 py-3">Invoice</th>
                                <th class="px-4 py-3">Supplier</th>
                                <th class="px-4 py-3">Branch</th>
                                <th class="px-4 py-3">Invoice Date</th>
                                <th class="px-4 py-3 text-right">Amount</th>
                                <th class="px-4 py-3 text-right">Paid</th>
                                <th class="px-4 py-3 text-right">Outstanding</th>
                                 <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                        Action
                                    </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-700">

                            @forelse($invoices as $invoice)

                                <tr class="hover:bg-slate-700/40">

                                    <td class="px-4 py-3 font-medium text-white">
                                        {{ $invoice->invoice_number }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-300">
                                        {{ $invoice->supplier_name }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-300">
                                        {{ $invoice->branch->name ?? 'Head Office' }}
                                    </td>

                                    <td class="px-4 py-3 text-slate-300">
                                        {{ $invoice->invoice_date?->format('d M Y') }}
                                    </td>

                                    <td class="px-4 py-3 text-right text-slate-300">
                                        ₦{{ number_format($invoice->amount, 2) }}
                                    </td>

                                    <td class="px-4 py-3 text-right text-slate-300">
                                        ₦{{ number_format($invoice->amount_paid, 2) }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-semibold text-red-400">
                                        ₦{{ number_format($invoice->balance(), 2) }}
                                    </td>
                                   <td class="px-6 py-4 whitespace-nowrap">
    <a href="{{ route('accounting.accounts-payable.show', $invoice) }}"
       class="text-blue-400 hover:text-blue-300 font-medium">
        View
    </a>
</td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8"
                                        class="px-4 py-8 text-center text-slate-400">
                                        No outstanding payables found.
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