<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\TestRequest;
use App\Models\TestRequestItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display the laboratory reports.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Date Range
        |--------------------------------------------------------------------------
        */

        $from = $request->input(
            'from',
            now()->startOfMonth()->format('Y-m-d')
        );

        $to = $request->input(
            'to',
            now()->format('Y-m-d')
        );


        /*
        |--------------------------------------------------------------------------
        | Financial Summary
        |--------------------------------------------------------------------------
        */

        $testRequests = TestRequest::query()
            ->where('laboratory_id', $user->laboratory_id)
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);


        $totalBilled = (clone $testRequests)
            ->sum('total_amount');


        $payments = Payment::query()
            ->where('laboratory_id', $user->laboratory_id)
            ->whereDate('paid_at', '>=', $from)
            ->whereDate('paid_at', '<=', $to);


        $totalCollected = (clone $payments)
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Outstanding Balance
        |--------------------------------------------------------------------------
        |
        | This represents the outstanding balance on test requests
        | created during the selected reporting period.
        |
        */

        $requestIds = (clone $testRequests)
            ->pluck('id');


        $totalOutstanding = 0;

        if ($requestIds->isNotEmpty()) {

            $periodRequests = TestRequest::query()
                ->whereIn('id', $requestIds)
                ->withSum('payments', 'amount')
                ->get();

            $totalOutstanding = $periodRequests->sum(function ($testRequest) {

                return max(
                    0,
                    (float) $testRequest->total_amount
                    - (float) ($testRequest->payments_sum_amount ?? 0)
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Methods
        |--------------------------------------------------------------------------
        */

        $paymentMethods = (clone $payments)
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Test Request Summary
        |--------------------------------------------------------------------------
        */

        $requestSummary = [
            'total' => (clone $testRequests)->count(),

            'pending' => (clone $testRequests)
                ->where('overall_status', TestRequest::STATUS_PENDING)
                ->count(),

            'in_progress' => (clone $testRequests)
                ->where('overall_status', TestRequest::STATUS_IN_PROGRESS)
                ->count(),

            'completed' => (clone $testRequests)
                ->where('overall_status', TestRequest::STATUS_COMPLETED)
                ->count(),

            'partially_completed' => (clone $testRequests)
                ->where(
                    'overall_status',
                    TestRequest::STATUS_PARTIALLY_COMPLETED
                )
                ->count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | Most Requested Tests
        |--------------------------------------------------------------------------
        */

        $mostRequestedTests = TestRequestItem::query()
            ->whereHas('testRequest', function ($query) use ($user, $from, $to) {

                $query->where('laboratory_id', $user->laboratory_id)
                    ->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to);

            })
            ->selectRaw('
                test_name,
                COUNT(*) as requests_count,
                SUM(price) as revenue
            ')
            ->groupBy('test_name')
            ->orderByDesc('requests_count')
            ->limit(10)
            ->get();


        return view('reports.index', compact(
            'from',
            'to',
            'totalBilled',
            'totalCollected',
            'totalOutstanding',
            'paymentMethods',
            'requestSummary',
            'mostRequestedTests'
        ));
    }
}