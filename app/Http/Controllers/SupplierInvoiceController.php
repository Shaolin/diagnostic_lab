<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\SupplierInvoice;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\ChartOfAccount;
use App\Services\JournalEntryService;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;
use App\Models\InventoryItem;
use App\Models\SupplierInvoiceItem;
use App\Models\InventoryStock;
use App\Models\InventoryStockMovement;
class SupplierInvoiceController extends Controller
{

public function __construct(
        protected JournalEntryService $journalEntryService
    ) {}
    public function create(): View
{
    $laboratoryId = auth()->user()->laboratory_id;

    $branches = Branch::where('laboratory_id', $laboratoryId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    $suppliers = Supplier::where('laboratory_id', $laboratoryId)
    ->where('is_active', true)
    ->orderBy('name')
    ->get();

    $inventoryItems = InventoryItem::where('laboratory_id', $laboratoryId)
    ->where('is_active', true)
    ->orderBy('name')
    ->get();

    $expenseAccounts = ChartOfAccount::where('laboratory_id', $laboratoryId)
        ->where('type', 'expense')
        ->where('is_active', true)
        ->orderBy('code')
        ->get();

    return view(
        'accounting.accounts-payable.create',
        compact('branches', 'suppliers', 'expenseAccounts', 'inventoryItems')
    );
}

    public function store(Request $request)
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $validated = $request->validate([
            'branch_id' => [
                'nullable',
                'integer',
                'exists:branches,id',
            ],
            'invoice_number' => [
                'required',
                'string',
                'max:255',
            ],
            'expense_account_id' => [
    'required',
    'integer',
    'exists:chart_of_accounts,id',
],
           'supplier_id' => [
    'required',
    'integer',
    'exists:suppliers,id',
],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'invoice_date' => [
                'required',
                'date',
            ],
            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:invoice_date',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'items' => [
    'required',
    'array',
    'min:1',
],

'items.*.inventory_item_id' => [
    'required',
    'integer',
    'exists:inventory_items,id',
],

'items.*.quantity' => [
    'required',
    'numeric',
    'min:0.01',
],

'items.*.unit_cost' => [
    'required',
    'numeric',
    'min:0',
],
        ]);

        // Make sure the selected branch belongs to this laboratory.
        if (!empty($validated['branch_id'])) {
            $branchExists = Branch::where('id', $validated['branch_id'])
                ->where('laboratory_id', $laboratoryId)
                ->exists();

            if (!$branchExists) {
                return back()
                    ->withErrors(['branch_id' => 'Invalid branch selected.'])
                    ->withInput();
            }
        }

        $supplier = Supplier::where('id', $validated['supplier_id'])
    ->where('laboratory_id', $laboratoryId)
    ->where('is_active', true)
    ->first();

if (!$supplier) {
    return back()
        ->withErrors(['supplier_id' => 'Invalid supplier selected.'])
        ->withInput();
}

        $expenseAccount = ChartOfAccount::where('id', $validated['expense_account_id'])
    ->where('laboratory_id', $laboratoryId)
    ->where('type', 'expense')
    ->where('is_active', true)
    ->first();

if (!$expenseAccount) {
    return back()
        ->withErrors(['expense_account_id' => 'Invalid expense account selected.'])
        ->withInput();
}

   $invoice = DB::transaction(function () use (
    $validated,
    $laboratoryId,
      $supplier
) {
    $invoice = SupplierInvoice::create([
        'laboratory_id' => $laboratoryId,
        'branch_id' => $validated['branch_id'] ?? null,
        'expense_account_id' => $validated['expense_account_id'],
        'invoice_number' => $validated['invoice_number'],
        'supplier_id' => $supplier->id,
'supplier_name' => $supplier->name,
'supplier_phone' => $supplier->phone,
'supplier_email' => $supplier->email,
        'amount' => $validated['amount'],
        'amount_paid' => 0,
        'invoice_date' => $validated['invoice_date'],
        'due_date' => $validated['due_date'] ?? null,
        'description' => $validated['description'] ?? null,
        'status' => 'Unpaid',
        'created_by' => auth()->id(),
    ]);

   foreach ($validated['items'] as $item) {

    $invoiceItem = SupplierInvoiceItem::create([
        'supplier_invoice_id' => $invoice->id,
        'laboratory_id' => $laboratoryId,
        'branch_id' => $invoice->branch_id,
        'inventory_item_id' => $item['inventory_item_id'],
        'quantity' => $item['quantity'],
        'unit_cost' => $item['unit_cost'],
        'total_amount' => $item['quantity'] * $item['unit_cost'],
    ]);

    $stock = InventoryStock::firstOrCreate(
        [
            'laboratory_id' => $laboratoryId,
            'branch_id' => $invoice->branch_id,
            'inventory_item_id' => $item['inventory_item_id'],
        ],
        [
            'quantity' => 0,
            'average_cost' => 0,
        ]
    );

    $oldQuantity = (float) $stock->quantity;
    $oldAverageCost = (float) $stock->average_cost;

    $newQuantity = $oldQuantity + (float) $item['quantity'];

    if ($newQuantity > 0) {
        $newAverageCost =
            (($oldQuantity * $oldAverageCost) +
            ((float) $item['quantity'] * (float) $item['unit_cost']))
            / $newQuantity;
    } else {
        $newAverageCost = 0;
    }

    $stock->update([
        'quantity' => $newQuantity,
        'average_cost' => $newAverageCost,
    ]);

    InventoryStockMovement::create([
        'laboratory_id' => $laboratoryId,
        'branch_id' => $invoice->branch_id,
        'inventory_item_id' => $item['inventory_item_id'],
        'inventory_stock_id' => $stock->id,
        'type' => 'receipt',
        'quantity' => $item['quantity'],
        'unit_cost' => $item['unit_cost'],
        'reference' => $invoice->invoice_number,
        'description' => 'Stock received from supplier invoice',
        'movement_date' => $invoice->invoice_date,
        'recorded_by' => auth()->id(),
    ]);
}

    $payableAccount = ChartOfAccount::where('laboratory_id', $laboratoryId)
        ->where('code', '2100')
        ->firstOrFail();

    $this->journalEntryService->create([
        'laboratory_id' => $laboratoryId,
        'branch_id' => $invoice->branch_id,
        'entry_date' => $invoice->invoice_date->toDateString(),
        'reference' => 'SUP-' . $invoice->id,
        'description' => 'Supplier invoice - ' . $invoice->supplier_name,
        'source_type' => SupplierInvoice::class,
        'source_id' => $invoice->id,
        'created_by' => auth()->id(),
        'status' => 'posted',
        'posted_at' => now(),
    ], [
        [
    'account_id' => ChartOfAccount::where('laboratory_id', $laboratoryId)
        ->where('code', '1400')
        ->firstOrFail()
        ->id,
    'debit' => $invoice->amount,
    'credit' => 0,
    'description' => 'Inventory purchased',
],
        [
            'account_id' => $payableAccount->id,
            'debit' => 0,
            'credit' => $invoice->amount,
            'description' => 'Amount owed to supplier',
        ],
    ]);

    return $invoice;
});

        return redirect()
            ->route('accounting.accounts-payable')
            ->with('success', 'Supplier invoice created successfully.');
    }

    public function show(SupplierInvoice $supplierInvoice): View
{
    abort_unless(
        $supplierInvoice->laboratory_id === auth()->user()->laboratory_id,
        403
    );

    $supplierInvoice->load([
        'branch',
        'creator',
        'payments.paidBy',
    ]);

    return view(
        'accounting.accounts-payable.show',
        compact('supplierInvoice')
    );
}
}