<?php

namespace App\Actions\Results;

use App\Models\Result;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class VerifyResult
{
    /**
     * Verify a laboratory result.
     */
    public function execute(Result $result): Result
    {
        $user = Auth::user();

        // Load required relationships
        $result->loadMissing([
            'testRequestItem.testRequest',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Business Rules
        |--------------------------------------------------------------------------
        */

        // Same laboratory
        if (
            $result->testRequestItem->testRequest->laboratory_id
            !== $user->laboratory_id
        ) {
            throw new RuntimeException(
                'You are not authorized to verify this result.'
            );
        }

        // Result must have a PDF
        if (! $result->pdf_path) {
            throw new RuntimeException(
                'There is no result file to verify.'
            );
        }

        // Result must not already be verified
        if ($result->verified_at) {
            throw new RuntimeException(
                'This result has already been verified.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Result
        |--------------------------------------------------------------------------
        */

        return DB::transaction(function () use ($result, $user) {

            $result->update([
                'verified_by' => $user->id,
                'verified_at' => Carbon::now(),
            ]);

            return $result->fresh();
        });
    }
}