<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\TestRequest;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {
    }

    /**
     * Display a listing of payments.
     */
    public function index(Request $request): View
    {
        $payments = Payment::query()
            ->with([
                'testRequest.patient',
                'receivedBy',
            ])
            ->forLaboratory(auth()->user()->laboratory_id)
            ->latest('paid_at')
            ->paginate(15)
            ->withQueryString();

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the payment form for a test request.
     */
    public function create(TestRequest $testRequest): View
    {
        $this->authorizeTestRequest($testRequest);

        $testRequest->load([
            'patient',
            'items',
            'payments.receivedBy',
        ]);

        return view('payments.create', compact('testRequest'));
    }

    /**
     * Store a newly recorded payment.
     */
    public function store(
        Request $request,
        TestRequest $testRequest
    ): RedirectResponse {
        $this->authorizeTestRequest($testRequest);

        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'payment_method' => [
                'required',
                'in:Cash,Transfer,POS,Other',
            ],

            'payment_reference' => [
                'nullable',
                'string',
                'max:255',
            ],

            'paid_at' => [
                'required',
                'date',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $this->paymentService->recordPayment(
            $testRequest,
            $validated,
            auth()->id()
        );

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display a specific payment.
     */
    public function show(Payment $payment): View
    {
        $this->authorizePayment($payment);

        $payment->load([
            'testRequest.patient',
            'testRequest.items',
            'receivedBy',
        ]);

        return view('payments.show', compact('payment'));
    }

    /**
     * Ensure the test request belongs to the current user's laboratory.
     */
    protected function authorizeTestRequest(TestRequest $testRequest): void
    {
        abort_unless(
            $testRequest->laboratory_id === auth()->user()->laboratory_id,
            403
        );
    }

    /**
     * Ensure the payment belongs to the current user's laboratory.
     */
    protected function authorizePayment(Payment $payment): void
    {
        abort_unless(
            $payment->laboratory_id === auth()->user()->laboratory_id,
            403
        );
    }
}