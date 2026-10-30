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


// result create

<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
               

  <h2 class="text-xl font-semibold text-white">
                    Upload Result
                </h2>

                <p class="text-sm text-slate-400 mt-1">
                    {{ $testRequestItem->test_name }}
                    — {{ $testRequestItem->testRequest->tracking_code }}
                </p>
            </div>

            <a
                href="{{ route('test-requests.show', $testRequestItem->testRequest) }}"
                class="text-sm text-slate-400 hover:text-white transition"
            >
                ← Back to Test Request
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Patient Information --}}
            <div class="bg-slate-900 border border-slate-800 shadow-sm sm:rounded-xl mb-6">
                <div class="p-6">

                    <h3 class="text-lg font-semibold text-white mb-5">
                        Patient Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <p class="text-sm text-slate-500">
                                Patient
                            </p>

                            <p class="mt-1 font-medium text-slate-200">
                                {{ $testRequestItem->testRequest->patient->full_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">
                                Tracking Code
                            </p>

                            <p class="mt-1 font-medium text-slate-200">
                                {{ $testRequestItem->testRequest->tracking_code }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">
                                Test
                            </p>

                            <p class="mt-1 font-medium text-slate-200">
                                {{ $testRequestItem->test_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">
                                Test Status
                            </p>

                            <p class="mt-1 font-medium text-green-400">
                                {{ $testRequestItem->status }}
                            </p>
                        </div>

                    </div>

                </div>
            </div>


            {{-- Upload Form --}}
            <div class="bg-slate-900 border border-slate-800 shadow-sm sm:rounded-xl">

                <div class="p-6">

                    <h3 class="text-lg font-semibold text-white">
                        Laboratory Result
                    </h3>

                    <p class="text-sm text-slate-400 mt-1 mb-6">
                        Upload the completed laboratory result as a PDF.
                    </p>


                    {{-- Validation Errors --}}
                    @if ($errors->any())

                        <div class="mb-6 rounded-lg bg-red-950/40 border border-red-800 p-4">

                            <p class="text-sm font-semibold text-red-400 mb-2">
                                Please correct the following errors:
                            </p>

                            <ul class="text-sm text-red-300 space-y-1">

                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('results.store', $testRequestItem) }}"
                        enctype="multipart/form-data"
                    >

                        @csrf


                        {{-- PDF --}}
                        <div>

                            <label
                                for="pdf"
                                class="block text-sm font-medium text-slate-300"
                            >
                                Result PDF
                            </label>

                            <input
                                id="pdf"
                                name="pdf"
                                type="file"
                                accept="application/pdf"
                                required
                                class="mt-2 block w-full text-sm text-slate-300
                                       border border-slate-700 rounded-lg
                                       cursor-pointer bg-slate-800
                                       focus:outline-none
                                       focus:border-indigo-500
                                       file:mr-4
                                       file:py-2
                                       file:px-4
                                       file:rounded-l-lg
                                       file:border-0
                                       file:text-sm
                                       file:font-semibold
                                       file:bg-slate-700
                                       file:text-slate-200
                                       hover:file:bg-slate-600"
                            >

                            <p class="mt-2 text-xs text-slate-500">
                                PDF files only. Maximum size: 10 MB.
                            </p>

                        </div>


                        {{-- Remarks --}}
                        <div class="mt-6">

                            <label
                                for="remarks"
                                class="block text-sm font-medium text-slate-300"
                            >
                                Remarks
                            </label>

                            <textarea
                                id="remarks"
                                name="remarks"
                                rows="4"
                                class="mt-2 block w-full rounded-lg
                                       bg-slate-800
                                       border-slate-700
                                       text-slate-200
                                       placeholder-slate-500
                                       shadow-sm
                                       focus:border-indigo-500
                                       focus:ring-indigo-500"
                                placeholder="Optional remarks about this result..."
                            >{{ old('remarks') }}</textarea>

                        </div>


                        {{-- Actions --}}
                        <div class="mt-6 flex items-center justify-end gap-3">

                            <a
                                href="{{ route('test-requests.show', $testRequestItem->testRequest) }}"
                                class="px-4 py-2 text-sm font-medium
                                       text-slate-300
                                       bg-slate-800
                                       border border-slate-700
                                       rounded-lg
                                       hover:bg-slate-700
                                       hover:text-white
                                       transition"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="px-4 py-2 text-sm font-medium
                                       text-white
                                       bg-indigo-600
                                       rounded-lg
                                       hover:bg-indigo-700
                                       transition"
                            >
                                Upload Result
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>


// action/uploadResult.php

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

            // $path = Storage::disk('private')->putFileAs(
            //     $directory,
            //     $pdf,
            //     $filename
            // );



$stream = fopen($pdf->getPathname(), 'rb');

if ($stream === false) {
    throw new RuntimeException('Unable to read the uploaded result file.');
}

// $path = Storage::disk('private')->put(
//     $directory . '/' . $filename,
//     $stream
// );

$path = $directory . '/' . $filename;

$stored = Storage::disk('private')->put(
    $path,
    $stream
);

if (! $stored) {
    throw new RuntimeException(
        'Unable to store the uploaded result file.'
    );
}

fclose($stream);

if ($path === false) {
    throw new RuntimeException('Unable to store the uploaded result file.');
}
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

// result controller

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
    UploadResult $uploadResult,
    \App\Actions\Results\UploadMedicalResultFiles $uploadMedicalResultFiles
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
        'medical_files' => [
            'nullable',
            'array',
            'max:10',
        ],
        'medical_files.*' => [
            'file',
            'mimes:jpg,jpeg,png,pdf',
            'max:10240',
        ],
        'file_type' => [
            'required_with:medical_files',
            'nullable',
            'in:X-ray,EEG,Ultrasound,Other',
        ],
        'description' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    // Upload the required PDF using the existing workflow.
    $result = $uploadResult->execute(
        $testRequestItem,
        $validated['pdf'],
        $validated['remarks'] ?? null
    );

    // Upload optional medical files after the PDF result exists.
    if (! empty($validated['medical_files'])) {
        $uploadMedicalResultFiles->execute(
            $testRequestItem,
            $validated['medical_files'],
            $validated['file_type'],
            $validated['description'] ?? null
        );
    }

    return redirect()
        ->route(
            'test-requests.show',
            $testRequestItem->testRequest
        )
        ->with(
            'success',
            'Result PDF and any medical files uploaded successfully.'
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