<?php

namespace App\Actions\Results;

use App\Models\Result;
use App\Models\TestRequest;
use App\Models\TestRequestItem;
use App\Services\ResultStatusService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class UploadResult
{
    public function __construct(
        protected ResultStatusService $statusService
    ) {
    }

    /**
     * Upload a laboratory result PDF.
     */
    public function execute(
        TestRequestItem $item,
        UploadedFile $pdf,
        ?string $remarks = null
    ): Result {
        $user = Auth::user();

        // Load required relationships
        $item->loadMissing([
            'testRequest',
            'testType',
            'result',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Business Rules
        |--------------------------------------------------------------------------
        */

        // Same laboratory
        if (
            $item->testRequest->laboratory_id !== $user->laboratory_id
        ) {
            throw new RuntimeException(
                'You are not authorized to upload results for this laboratory.'
            );
        }

        // Sample must be collected
        if (! $item->isSampleCollected()) {
            throw new RuntimeException(
                'The sample has not yet been collected.'
            );
        }

        // Test must be completed
        if (! $item->isCompleted()) {
            throw new RuntimeException(
                'The laboratory test has not been completed.'
            );
        }

        // Only one result allowed
        if ($item->result()->exists()) {
            throw new RuntimeException(
                'A result has already been uploaded for this test.'
            );
        }

        return DB::transaction(function () use (
            $item,
            $pdf,
            $remarks,
            $user
        ) {

            /*
            |--------------------------------------------------------------------------
            | Generate filename
            |--------------------------------------------------------------------------
            */

            $directory = 'results/'
                . now()->format('Y')
                . '/'
                . now()->format('m');

            $filename =
                $item->testRequest->tracking_code
                . '-'
                . Str::slug($item->test_name)
                . '-'
                . now()->format('His')
                . '.pdf';

            /*
            |--------------------------------------------------------------------------
            | Store PDF
            |--------------------------------------------------------------------------
            */

            $path = Storage::disk('private')->putFileAs(
                $directory,
                $pdf,
                $filename
            );

            /*
            |--------------------------------------------------------------------------
            | Create Result
            |--------------------------------------------------------------------------
            */

            $result = Result::create([
                'test_request_item_id' => $item->id,
                'uploaded_by' => $user->id,
                'verified_by' => null,
                'pdf_path' => $path,
                'remarks' => $remarks,
                'uploaded_at' => Carbon::now(),
                'verified_at' => null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Workflow
            |--------------------------------------------------------------------------
            */

            $this->statusService->markReady($item);

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            $item->testRequest->update([
                'updated_by' => $user->id,
            ]);

            return $result;
        });
    }
}