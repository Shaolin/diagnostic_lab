<x-app-layout>
    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">
                    Supplier Invoice
                </h1>

                <p class="mt-1 text-sm text-slate-400">
                    Invoice #{{ $supplierInvoice->invoice_number }}
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('accounting.accounts-payable') }}"
                   class="inline-flex items-center justify-center rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-white hover:bg-slate-600 transition">
                    ← Back
                </a>

                @if($supplierInvoice->balance() > 0)
                   <a href="{{ route('accounting.accounts-payable.payment.create', $supplierInvoice) }}"
   class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
    Record Payment
</a>
                @endif
            </div>
        </div>

        {{-- Supplier & Invoice Information --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- Supplier --}}
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">
                    Supplier Information
                </h2>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400">Supplier</span>
                        <span class="text-white font-medium text-right">
                            {{ $supplierInvoice->supplier_name }}
                        </span>
                    </div>

                    @if($supplierInvoice->supplier_phone)
                        <div class="flex justify-between gap-4">
                            <span class="text-slate-400">Phone</span>
                            <span class="text-white">
                                {{ $supplierInvoice->supplier_phone }}
                            </span>
                        </div>
                    @endif

                    @if($supplierInvoice->supplier_email)
                        <div class="flex justify-between gap-4">
                            <span class="text-slate-400">Email</span>
                            <span class="text-white">
                                {{ $supplierInvoice->supplier_email }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Invoice --}}
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">
                    Invoice Information
                </h2>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400">Invoice Number</span>
                        <span class="text-white font-medium">
                            {{ $supplierInvoice->invoice_number }}
                        </span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400">Invoice Date</span>
                        <span class="text-white">
                            {{ $supplierInvoice->invoice_date->format('d M Y') }}
                        </span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400">Due Date</span>
                        <span class="text-white">
                            {{ $supplierInvoice->due_date?->format('d M Y') ?? '—' }}
                        </span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-slate-400">Branch</span>
                        <span class="text-white">
                            {{ $supplierInvoice->branch?->name ?? 'Head Office / General' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Financial Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <p class="text-sm text-slate-400">Invoice Amount</p>
                <p class="mt-2 text-2xl font-bold text-white">
                    ₦{{ number_format($supplierInvoice->amount, 2) }}
                </p>
            </div>

            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <p class="text-sm text-slate-400">Amount Paid</p>
                <p class="mt-2 text-2xl font-bold text-green-400">
                    ₦{{ number_format($supplierInvoice->amount_paid, 2) }}
                </p>
            </div>

            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <p class="text-sm text-slate-400">Outstanding</p>
                <p class="mt-2 text-2xl font-bold text-red-400">
                    ₦{{ number_format($supplierInvoice->balance(), 2) }}
                </p>
            </div>

        </div>

        {{-- Description --}}
        @if($supplierInvoice->description)
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6 mb-6">
                <h2 class="text-lg font-semibold text-white mb-3">
                    Description
                </h2>

                <p class="text-sm text-slate-300">
                    {{ $supplierInvoice->description }}
                </p>
            </div>
        @endif

        {{-- Payment History --}}
        <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">

            <div class="px-6 py-4 border-b border-slate-700">
                <h2 class="text-lg font-semibold text-white">
                    Payment History
                </h2>
            </div>

            @if($supplierInvoice->payments->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700">

                        <thead class="bg-slate-900/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Date
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Amount
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Method
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Reference
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-400 uppercase">
                                    Paid By
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-700">
                            @foreach($supplierInvoice->payments->sortByDesc('paid_at') as $payment)
                                <tr class="hover:bg-slate-700/30">

                                    <td class="px-6 py-4 text-sm text-slate-300 whitespace-nowrap">
                                        {{ $payment->paid_at->format('d M Y h:i A') }}
                                    </td>

                                    <td class="px-6 py-4 text-sm font-medium text-green-400 whitespace-nowrap">
                                        ₦{{ number_format($payment->amount, 2) }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ $payment->payment_method }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ $payment->payment_reference ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ $payment->paidBy?->name ?? '—' }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            @else
                <div class="px-6 py-10 text-center">
                    <p class="text-sm text-slate-400">
                        No payments have been recorded for this invoice.
                    </p>
                </div>
            @endif

        </div>

    </div>
</x-app-layout>