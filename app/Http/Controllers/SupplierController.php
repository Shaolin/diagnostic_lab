<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index()
    {
        $suppliers = Supplier::where('laboratory_id', auth()->user()->laboratory_id)
            ->latest()
            ->paginate(15);

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $validated['laboratory_id'] = auth()->user()->laboratory_id;
        $validated['is_active'] = true;

        Supplier::create($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier)
    {
        $this->authorizeSupplier($supplier);

        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $this->authorizeSupplier($supplier);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Activate or deactivate a supplier.
     */
    public function toggleStatus(Supplier $supplier)
    {
        $this->authorizeSupplier($supplier);

        $supplier->update([
            'is_active' => !$supplier->is_active,
        ]);

        return redirect()
            ->route('suppliers.index')
            ->with(
                'success',
                $supplier->is_active
                    ? 'Supplier activated successfully.'
                    : 'Supplier deactivated successfully.'
            );
    }

    /**
     * Ensure the supplier belongs to the current laboratory.
     */
    private function authorizeSupplier(Supplier $supplier)
    {
        abort_unless(
            $supplier->laboratory_id === auth()->user()->laboratory_id,
            403
        );
    }
}