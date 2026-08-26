<?php



namespace App\Actions\Results;

use App\Models\Result;
use App\Services\ResultStatusService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ReplaceResult
{
    /**
     * Replace an existing laboratory result PDF.
     */
    public function execute(
        Result $result,
        UploadedFile $pdf,
        ?string $remarks = null
    ): Result {
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
                'You are not authorized to replace this result.'
            );
        }

        // Result must have an existing file
        if (! $result->pdf_path) {
            throw new RuntimeException(
                'There is no existing result to replace.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Generate New Filename
        |--------------------------------------------------------------------------
        */

        $item = $result->testRequestItem;

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
        | Replace PDF
        |--------------------------------------------------------------------------
        */

        return DB::transaction(function () use (
            $result,
            $pdf,
            $remarks,
            $user,
            $directory,
            $filename
        ) {

            // Store the new PDF first
            $newPath = Storage::disk('private')->putFileAs(
                $directory,
                $pdf,
                $filename
            );

            if (! $newPath) {
                throw new RuntimeException(
                    'The new result file could not be stored.'
                );
            }

            // Delete the old PDF
            if (
                Storage::disk('private')->exists($result->pdf_path)
            ) {
                Storage::disk('private')->delete($result->pdf_path);
            }

            // Update result record
            $result->update([
                'pdf_path' => $newPath,
                'remarks' => $remarks,
                'uploaded_by' => $user->id,
                'uploaded_at' => Carbon::now(),

                // New file must be verified again
                'verified_by' => null,
                'verified_at' => null,
            ]);

            // The replacement needs verification again
            $this->statusService->markReady(
                $result->testRequestItem
            );

            return $result->fresh();
        });
    }

    public function __construct(
        protected ResultStatusService $statusService
    ) {
    }
}

