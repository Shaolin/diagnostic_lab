<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\InventoryItem;
use App\Models\InventoryStock;
use Illuminate\Http\Request;

class InventoryStockController extends Controller
{
    public function index()
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $stocks = InventoryStock::with(['branch', 'inventoryItem'])
            ->where('laboratory_id', $laboratoryId)
            ->orderBy('branch_id')
            ->get();

        return view('accounting.inventory.stocks.index', compact('stocks'));
    }

    public function create()
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

        return view('accounting.inventory.stocks.create', compact(
            'branches',
            'items'
        ));
    }

    public function store(Request $request)
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $validated = $request->validate([
            'branch_id' => [
                'required',
                'exists:branches,id',
            ],
            'inventory_item_id' => [
                'required',
                'exists:inventory_items,id',
            ],
        ]);

        $branch = Branch::where('id', $validated['branch_id'])
            ->where('laboratory_id', $laboratoryId)
            ->firstOrFail();

        $item = InventoryItem::where('id', $validated['inventory_item_id'])
            ->where('laboratory_id', $laboratoryId)
            ->firstOrFail();

        InventoryStock::firstOrCreate(
            [
                'laboratory_id' => $laboratoryId,
                'branch_id' => $branch->id,
                'inventory_item_id' => $item->id,
            ],
            [
                'quantity' => 0,
                'average_cost' => 0,
            ]
        );

        return redirect()
            ->route('accounting.inventory.stocks.index')
            ->with('success', 'Branch stock record created successfully.');
    }
}