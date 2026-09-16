<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function index()
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $items = InventoryItem::where('laboratory_id', $laboratoryId)
            ->orderBy('name')
            ->get();

        return view('accounting.inventory.items.index', compact('items'));
    }

    public function create()
    {
        return view('accounting.inventory.items.create');
    }

    public function store(Request $request)
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'category' => [
                'nullable',
                'string',
                'max:255',
            ],
            'unit' => [
                'required',
                'string',
                'max:100',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        InventoryItem::create([
            'laboratory_id' => $laboratoryId,
            'name' => $validated['name'],
            'category' => $validated['category'] ?? null,
            'unit' => $validated['unit'],
            'description' => $validated['description'] ?? null,
            'minimum_stock' => $validated['minimum_stock'],
            'is_active' => true,
        ]);

        return redirect()
            ->route('accounting.inventory.items.index')
            ->with('success', 'Inventory item created successfully.');
    }
}