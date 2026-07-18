<?php

namespace App\Actions;

use App\Models\TestRequest;
use App\Models\TestRequestItem;
use App\Models\TestType;
use Illuminate\Support\Facades\DB;

class UpdateTestRequest
{
    public function execute(TestRequest $testRequest, array $data): TestRequest
    {
        return DB::transaction(function () use ($testRequest, $data) {

            /*
            |--------------------------------------------------------------------------
            | Update Main Test Request
            |--------------------------------------------------------------------------
            */

            $testRequest->update([
                'patient_id' => $data['patient_id'],
                'remarks'    => $data['remarks'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Remove Existing Items
            |--------------------------------------------------------------------------
            */

            $testRequest->items()->delete();

            /*
            |--------------------------------------------------------------------------
            | Fetch Test Types (Avoid N+1 Queries)
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
            | Recreate Items
            |--------------------------------------------------------------------------
            */

            $total = 0;

            foreach ($data['items'] as $item) {

                $testType = $testTypes->get($item['test_type_id']);

                $testRequest->items()->create([
                    'test_type_id'  => $testType->id,
                    'test_name'     => $testType->name,
                    'price'         => $item['price'],

                    'status'        => TestRequestItem::STATUS_PENDING,
                    'sample_status' => TestRequestItem::SAMPLE_PENDING,
                    'result_status' => TestRequestItem::RESULT_NOT_READY,
                ]);

                $total += $item['price'];
            }

            /*
            |--------------------------------------------------------------------------
            | Update Total Amount
            |--------------------------------------------------------------------------
            */

            $testRequest->update([
                'total_amount' => $total,
            ]);

            return $testRequest->fresh([
                'patient',
                'items.testType',
                'requestedBy',
            ]);
        });
    }
}