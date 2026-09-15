<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\TestRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountsReceivableController extends Controller
{
    public function index(Request $request): View
{
    $laboratoryId = auth()->user()->laboratory_id;

    $branches = Branch::where('laboratory_id', $laboratoryId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    $query = TestRequest::query()
        ->with(['patient', 'branch', 'payments'])
        ->where('laboratory_id', $laboratoryId);

    // Branch filter
    if ($request->filled('branch_id')) {
        $query->where('branch_id', $request->integer('branch_id'));
    }

    // Patient search
    if ($request->filled('patient')) {
        $search = $request->input('patient');

        $query->whereHas('patient', function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('other_names', 'like', "%{$search}%")
                ->orWhere('patient_number', 'like', "%{$search}%");
        });
    }

    // Date filter
    if ($request->filled('from')) {
        $query->whereDate('created_at', '>=', $request->input('from'));
    }

    if ($request->filled('to')) {
        $query->whereDate('created_at', '<=', $request->input('to'));
    }

    $testRequests = $query
        ->latest()
        ->get()
        ->filter(fn ($testRequest) => $testRequest->balance() > 0);

    $totalReceivable = $testRequests->sum('total_amount');
    $totalPaid = $testRequests->sum(
        fn ($testRequest) => $testRequest->totalPaid()
    );
    $totalOutstanding = $testRequests->sum(
        fn ($testRequest) => $testRequest->balance()
    );

    return view('accounting.accounts-receivable.index', compact(
        'testRequests',
        'branches',
        'totalReceivable',
        'totalPaid',
        'totalOutstanding'
    ));
}

      public function show(TestRequest $testRequest): View
{
    abort_unless(
        $testRequest->laboratory_id === auth()->user()->laboratory_id,
        403
    );

    $testRequest->load([
        'patient',
        'branch',
        'items',
        'payments.receivedBy',
    ]);

    return view('accounting.accounts-receivable.show', compact('testRequest'));
}
}