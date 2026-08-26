<?php

namespace App\Http\Controllers;

use App\Actions\CreateTestRequest;
use App\Http\Requests\StoreTestRequestRequest;
use App\Http\Requests\UpdateTestRequestRequest;
use App\Models\Patient;
use App\Models\TestRequest;
use App\Models\TestType;
use Illuminate\Http\Request;
use App\Models\TestRequestItem;
use App\Actions\UpdateTestRequest;



class TestRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $laboratoryId = auth()->user()->laboratory_id;

    $testRequests = TestRequest::query()
        ->with([
            'patient',
            'requestedBy',
        ])
        ->withCount('items')
        ->forLaboratory($laboratoryId)
        ->when($request->tracking_code, function ($query, $trackingCode) {
            $query->where('tracking_code', 'like', "%{$trackingCode}%");
        })
        ->when($request->patient, function ($query, $patient) {
            $query->whereHas('patient', function ($query) use ($patient) {
                $query->where('first_name', 'like', "%{$patient}%")
                      ->orWhere('last_name', 'like', "%{$patient}%");
            });
        })
        ->when($request->status, function ($query, $status) {
            $query->where('overall_status', $status);
        })
        ->when($request->date, function ($query, $date) {
            $query->whereDate('created_at', $date);
        })
        ->latest()
        ->paginate(15)
        ->withQueryString();

    return view('test-requests.index', compact('testRequests'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $patients = Patient::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('first_name')
            ->get();

        $testTypes = TestType::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('test-requests.create', compact(
            'patients',
            'testTypes'
        ));
    }

    /**
     * Store a newly created resource.
     */
    public function store(
        StoreTestRequestRequest $request,
        CreateTestRequest $createTestRequest
    ) {
        $testRequest = $createTestRequest->execute(
            $request->validated()
        );

        return redirect()
            ->route('test-requests.show', $testRequest)
            ->with('success', 'Test request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TestRequest $testRequest)
    {
        abort_if(
            $testRequest->laboratory_id !== auth()->user()->laboratory_id,
            403
        );

        // $testRequest->load([
        //     'patient',
        //     'requestedBy',
        //     'items.testType',
        // ]);
          $testRequest->load([
    'patient',
    'requestedBy',
    'items.testType',
    'items.result.uploadedBy',
    'items.result.verifiedBy',
]);

        return view('test-requests.show', compact('testRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TestRequest $testRequest)
    {
        abort_if(
            $testRequest->laboratory_id !== auth()->user()->laboratory_id,
            403
        );
        if ($testRequest->items()->whereIn('status', [
    TestRequestItem::STATUS_IN_PROGRESS,
    TestRequestItem::STATUS_COMPLETED,
])->exists()) {

    return redirect()
        ->route('test-requests.show', $testRequest)
        ->with(
            'error',
            'This test request can no longer be edited because one or more tests have already started.'
        );
}

        $laboratoryId = auth()->user()->laboratory_id;

        $patients = Patient::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('first_name')
            ->get();

        $testTypes = TestType::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $testRequest->load('items');

        return view('test-requests.edit', compact(
            'testRequest',
            'patients',
            'testTypes'
        ));
    }

    /**
     * Update the specified resource.
     */
   
    public function update(
    UpdateTestRequestRequest $request,
    TestRequest $testRequest,
    UpdateTestRequest $action
) {
    abort_if(
        $testRequest->laboratory_id !== auth()->user()->laboratory_id,
        403
    );

    $action->execute(
        $testRequest,
        $request->validated()
    );

    return redirect()
        ->route('test-requests.show', $testRequest)
        ->with('success', 'Test request updated successfully.');
}

    /**
     * Remove the specified resource.
     */
    public function destroy(TestRequest $testRequest)
    {
        abort_if(
            $testRequest->laboratory_id !== auth()->user()->laboratory_id,
            403
        );

        $testRequest->delete();

        return redirect()
            ->route('test-requests.index')
            ->with('success', 'Test request deleted successfully.');
    }
}