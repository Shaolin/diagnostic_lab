<?php

namespace App\Actions;

use App\Models\TestRequest;
use App\Models\TestType;
use App\Services\TestRequestCalculator;
use App\Services\TrackingCodeGenerator;
use Illuminate\Support\Facades\DB;
use App\Models\TestRequestItem;

class CreateTestRequest
{
    public function __construct(
        protected TrackingCodeGenerator $trackingCodeGenerator,
        protected TestRequestCalculator $calculator
    ) {
    }

    /**
     * Create a Test Request with its items.
     */
  public function execute(array $data): TestRequest
{
    return DB::transaction(function () use ($data) {

        /*
        |--------------------------------------------------------------------------
        | Calculate Total
        |--------------------------------------------------------------------------
        */

        $totalAmount = $this->calculator->calculate($data['items']);

        /*
        |--------------------------------------------------------------------------
        | Fetch All Test Types (Avoid N+1 Queries)
        |--------------------------------------------------------------------------
        */

        $testTypes = TestType::whereIn(
            'id',
            collect($data['items'])->pluck('test_type_id')
        )
        ->get()
        ->keyBy('id');

        /*
        |--------------------------------------------------------------------------
        | Create Test Request
        |--------------------------------------------------------------------------
        */

        $testRequest = TestRequest::create([
            'laboratory_id' => auth()->user()->laboratory_id,
            'patient_id' => $data['patient_id'],
            'tracking_code' => $this->trackingCodeGenerator->generate(),
            'total_amount' => $totalAmount,
            'remarks' => $data['remarks'] ?? null,
            'overall_status' => TestRequest::STATUS_PENDING,
            'requested_by' => auth()->id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Test Request Items
        |--------------------------------------------------------------------------
        */

        foreach ($data['items'] as $item) {

            $testType = $testTypes->get($item['test_type_id']);

            $testRequest->items()->create([
                'test_type_id' => $testType->id,
                'test_name'    => $testType->name,
                'price'        => $item['price'],

                'status'         => TestRequestItem::STATUS_PENDING,
                'sample_status'  => TestRequestItem::SAMPLE_PENDING,
                'result_status'  => TestRequestItem::RESULT_NOT_READY,
            ]);
        }

        return $testRequest->load([
            'patient',
            'items.testType',
            'requestedBy',
        ]);
    });
}
}