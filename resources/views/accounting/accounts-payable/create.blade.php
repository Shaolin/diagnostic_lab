<x-app-layout>
    <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white">
                    Add Supplier Invoice
                </h1>

                <p class="text-sm text-slate-400 mt-1">
                    Record a bill received from a supplier.
                </p>
            </div>

            <a href="{{ route('accounting.accounts-payable') }}"
               class="inline-flex items-center justify-center rounded-lg bg-slate-700 px-4 py-2 text-sm font-medium text-white hover:bg-slate-600 transition">
                ← Back to Accounts Payable
            </a>
        </div>

        {{-- Form --}}
        <div class="bg-slate-800 rounded-xl border border-slate-700 shadow-lg">
            <form method="POST"
                  action="{{ route('accounting.accounts-payable.store') }}"
                  class="p-6 space-y-6">

                @csrf

                {{-- Supplier Information --}}
                <div>
                    <h2 class="text-lg font-semibold text-white mb-4">
                        Supplier Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                      {{-- Supplier --}}
<div>
    <label for="supplier_id"
           class="block text-sm font-medium text-slate-300 mb-1">
        Supplier <span class="text-red-400">*</span>
    </label>

    <select name="supplier_id"
            id="supplier_id"
            required
            class="w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

        <option value="">Select supplier</option>

        @foreach($suppliers as $supplier)
            <option value="{{ $supplier->id }}"
                {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                {{ $supplier->name }}
            </option>
        @endforeach

    </select>

    @error('supplier_id')
        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
    @enderror
</div>

                        {{-- Invoice Number --}}
                        <div>
                            <label for="invoice_number"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Invoice Number <span class="text-red-400">*</span>
                            </label>

                            <input type="text"
                                   name="invoice_number"
                                   id="invoice_number"
                                   value="{{ old('invoice_number') }}"
                                   required
                                   class="w-full rounded-lg bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500">

                            @error('invoice_number')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                       

                       

                    </div>
                </div>

                <hr class="border-slate-700">

                {{-- Invoice Details --}}
                <div>
                    <h2 class="text-lg font-semibold text-white mb-4">
                        Invoice Details
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Branch --}}
                        <div>
                            <label for="branch_id"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Branch
                            </label>

                            <select name="branch_id"
                                    id="branch_id"
                                    class="w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

                                <option value="" class="bg-slate-700">
                                    Head Office / General
                                </option>

                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_id') == $branch->id ? 'selected' : '' }}
                                        class="bg-slate-700">
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('branch_id')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
    <label for="expense_account_id" class="block text-sm font-medium text-slate-300 mb-2">
        Expense Account
    </label>

    <select
        name="expense_account_id"
        id="expense_account_id"
        required
        class="w-full rounded-lg border border-slate-700 bg-slate-800 px-4 py-2.5 text-white focus:border-blue-500 focus:ring-blue-500"
    >
        <option value="">Select expense account</option>

        @foreach($expenseAccounts as $account)
            <option value="{{ $account->id }}"
                {{ old('expense_account_id') == $account->id ? 'selected' : '' }}>
                {{ $account->code }} - {{ $account->name }}
            </option>
        @endforeach
    </select>

    @error('expense_account_id')
        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
    @enderror
</div>

                        {{-- Amount --}}
                        <div>
                            <label for="amount"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Invoice Amount <span class="text-red-400">*</span>
                            </label>


                                   <input type="number"
       name="amount"
       id="invoice-amount"
       step="0.01"
       min="0"
       readonly
       class="w-full rounded-lg bg-slate-700 border-slate-600 text-white">

                            @error('amount')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Invoice Date --}}
                        <div>
                            <label for="invoice_date"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Invoice Date <span class="text-red-400">*</span>
                            </label>

                            <input type="date"
                                   name="invoice_date"
                                   id="invoice_date"
                                   value="{{ old('invoice_date', now()->format('Y-m-d')) }}"
                                   required
                                   class="w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

                            @error('invoice_date')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Due Date --}}
                        <div>
                            <label for="due_date"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Due Date
                            </label>

                            <input type="date"
                                   name="due_date"
                                   id="due_date"
                                   value="{{ old('due_date') }}"
                                   class="w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">

                            @error('due_date')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <label for="description"
                                   class="block text-sm font-medium text-slate-300 mb-1">
                                Description
                            </label>

                            <textarea name="description"
                                      id="description"
                                      rows="3"
                                      class="w-full rounded-lg bg-slate-700 border-slate-600 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="e.g. Laboratory reagents supplied">{{ old('description') }}</textarea>

                            @error('description')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                    {{-- Invoice Items --}}
<div class="mt-6">
    <h3 class="text-lg font-semibold text-white mb-3">
        Invoice Items
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-slate-300">
            <thead class="bg-slate-700 text-slate-200">
                <tr>
                    <th class="px-4 py-3">Item</th>
                    <th class="px-4 py-3">Quantity</th>
                    <th class="px-4 py-3">Unit Cost</th>
                    <th class="px-4 py-3">Total</th>
                </tr>
            </thead>
<tbody id="invoice-items">
    <tr class="border-b border-slate-700 invoice-item-row">
        <td class="px-4 py-3">
            <select name="items[0][inventory_item_id]"
                    class="w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">
                <option value="">Select item</option>

                @foreach($inventoryItems as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </td>

        <td class="px-4 py-3">
            <input type="number"
                   name="items[0][quantity]"
                   step="0.01"
                   min="0"
                   class="item-quantity w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">
        </td>

        <td class="px-4 py-3">
            <input type="number"
                   name="items[0][unit_cost]"
                   step="0.01"
                   min="0"
                   class="item-unit-cost w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">
        </td>

        <td class="px-4 py-3 text-white">
          
             {{-- <span id="item-total-0">₦0.00</span> --}}
              <span class="item-total">₦0.00</span>
        </td>

        <td class="px-4 py-3">
            <button type="button"
                    class="remove-item text-red-400 hover:text-red-300">
                Remove
            </button>
        </td>
       
    </tr>
</tbody>
              
        </table>

        <div class="mt-4 flex justify-end">
    <div class="text-right">
        <p class="text-sm text-slate-400">Invoice Total</p>
        <p id="invoice-total-display"
           class="text-2xl font-bold text-white">
            ₦0.00
        </p>
    </div>
</div>
        <div class="mt-3">
    <button type="button"
            id="add-item"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
        + Add Item
    </button>
</div>
    </div>
</div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-4 border-t border-slate-700">

                    <a href="{{ route('accounting.accounts-payable') }}"
                       class="inline-flex justify-center items-center rounded-lg bg-slate-700 px-5 py-2.5 text-sm font-medium text-slate-200 hover:bg-slate-600 transition">
                        Cancel
                    </a>

                    <button type="submit"
                            class="inline-flex justify-center items-center rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition">
                        Save Supplier Invoice
                    </button>

                </div>

            </form>
        </div>

    </div>

   <script>
    let itemIndex = 1;

    const itemsContainer = document.getElementById('invoice-items');
    const addItemButton = document.getElementById('add-item');

    function calculateItemTotal(row) {
        const quantity = parseFloat(
            row.querySelector('.item-quantity').value
        ) || 0;

        const unitCost = parseFloat(
            row.querySelector('.item-unit-cost').value
        ) || 0;

        const total = quantity * unitCost;

        row.querySelector('.item-total').textContent =
            '₦' + total.toLocaleString('en-NG', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
    }

    function attachRowEvents(row) {
        row.querySelector('.item-quantity')
            .addEventListener('input', () => calculateItemTotal(row));

        row.querySelector('.item-unit-cost')
            .addEventListener('input', () => calculateItemTotal(row));

        row.querySelector('.remove-item')
            .addEventListener('click', () => {
                const rows = itemsContainer.querySelectorAll('.invoice-item-row');

                if (rows.length > 1) {
                    row.remove();
                }
            });
    }

    document.querySelectorAll('.invoice-item-row').forEach(row => {
        attachRowEvents(row);
    });

   function calculateInvoiceTotal() {
    let invoiceTotal = 0;

    document.querySelectorAll('.invoice-item-row').forEach(row => {
        const quantityInput = row.querySelector('.item-quantity');
        const unitCostInput = row.querySelector('.item-unit-cost');

        const quantity = parseFloat(quantityInput.value) || 0;
        const unitCost = parseFloat(unitCostInput.value) || 0;

        invoiceTotal += quantity * unitCost;
    });

    // Display total below the table
    const totalDisplay = document.getElementById('invoice-total-display');

    if (totalDisplay) {
        totalDisplay.textContent =
            '₦' + invoiceTotal.toLocaleString('en-NG', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
    }

    // Put total into the amount field
    const amountInput = document.getElementById('invoice-amount');

    if (amountInput) {
        amountInput.value = invoiceTotal.toFixed(2);
    }
}


function calculateItemTotal(row) {
    const quantity = parseFloat(
        row.querySelector('.item-quantity').value
    ) || 0;

    const unitCost = parseFloat(
        row.querySelector('.item-unit-cost').value
    ) || 0;

    const total = quantity * unitCost;

    row.querySelector('.item-total').textContent =
        '₦' + total.toLocaleString('en-NG', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    // Recalculate invoice total
    calculateInvoiceTotal();
}

    addItemButton.addEventListener('click', () => {
        const row = document.createElement('tr');

        row.className = 'border-b border-slate-700 invoice-item-row';

        row.innerHTML = `
            <td class="px-4 py-3">
                <select name="items[${itemIndex}][inventory_item_id]"
                        class="w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select item</option>

                    @foreach($inventoryItems as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </td>

            <td class="px-4 py-3">
                <input type="number"
                       name="items[${itemIndex}][quantity]"
                       step="0.01"
                       min="0"
                       class="item-quantity w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">
            </td>

            <td class="px-4 py-3">
                <input type="number"
                       name="items[${itemIndex}][unit_cost]"
                       step="0.01"
                       min="0"
                       class="item-unit-cost w-full rounded-lg bg-slate-700 border-slate-600 text-white focus:border-blue-500 focus:ring-blue-500">
            </td>

            <td class="px-4 py-3 text-white">
                <span class="item-total">₦0.00</span>
            </td>

            <td class="px-4 py-3">
                <button type="button"
                        class="remove-item text-red-400 hover:text-red-300">
                    Remove
                </button>
            </td>
        `;

        itemsContainer.appendChild(row);

        attachRowEvents(row);

        itemIndex++;
    });
</script>
</x-app-layout>