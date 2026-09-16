<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\InventoryItem;
use App\Models\InventoryStock;
use App\Models\InventoryStockMovement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryStockIssueController extends Controller
{
    public function create(): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $branches = Branch::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $items = InventoryItem::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'accounting.inventory.stock-issues.create',
            compact('branches', 'items')
        );
    }

    public function store(Request $request)
{
    $laboratoryId = auth()->user()->laboratory_id;

    $validated = $request->validate([
        'branch_id' => [
            'required',
            'integer',
            'exists:branches,id',
        ],
        'inventory_item_id' => [
            'required',
            'integer',
            'exists:inventory_items,id',
        ],
        'quantity' => [
            'required',
            'numeric',
            'min:0.01',
        ],
        'description' => [
            'nullable',
            'string',
            'max:1000',
        ],
        'movement_date' => [
            'required',
            'date',
        ],
    ]);

    $branch = Branch::where('id', $validated['branch_id'])
        ->where('laboratory_id', $laboratoryId)
        ->where('is_active', true)
        ->firstOrFail();

    $item = InventoryItem::where('id', $validated['inventory_item_id'])
        ->where('laboratory_id', $laboratoryId)
        ->where('is_active', true)
        ->firstOrFail();

    $stock = InventoryStock::where('laboratory_id', $laboratoryId)
        ->where('branch_id', $branch->id)
        ->where('inventory_item_id', $item->id)
        ->first();

    if (!$stock) {
        return back()
            ->withErrors([
                'inventory_item_id' => 'No stock record exists for this item at the selected branch.',
            ])
            ->withInput();
    }

    if ((float) $stock->quantity < (float) $validated['quantity']) {
        return back()
            ->withErrors([
                'quantity' => 'Insufficient stock. Available quantity: ' . $stock->quantity,
            ])
            ->withInput();
    }

    $stock->update([
        'quantity' => (float) $stock->quantity - (float) $validated['quantity'],
    ]);

    InventoryStockMovement::create([
        'laboratory_id' => $laboratoryId,
        'branch_id' => $branch->id,
        'inventory_item_id' => $item->id,
        'inventory_stock_id' => $stock->id,
        'type' => 'issue',
        'quantity' => $validated['quantity'],
        'unit_cost' => $stock->average_cost,
        'reference' => 'ISSUE-' . now()->format('YmdHis'),
        'description' => $validated['description'] ?? 'Inventory stock issued',
        'movement_date' => $validated['movement_date'],
        'recorded_by' => auth()->id(),
    ]);

    return redirect()
        ->route('accounting.inventory.stocks.index')
        ->with('success', 'Inventory stock issued successfully.');
}
}