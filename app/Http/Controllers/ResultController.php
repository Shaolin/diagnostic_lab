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

            // Staff can only see results from their own branch.
            if (auth()->user()->isStaff()) {
                $query->where(
                    'branch_id',
                    auth()->user()->branch_id
                );
            }
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

    public function sendWhatsapp(Result $result)
{
    $result->load([
        'testRequestItem.testRequest.patient',
        'testRequestItem.testType',
    ]);

    // Make sure the result belongs to the logged-in laboratory
    abort_if(
        $result->testRequestItem->testRequest->laboratory_id !== auth()->user()->laboratory_id,
        403
    );

    // Only verified results can be sent to patients
    if (!$result->verified_at) {
        return redirect()->back()->with('error', 'This result must be verified before it can be sent via WhatsApp.');
    }

    $patient = $result->testRequestItem->testRequest->patient;

    // Patient must have a phone number
    if (!$patient->phone) {
        return redirect()->back()->with('error', 'This patient does not have a phone number.');
    }

    // Convert Nigerian phone number to international format
    $phone = preg_replace('/\D/', '', $patient->phone);

    if (str_starts_with($phone, '0')) {
        $phone = '234' . substr($phone, 1);
    }

    // Generate the patient's secure result-view link
    $resultUrl = route('patient.result.view', [
        'trackingCode' => $result->testRequestItem->testRequest->tracking_code,
        'result' => $result->id,
    ]);

    $laboratory = auth()->user()->laboratory;

    $message = "🧪 {$laboratory->name}\n\n"
        . "Hello {$patient->full_name}, your laboratory result is now ready.\n\n"
        . "You can securely view your result here:\n"
        . "{$resultUrl}\n\n"
        . "Thank you for choosing {$laboratory->name}.";

    return redirect(
        'https://wa.me/' . $phone . '?text=' . urlencode($message)
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