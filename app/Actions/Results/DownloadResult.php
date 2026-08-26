<?php

namespace App\Actions\Results;

use App\Models\Result;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DownloadResult
{
    /**
     * Download a laboratory result PDF.
     */
    public function execute(Result $result): BinaryFileResponse
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
                'You are not authorized to download this result.'
            );
        }

        // Result file must exist
        if (
            ! $result->pdf_path ||
            ! Storage::disk('private')->exists($result->pdf_path)
        ) {
            throw new RuntimeException(
                'The result file could not be found.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Download PDF
        |--------------------------------------------------------------------------
        */

        return response()->download(
            Storage::disk('private')->path($result->pdf_path),
            basename($result->pdf_path),
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }
}