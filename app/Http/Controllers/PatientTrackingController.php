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

       $testRequest = TestRequest::with(['items.result'])
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
    |
    | Make sure this result actually belongs to the test request
    | represented by the tracking code.
    |
    */

    if (
        !$result->testRequestItem ||
        $result->testRequestItem->test_request_id !== $testRequest->id
    ) {
        abort(403, 'Unauthorized access to this result.');
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
}