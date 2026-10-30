<?php
namespace App\Http\Controllers;

use App\Models\InsuranceClaim;
use App\Models\TestRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InsuranceClaimController extends Controller
{
    /**
     * List insurance claims for the current laboratory.
     */
    public function index(): View
    {
        $claims = InsuranceClaim::query()
            ->with(['testRequest.patient', 'payments'])
            ->where('laboratory_id', auth()->user()->laboratory_id)
            ->latest()
            ->paginate(15);

        return view('insurance-claims.index', compact('claims'));
    }

    
/**
 * Display the insurance payments overview.
 */
public function paymentsIndex(): View
{
    $claims = InsuranceClaim::query()
        ->with(['testRequest.patient', 'payments'])
        ->where('laboratory_id', auth()->user()->laboratory_id)
        ->latest()
        ->paginate(15);

    $totalClaimed = InsuranceClaim::query()
        ->where('laboratory_id', auth()->user()->laboratory_id)
        ->sum('covered_amount');

    $totalReceived = \App\Models\InsuranceClaimPayment::query()
        ->where('laboratory_id', auth()->user()->laboratory_id)
        ->sum('amount');

    $totalOutstanding = max(0, $totalClaimed - $totalReceived);

    $pendingClaims = InsuranceClaim::query()
        ->where('laboratory_id', auth()->user()->laboratory_id)
        ->whereIn('status', ['Pending', 'Submitted', 'Partially Paid'])
        ->count();

    return view('insurance-claims.payments-index', compact(
        'claims',
        'totalClaimed',
        'totalReceived',
        'totalOutstanding',
        'pendingClaims'
    ));
}


    /**
     * Show the form for creating an insurance claim.
     */
    public function create(TestRequest $testRequest): View
    {
        $this->authorizeTestRequest($testRequest);

        abort_if(
            $testRequest->insuranceClaim()->exists(),
            409,
            'An insurance claim already exists for this test request.'
        );

        $testRequest->load(['patient', 'items']);

        return view('insurance-claims.create', compact('testRequest'));
    }

    /**
     * Store an insurance claim.
     */
    public function store(
        Request $request,
        TestRequest $testRequest
    ): RedirectResponse {
        $this->authorizeTestRequest($testRequest);

        $validated = $request->validate([
            'insurance_company' => [
                'required',
                'string',
                'max:255',
            ],
            'membership_number' => [
                'nullable',
                'string',
                'max:255',
            ],
            'covered_amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'patient_contribution' => [
                'required',
                'numeric',
                'min:0',
            ],
            'remarks' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $covered = (float) $validated['covered_amount'];
        $contribution = (float) $validated['patient_contribution'];
        $total = (float) $testRequest->total_amount;

        if (($covered + $contribution) > ($total + 0.001)) {
            throw ValidationException::withMessages([
                'covered_amount' =>
                    'The insurance coverage and patient contribution '
                    . 'cannot exceed the test request total of ₦'
                    . number_format($total, 2) . '.',
            ]);
        }

        DB::transaction(function () use (
            $testRequest,
            $validated
        ) {
            // Lock the test request to prevent duplicate claims
            // from concurrent submissions.
            $lockedRequest = TestRequest::query()
                ->whereKey($testRequest->id)
                ->where(
                    'laboratory_id',
                    auth()->user()->laboratory_id
                )
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedRequest->insuranceClaim()->exists()) {
                throw ValidationException::withMessages([
                    'insurance_company' =>
                        'An insurance claim already exists for this test request.',
                ]);
            }

            InsuranceClaim::create([
                'laboratory_id' => $lockedRequest->laboratory_id,
                'test_request_id' => $lockedRequest->id,
                'insurance_company' =>
                    $validated['insurance_company'],
                'membership_number' =>
                    $validated['membership_number'] ?? null,
                'covered_amount' => $validated['covered_amount'],
                'patient_contribution' =>
                    $validated['patient_contribution'],
                'status' => 'Pending',
                'remarks' => $validated['remarks'] ?? null,
            ]);
        });

        return redirect()
            ->route('insurance-claims.index')
            ->with('success', 'Insurance claim created successfully.');
    }

    /**
     * Display one insurance claim.
     */
    public function show(InsuranceClaim $insuranceClaim): View
    {
        $this->authorizeClaim($insuranceClaim);

        $insuranceClaim->load([
            'testRequest.patient',
            'testRequest.items',
            'payments.receivedBy',
        ]);

        return view(
            'insurance-claims.show',
            compact('insuranceClaim')
        );
    }

    protected function authorizeTestRequest(
        TestRequest $testRequest
    ): void {
        abort_unless(
            (int) $testRequest->laboratory_id ===
                (int) auth()->user()->laboratory_id,
            403
        );
    }

    protected function authorizeClaim(
        InsuranceClaim $insuranceClaim
    ): void {
        abort_unless(
            (int) $insuranceClaim->laboratory_id ===
                (int) auth()->user()->laboratory_id,
            403
        );
    }
}
