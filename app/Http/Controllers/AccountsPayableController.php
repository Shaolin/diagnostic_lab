<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\SupplierInvoice;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountsPayableController extends Controller
{
    public function index(Request $request): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $branches = Branch::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $query = SupplierInvoice::query()
            ->with(['branch', 'payments'])
            ->where('laboratory_id', $laboratoryId);

        // Branch filter
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->integer('branch_id'));
        }

        // Supplier search
        if ($request->filled('supplier')) {
            $search = $request->input('supplier');

            $query->where(function ($q) use ($search) {
                $q->where('supplier_name', 'like', "%{$search}%")
                    ->orWhere('invoice_number', 'like', "%{$search}%")
                    ->orWhere('supplier_phone', 'like', "%{$search}%");
            });
        }

        // Date filter
        if ($request->filled('from')) {
            $query->whereDate('invoice_date', '>=', $request->input('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('invoice_date', '<=', $request->input('to'));
        }

        $invoices = $query
            ->latest('invoice_date')
            ->get()
            ->filter(fn ($invoice) => $invoice->balance() > 0);

        $totalPayable = $invoices->sum('amount');
        $totalPaid = $invoices->sum('amount_paid');
        $totalOutstanding = $invoices->sum(
            fn ($invoice) => $invoice->balance()
        );

        return view('accounting.accounts-payable.index', compact(
            'invoices',
            'branches',
            'totalPayable',
            'totalPaid',
            'totalOutstanding'
        ));
    }
}