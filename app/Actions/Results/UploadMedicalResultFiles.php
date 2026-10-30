<?php
namespace App\Actions\Results;

use App\Models\MedicalResultFile;
use App\Models\TestRequestItem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class UploadMedicalResultFiles
{
    /**
     * Upload medical images and films for a test request item.
     *
     * @param array<int, UploadedFile> $files
     */
    public function execute(
        TestRequestItem $item,
        array $files,
        string $fileType,
        ?string $description = null
    ): array {
        $user = Auth::user();

        $item->loadMissing(['testRequest', 'result']);

        if (! $user || $item->testRequest->laboratory_id !== $user->laboratory_id) {
            throw new RuntimeException(
                'You are not authorized to upload files for this laboratory.'
            );
        }

        if (! $item->isSampleCollected()) {
            throw new RuntimeException(
                'The sample has not yet been collected.'
            );
        }

        if (! $item->isCompleted()) {
            throw new RuntimeException(
                'The laboratory test has not been completed.'
            );
        }

        if (! $item->result) {
            throw new RuntimeException(
                'Upload the required PDF result before uploading medical files.'
            );
        }

        $allowedTypes = ['X-ray', 'EEG', 'Ultrasound', 'Other'];

        if (! in_array($fileType, $allowedTypes, true)) {
            throw new RuntimeException('Invalid medical file category.');
        }

        $createdFiles = [];
        $storedPaths = [];

        try {
            DB::transaction(function () use (
                $item,
                $files,
                $fileType,
                $description,
                $user,
                &$createdFiles,
                &$storedPaths
            ) {
                foreach ($files as $file) {
                    $directory = 'medical-results/'
                        . now()->format('Y')
                        . '/'
                        . now()->format('m');

                    $path = $file->store($directory, 'private');

                    if (! $path) {
                        throw new RuntimeException(
                            'Unable to store a medical result file.'
                        );
                    }

                    $storedPaths[] = $path;

                    $createdFiles[] = MedicalResultFile::create([
                        'test_request_item_id' => $item->id,
                        'uploaded_by' => $user->id,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'file_type' => $fileType,
                        'mime_type' => $file->getMimeType()
                            ?: 'application/octet-stream',
                        'description' => $description,
                    ]);
                }
            });
        } catch (Throwable $exception) {
            foreach ($storedPaths as $path) {
                Storage::disk('private')->delete($path);
            }

            throw $exception;
        }

        return $createdFiles;
    }
}
