<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\TestRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    /**
     * Record a payment against a test request.
     */
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

            return Payment::create([
                'laboratory_id' => $testRequest->laboratory_id,
                'test_request_id' => $testRequest->id,
                'amount' => $amount,
                'payment_method' => $data['payment_method'],
                'payment_reference' => $data['payment_reference'] ?? null,
                'paid_at' => $data['paid_at'] ?? now(),
                'received_by' => $receivedBy,
                'remarks' => $data['remarks'] ?? null,
            ]);
        });
    }
}