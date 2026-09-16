<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\InventoryItem;
use App\Models\InventoryStockMovement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryStockMovementController extends Controller
{
    public function index(Request $request): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $branches = Branch::where('laboratory_id', $laboratoryId)
            ->orderBy('name')
            ->get();

        $items = InventoryItem::where('laboratory_id', $laboratoryId)
            ->orderBy('name')
            ->get();

        $movements = InventoryStockMovement::with([
                'branch',
                'inventoryItem',
                'recordedBy',
            ])
            ->where('laboratory_id', $laboratoryId)
            ->when($request->branch_id, function ($query) use ($request) {
                $query->where('branch_id', $request->branch_id);
            })
            ->when($request->inventory_item_id, function ($query) use ($request) {
                $query->where('inventory_item_id', $request->inventory_item_id);
            })
            ->when($request->type, function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->from, function ($query) use ($request) {
                $query->whereDate('movement_date', '>=', $request->from);
            })
            ->when($request->to, function ($query) use ($request) {
                $query->whereDate('movement_date', '<=', $request->to);
            })
            ->latest('movement_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view(
            'accounting.inventory.stock-movements.index',
            compact('movements', 'branches', 'items')
        );
    }
}