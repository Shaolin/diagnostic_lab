<?php
namespace App\Http\Controllers;
use App\Models\TestRequest;
use Illuminate\Http\Request;
use App\Models\Result;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PatientTrackingController extends Controller
{
    public function index()
    {
        return view('patient.track');
    }

    public function search(Request $request)
    {
        $request->validate([
            'tracking_code' => ['required', 'string', 'max:255'],
        ]);

        $trackingCode = trim($request->tracking_code);

        // $testRequest = TestRequest::with(['items.result'])
        $testRequest = TestRequest::with([
    'items.result',
    'items.medicalResultFiles',
])
            ->where('tracking_code', $trackingCode)
            ->first();

        if (!$testRequest) {
            return back()
                ->withInput()
                ->with('error', 'We could not find a laboratory request with that tracking ID.');
        }

        return view('patient.result', compact('testRequest'));
    }

    public function download(string $trackingCode, Result $result)
    {
        $testRequest = TestRequest::where('tracking_code', $trackingCode)
            ->firstOrFail();

        $result->loadMissing([
            'testRequestItem.testRequest',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if (
            !$result->testRequestItem ||
            $result->testRequestItem->test_request_id !== $testRequest->id
        ) {
            abort(403, 'Unauthorized access to this result.');
        }

        /*
        |--------------------------------------------------------------------------
        | Verification Check
        |--------------------------------------------------------------------------
        |
        | Patients can only access results that have been verified.
        |
        */

        if (!$result->verified_at) {
            abort(404, 'Result not available.');
        }

        /*
        |--------------------------------------------------------------------------
        | Result File
        |--------------------------------------------------------------------------
        */

        if (
            !$result->pdf_path ||
            !Storage::disk('private')->exists($result->pdf_path)
        ) {
            abort(404, 'Result file not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Download
        |--------------------------------------------------------------------------
        */

        $fileName = Str::slug(
            $result->testRequestItem->test_name
        ) . '-result.pdf';

        return response()->download(
            Storage::disk('private')->path($result->pdf_path),
            $fileName,
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }

    public function view(string $trackingCode, Result $result)
    {
        $testRequest = TestRequest::where('tracking_code', $trackingCode)
            ->firstOrFail();

        $result->loadMissing([
            'testRequestItem.testRequest',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if (
            !$result->testRequestItem ||
            $result->testRequestItem->test_request_id !== $testRequest->id
        ) {
            abort(403, 'Unauthorized access to this result.');
        }

        /*
        |--------------------------------------------------------------------------
        | Verification Check
        |--------------------------------------------------------------------------
        |
        | Patients can only view results that have been verified.
        |
        */

        if (!$result->verified_at) {
            abort(404, 'Result not available.');
        }

        /*
        |--------------------------------------------------------------------------
        | Result File
        |--------------------------------------------------------------------------
        */

        if (
            !$result->pdf_path ||
            !Storage::disk('private')->exists($result->pdf_path)
        ) {
            abort(404, 'Result file not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Display PDF in Browser
        |--------------------------------------------------------------------------
        */

        return response()->file(
            Storage::disk('private')->path($result->pdf_path),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' .
                    basename($result->pdf_path) . '"',
            ]
        );
    }

    
/**
 * View a medical result file.
 */
public function viewMedicalFile(string $trackingCode, \App\Models\MedicalResultFile $medicalFile)
{
    $testRequest = TestRequest::where('tracking_code', $trackingCode)
        ->firstOrFail();

    $medicalFile->loadMissing([
        'testRequestItem.result',
        'testRequestItem.testRequest',
    ]);

    $item = $medicalFile->testRequestItem;
    $result = $item?->result;

    if (
        ! $item ||
        $item->test_request_id !== $testRequest->id ||
        ! $result ||
        ! $result->verified_at
    ) {
        abort(404, 'Medical file not available.');
    }

    if (
        ! $medicalFile->file_path ||
        ! \Illuminate\Support\Facades\Storage::disk('private')
            ->exists($medicalFile->file_path)
    ) {
        abort(404, 'Medical file not found.');
    }

    $path = \Illuminate\Support\Facades\Storage::disk('private')
        ->path($medicalFile->file_path);

    return response()->file($path, [
        'Content-Type' => $medicalFile->mime_type,
        'X-Content-Type-Options' => 'nosniff',
    ]);
}


/**
 * Download a medical result file.
 */
public function downloadMedicalFile(string $trackingCode, \App\Models\MedicalResultFile $medicalFile)
{
    $testRequest = TestRequest::where('tracking_code', $trackingCode)
        ->firstOrFail();

    $medicalFile->loadMissing([
        'testRequestItem.result',
    ]);

    $item = $medicalFile->testRequestItem;
    $result = $item?->result;

    if (
        ! $item ||
        $item->test_request_id !== $testRequest->id ||
        ! $result ||
        ! $result->verified_at
    ) {
        abort(404, 'Medical file not available.');
    }

    if (
        ! $medicalFile->file_path ||
        ! \Illuminate\Support\Facades\Storage::disk('private')
            ->exists($medicalFile->file_path)
    ) {
        abort(404, 'Medical file not found.');
    }

    return \Illuminate\Support\Facades\Storage::disk('private')
        ->download(
            $medicalFile->file_path,
            $medicalFile->original_name
        );
}

}
