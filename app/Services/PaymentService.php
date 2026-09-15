<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\TestRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\ChartOfAccount;
use App\Services\JournalEntryService;

class PaymentService
{
    /**
     * Record a payment against a test request.
     */

     public function __construct(
        protected JournalEntryService $journalEntryService
    ) {
    }
    public function recordPayment(
        TestRequest $testRequest,
        array $data,
        int $receivedBy
    ): Payment {
        return DB::transaction(function () use ($testRequest, $data, $receivedBy) {

            $amount = (float) $data['amount'];

            /*
            |--------------------------------------------------------------------------
            | Validate Payment Amount
            |--------------------------------------------------------------------------
            */

            if ($amount <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'Payment amount must be greater than zero.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Outstanding Balance
            |--------------------------------------------------------------------------
            */

            $balance = $testRequest->balance();

            if ($balance <= 0) {
                throw ValidationException::withMessages([
                    'amount' => 'This test request has already been fully paid.',
                ]);
            }

            if ($amount > $balance) {
                throw ValidationException::withMessages([
                    'amount' => 'Payment cannot be greater than the outstanding balance of ₦'
                        . number_format($balance, 2) . '.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Create Payment
            |--------------------------------------------------------------------------
            */

            $payment = Payment::create([
    'laboratory_id' => $testRequest->laboratory_id,
    'test_request_id' => $testRequest->id,
    'amount' => $amount,
    'payment_method' => $data['payment_method'],
    'payment_reference' => $data['payment_reference'] ?? null,
    'paid_at' => $data['paid_at'] ?? now(),
    'received_by' => $receivedBy,
    'remarks' => $data['remarks'] ?? null,
]);

$cashOrBankAccount = match ($payment->payment_method) {
    'Cash' => '1100',
    'Transfer', 'POS' => '1200',
    default => '1100',
};

$debitAccount = ChartOfAccount::where('laboratory_id', $testRequest->laboratory_id)
    ->where('code', $cashOrBankAccount)
    ->firstOrFail();

$receivableAccount = ChartOfAccount::where('laboratory_id', $testRequest->laboratory_id)
    ->where('code', '1300')
    ->firstOrFail();

$this->journalEntryService->create([
    'laboratory_id' => $testRequest->laboratory_id,
    'branch_id' => $testRequest->branch_id,
    'entry_date' => $payment->paid_at->toDateString(),
    'reference' => 'PAY-' . $payment->id,
    'description' => 'Laboratory service payment',
    'source_type' => Payment::class,
    'source_id' => $payment->id,
    'created_by' => $receivedBy,
    'status' => 'posted',
    'posted_at' => now(),
], [
    [
        'account_id' => $debitAccount->id,
        'debit' => $amount,
        'credit' => 0,
        'description' => $payment->payment_method . ' payment received',
    ],
    [
        'account_id' => $receivableAccount->id,
        'debit' => 0,
        'credit' => $amount,
        'description' => 'Payment received against accounts receivable',
    ],
]);

return $payment;
        });
    }
}