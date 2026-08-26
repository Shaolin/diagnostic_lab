<?php

namespace App\Http\Controllers;

use App\Actions\Results\DownloadResult;
use App\Actions\Results\ReplaceResult;
use App\Actions\Results\UploadResult;
use App\Actions\Results\VerifyResult;
use App\Models\Result;
use App\Models\TestRequestItem;
use Illuminate\Http\Request;

class ResultController extends Controller
{

/**
 * Display a listing of laboratory results.
 */
public function index(Request $request)
{
    $laboratoryId = auth()->user()->laboratory_id;

    $results = Result::query()
        ->with([
            'testRequestItem.testRequest.patient',
            'testRequestItem.testType',
            'uploadedBy',
            'verifiedBy',
        ])
        ->whereHas('testRequestItem.testRequest', function ($query) use ($laboratoryId) {
            $query->where('laboratory_id', $laboratoryId);
        })
        ->when($request->search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query
                    ->whereHas('testRequestItem', function ($query) use ($search) {
                        $query->where('test_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('testRequestItem.testRequest', function ($query) use ($search) {
                        $query->where('tracking_code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('testRequestItem.testRequest.patient', function ($query) use ($search) {
                        $query->where('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%")
                              ->orWhere('other_names', 'like', "%{$search}%")
                              ->orWhere('patient_number', 'like', "%{$search}%");
                    });
            });
        })
        ->when($request->status === 'verified', function ($query) {
            $query->whereNotNull('verified_at');
        })
        ->when($request->status === 'pending', function ($query) {
            $query->whereNull('verified_at');
        })
        ->latest('uploaded_at')
        ->paginate(15)
        ->withQueryString();

    return view('results.index', compact('results'));
}
    /**
     * Show the result upload form.
     */
    public function create(TestRequestItem $testRequestItem)
    {
        abort_if(
            $testRequestItem->testRequest->laboratory_id
                !== auth()->user()->laboratory_id,
            403
        );

        $testRequestItem->load([
            'testRequest.patient',
            'testType',
            'result',
        ]);

        return view(
            'results.create',
            compact('testRequestItem')
        );
    }

    /**
     * Upload a laboratory result.
     */
    public function store(
        Request $request,
        TestRequestItem $testRequestItem,
        UploadResult $uploadResult
    ) {
        abort_if(
            $testRequestItem->testRequest->laboratory_id
                !== auth()->user()->laboratory_id,
            403
        );

        $validated = $request->validate([
            'pdf' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $uploadResult->execute(
            $testRequestItem,
            $validated['pdf'],
            $validated['remarks'] ?? null
        );

        return redirect()
            ->route(
                'test-requests.show',
                $testRequestItem->testRequest
            )
            ->with(
                'success',
                'Result uploaded successfully.'
            );
    }

    /**
     * Download a laboratory result.
     */
    public function download(
        Result $result,
        DownloadResult $downloadResult
    ) {
        return $downloadResult->execute($result);
    }

    /**
     * Verify a laboratory result.
     */
    public function verify(
        Result $result,
        VerifyResult $verifyResult
    ) {
        $verifyResult->execute($result);

        return redirect()
            ->back()
            ->with(
                'success',
                'Result verified successfully.'
            );
    }

    /**
     * Show the result replacement form.
     */
    public function edit(Result $result)
    {
        abort_if(
            $result->testRequestItem->testRequest->laboratory_id
                !== auth()->user()->laboratory_id,
            403
        );

        $result->load([
            'testRequestItem.testRequest.patient',
            'testRequestItem.testType',
        ]);

        return view(
            'results.edit',
            compact('result')
        );
    }

    /**
     * Replace an existing laboratory result.
     */
    public function update(
        Request $request,
        Result $result,
        ReplaceResult $replaceResult
    ) {
        abort_if(
            $result->testRequestItem->testRequest->laboratory_id
                !== auth()->user()->laboratory_id,
            403
        );

        $validated = $request->validate([
            'pdf' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240',
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $replaceResult->execute(
            $result,
            $validated['pdf'],
            $validated['remarks'] ?? null
        );

        return redirect()
            ->route(
                'test-requests.show',
                $result->testRequestItem->testRequest
            )
            ->with(
                'success',
                'Result replaced successfully. The new result must be verified again.'
            );
    }
}