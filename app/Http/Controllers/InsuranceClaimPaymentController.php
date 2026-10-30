<?php
namespace App\Http\Controllers;

use App\Models\InsuranceClaim;
use App\Models\InsuranceClaimPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InsuranceClaimPaymentController extends Controller
{
    public function create(InsuranceClaim $insuranceClaim): View
    {
        $this->authorizeClaim($insuranceClaim);

        $insuranceClaim->load('testRequest.patient');

        return view('insurance-claims.payments.create', compact('insuranceClaim'));
    }

    public function store(
        Request $request,
        InsuranceClaim $insuranceClaim
    ): RedirectResponse {
        $this->authorizeClaim($insuranceClaim);

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0'],
            'payment_reference' => ['nullable', 'string', 'max:255'],
            'paid_at' => ['required', 'date'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($insuranceClaim, $validated) {
            $claim = InsuranceClaim::query()
                ->whereKey($insuranceClaim->id)
                ->where('laboratory_id', auth()->user()->laboratory_id)
                ->lockForUpdate()
                ->firstOrFail();

            if (in_array($claim->status, ['Rejected', 'Paid'], true)) {
                throw ValidationException::withMessages([
                    'amount' => 'A rejected or fully paid claim cannot receive another settlement.',
                ]);
            }

            $alreadySettled = (float) $claim->payments()->sum('amount');
            $outstanding = max(
                0,
                (float) $claim->covered_amount - $alreadySettled
            );
            $amount = (float) $validated['amount'];

            if ($amount > $outstanding + 0.001) {
                throw ValidationException::withMessages([
                    'amount' => 'The payment cannot exceed the outstanding claim balance of ₦'
                        . number_format($outstanding, 2) . '.',
                ]);
            }

            InsuranceClaimPayment::create([
                'laboratory_id' => $claim->laboratory_id,
                'insurance_claim_id' => $claim->id,
                'amount' => $validated['amount'],
                'payment_reference' => $validated['payment_reference'] ?? null,
                'paid_at' => $validated['paid_at'],
                'received_by' => auth()->id(),
                'remarks' => $validated['remarks'] ?? null,
            ]);

            $newTotalSettled = $alreadySettled + $amount;

            if ($newTotalSettled >= (float) $claim->covered_amount - 0.001) {
                $claim->status = 'Paid';
            } else {
                $claim->status = 'Partially Paid';
            }

            $claim->save();
        });

        return redirect()
            ->route('insurance-claims.show', $insuranceClaim)
            ->with('success', 'Insurance settlement recorded successfully.');
    }

    protected function authorizeClaim(InsuranceClaim $insuranceClaim): void
    {
        abort_unless(
            (int) $insuranceClaim->laboratory_id ===
                (int) auth()->user()->laboratory_id,
            403
        );
    }
}