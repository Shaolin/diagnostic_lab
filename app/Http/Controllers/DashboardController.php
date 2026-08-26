<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Payment;
use App\Models\TestRequest;
use App\Models\TestRequestItem;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the laboratory dashboard.
     */
    public function index(): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        /*
        |--------------------------------------------------------------------------
        | Main Statistics
        |--------------------------------------------------------------------------
        */

        $totalPatients = Patient::query()
            ->where('laboratory_id', $laboratoryId)
            ->count();

        $totalTestRequests = TestRequest::query()
            ->where('laboratory_id', $laboratoryId)
            ->count();

        $pendingRequests = TestRequest::query()
            ->where('laboratory_id', $laboratoryId)
            ->where(
                'overall_status',
                TestRequest::STATUS_PENDING
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Outstanding Balance
        |--------------------------------------------------------------------------
        */

        $testRequests = TestRequest::query()
            ->where('laboratory_id', $laboratoryId)
            ->withSum('payments', 'amount')
            ->get();

        $outstandingBalance = $testRequests->sum(function ($testRequest) {

            return max(
                0,
                (float) $testRequest->total_amount
                - (float) ($testRequest->payments_sum_amount ?? 0)
            );

        });


        /*
        |--------------------------------------------------------------------------
        | Today's Activity
        |--------------------------------------------------------------------------
        */

        $today = now()->toDateString();


        $newPatientsToday = Patient::query()
            ->where('laboratory_id', $laboratoryId)
            ->whereDate('created_at', $today)
            ->count();


        $testRequestsToday = TestRequest::query()
            ->where('laboratory_id', $laboratoryId)
            ->whereDate('created_at', $today)
            ->count();


        $paymentsToday = Payment::query()
            ->where('laboratory_id', $laboratoryId)
            ->whereDate('paid_at', $today)
            ->sum('amount');


        $resultsToday = TestRequestItem::query()
            ->whereHas('testRequest', function ($query) use ($laboratoryId) {

                $query->where('laboratory_id', $laboratoryId);

            })
            ->where('status', TestRequestItem::STATUS_COMPLETED)
            ->whereDate('completed_at', $today)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Recent Test Requests
        |--------------------------------------------------------------------------
        */

        $recentTestRequests = TestRequest::query()
            ->where('laboratory_id', $laboratoryId)
            ->with([
                'patient',
                'items',
            ])
            ->latest()
            ->limit(8)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Payments
        |--------------------------------------------------------------------------
        */

        $recentPayments = Payment::query()
            ->where('laboratory_id', $laboratoryId)
            ->with([
                'testRequest.patient',
                'receivedBy',
            ])
            ->latest('paid_at')
            ->limit(8)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Pending / Active Tests
        |--------------------------------------------------------------------------
        */

        $activeTests = TestRequestItem::query()
            ->whereHas('testRequest', function ($query) use ($laboratoryId) {

                $query->where('laboratory_id', $laboratoryId);

            })
            ->whereIn('status', [
                TestRequestItem::STATUS_PENDING,
                TestRequestItem::STATUS_IN_PROGRESS,
            ])
            ->with([
                'testRequest.patient',
                'testRequest',
            ])
            ->latest()
            ->limit(8)
            ->get();


        return view('dashboard', compact(
            'totalPatients',
            'totalTestRequests',
            'pendingRequests',
            'outstandingBalance',
            'newPatientsToday',
            'testRequestsToday',
            'paymentsToday',
            'resultsToday',
            'recentTestRequests',
            'recentPayments',
            'activeTests'
        ));
    }
}