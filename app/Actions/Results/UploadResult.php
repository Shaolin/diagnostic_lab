<?php
namespace App\Actions\Results;

use App\Models\MedicalResultFile;
use App\Models\Result;
use App\Models\TestRequestItem;
use App\Services\ResultStatusService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class UploadResult
{
    public function __construct(
        protected ResultStatusService $statusService
    ) {
    }

    /**
     * Upload a laboratory result PDF and optional medical files.
     */
    public function execute(
        TestRequestItem $item,
        UploadedFile $pdf,
        ?string $remarks = null,
        array $medicalFiles = [],
        ?string $fileType = null,
        ?string $description = null
    ): Result {
        $user = Auth::user();

        $item->loadMissing([
            'testRequest',
            'testType',
            'result',
        ]);

        if (! $user || $item->testRequest->laboratory_id !== $user->laboratory_id) {
            throw new RuntimeException(
                'You are not authorized to upload results for this laboratory.'
            );
        }

        if (! $item->isSampleCollected()) {
            throw new RuntimeException('The sample has not yet been collected.');
        }

        if (! $item->isCompleted()) {
            throw new RuntimeException('The laboratory test has not been completed.');
        }

        if ($item->result()->exists()) {
            throw new RuntimeException(
                'A result has already been uploaded for this test.'
            );
        }

        if (! empty($medicalFiles)) {
            $allowedTypes = ['X-ray', 'EEG', 'Ultrasound', 'Other'];

            if (! in_array($fileType, $allowedTypes, true)) {
                throw new RuntimeException('Invalid medical file category.');
            }
        }

        $storedPaths = [];

        try {
            return DB::transaction(function () use (
                $item,
                $pdf,
                $remarks,
                $medicalFiles,
                $fileType,
                $description,
                $user,
                &$storedPaths
            ) {
                $directory = 'results/'
                    . now()->format('Y')
                    . '/'
                    . now()->format('m');

                $filename = $item->testRequest->tracking_code
                    . '-'
                    . Str::slug($item->test_name)
                    . '-'
                    . now()->format('His')
                    . '.pdf';

                /*
                 * Store the required PDF.
                 */
                $stream = fopen($pdf->getPathname(), 'rb');

                if ($stream === false) {
                    throw new RuntimeException(
                        'Unable to read the uploaded result file.'
                    );
                }

                try {
                    $path = $directory . '/' . $filename;

                    $stored = Storage::disk('private')->put($path, $stream);

                    if (! $stored) {
                        throw new RuntimeException(
                            'Unable to store the uploaded result file.'
                        );
                    }

                    $storedPaths[] = $path;
                } finally {
                    fclose($stream);
                }

                /*
                 * Create the PDF result record.
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
                 * Store optional medical images and films.
                 */
 foreach ($medicalFiles as $file) {

    if (! $file instanceof UploadedFile || ! $file->isValid()) {
        throw new RuntimeException(
            'One of the uploaded medical files is invalid.'
        );
    }

    $medicalDirectory = 'medical-results/'
        . now()->format('Y')
        . '/'
        . now()->format('m');

    // Generate a unique filename.
    $extension = strtolower($file->getClientOriginalExtension());

    $filename = Str::uuid() . '.' . $extension;

    $medicalPath = $medicalDirectory . '/' . $filename;

    // Open the actual temporary upload file.
    $stream = fopen($file->getPathname(), 'rb');

    if ($stream === false) {
        throw new RuntimeException(
            'Unable to read the uploaded medical file: '
            . $file->getClientOriginalName()
        );
    }

    try {
        $stored = Storage::disk('private')->put(
            $medicalPath,
            $stream
        );

        if (! $stored) {
            throw new RuntimeException(
                'Unable to store the medical result file.'
            );
        }

        $storedPaths[] = $medicalPath;

    } finally {
        fclose($stream);
    }

    MedicalResultFile::create([
        'test_request_item_id' => $item->id,
        'uploaded_by' => $user->id,
        'file_path' => $medicalPath,
        'original_name' => $file->getClientOriginalName(),
        'file_type' => $fileType,
        'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
        'description' => $description,
    ]);
}
                /*
                 * Update workflow only after all files are stored.
                 */
                $this->statusService->markReady($item);

                $item->testRequest->update([
                    'updated_by' => $user->id,
                ]);

                return $result;
            });
        } catch (Throwable $exception) {
            /*
             * Remove any files stored if the database transaction fails.
             */
            foreach ($storedPaths as $storedPath) {
                Storage::disk('private')->delete($storedPath);
            }

            throw $exception;
        }
    }
}
