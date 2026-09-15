<?php

namespace App\Http\Controllers;

use App\Models\SupplierInvoice;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\ChartOfAccount;
use App\Services\JournalEntryService;

class SupplierPaymentController extends Controller
{

public function __construct(
    protected JournalEntryService $journalEntryService
) {}
    public function create(SupplierInvoice $supplierInvoice): View
    {
        abort_unless(
            $supplierInvoice->laboratory_id === auth()->user()->laboratory_id,
            403
        );

        $supplierInvoice->load('branch');

        return view(
            'accounting.accounts-payable.payment',
            compact('supplierInvoice')
        );
    }

    public function store(
        Request $request,
        SupplierInvoice $supplierInvoice
    ) {
        abort_unless(
            $supplierInvoice->laboratory_id === auth()->user()->laboratory_id,
            403
        );

        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'payment_method' => [
                'required',
                'in:Cash,Transfer,POS,Other',
            ],
            'payment_reference' => [
                'nullable',
                'string',
                'max:255',
            ],
            'paid_at' => [
                'required',
                'date',
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $amount = (float) $validated['amount'];
        $balance = $supplierInvoice->balance();

        if ($balance <= 0) {
            throw ValidationException::withMessages([
                'amount' => 'This supplier invoice has already been fully paid.',
            ]);
        }

        if ($amount > $balance) {
            throw ValidationException::withMessages([
                'amount' => 'Payment cannot be greater than the outstanding balance of ₦'
                    . number_format($balance, 2) . '.',
            ]);
        }

        DB::transaction(function () use (
            $supplierInvoice,
            $validated,
            $amount
        ) {
         $payment =  SupplierPayment::create([
                'supplier_invoice_id' => $supplierInvoice->id,
                'laboratory_id' => $supplierInvoice->laboratory_id,
                'branch_id' => $supplierInvoice->branch_id,
                'amount' => $amount,
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $validated['payment_reference'] ?? null,
                'paid_at' => $validated['paid_at'],
                'paid_by' => auth()->id(),
                'remarks' => $validated['remarks'] ?? null,
            ]);

            $payableAccount = ChartOfAccount::where('laboratory_id', $supplierInvoice->laboratory_id)
    ->where('code', '2100')
    ->firstOrFail();

    $cashOrBankCode = match ($payment->payment_method) {
    'Cash' => '1100',
    'Transfer', 'POS' => '1200',
    default => '1100',
};

$cashOrBankAccount = ChartOfAccount::where('laboratory_id', $supplierInvoice->laboratory_id)
    ->where('code', $cashOrBankCode)
    ->firstOrFail();

    $this->journalEntryService->create([
    'laboratory_id' => $supplierInvoice->laboratory_id,
    'branch_id' => $payment->branch_id,
    'entry_date' => $payment->paid_at->toDateString(),
    'reference' => 'SPAY-' . $payment->id,
    'description' => 'Payment to supplier - ' . $supplierInvoice->supplier_name,
    'source_type' => SupplierPayment::class,
    'source_id' => $payment->id,
    'created_by' => auth()->id(),
    'status' => 'posted',
    'posted_at' => now(),
], [
    [
        'account_id' => $payableAccount->id,
        'debit' => $payment->amount,
        'credit' => 0,
        'description' => 'Supplier payment',
    ],
    [
        'account_id' => $cashOrBankAccount->id,
        'debit' => 0,
        'credit' => $payment->amount,
        'description' => $payment->payment_method . ' payment to supplier',
    ],
]);

            $newAmountPaid = (float) $supplierInvoice->amount_paid + $amount;
            $newBalance = max(
                0,
                (float) $supplierInvoice->amount - $newAmountPaid
            );

            $status = $newBalance <= 0
                ? 'Paid'
                : 'Partially Paid';

            $supplierInvoice->update([
                'amount_paid' => $newAmountPaid,
                'status' => $status,
            ]);
        });

        return redirect()
            ->route('accounting.accounts-payable.show', $supplierInvoice)
            ->with('success', 'Supplier payment recorded successfully.');
    }
}