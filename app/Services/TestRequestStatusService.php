<?php

namespace App\Services;

use App\Models\TestRequest;
use App\Models\TestRequestItem;

class TestRequestStatusService
{
    /**
     * Update the overall status of a test request.
     */
    public function update(TestRequest $request): void
    {
        $statuses = $request
            ->items()
            ->pluck('status');

        if ($statuses->every(fn ($status) => $status === TestRequestItem::STATUS_COMPLETED)) {

            $request->update([
                'overall_status' => TestRequest::STATUS_COMPLETED,
            ]);

            return;
        }

        if ($statuses->contains(TestRequestItem::STATUS_IN_PROGRESS)) {

            $request->update([
                'overall_status' => TestRequest::STATUS_IN_PROGRESS,
            ]);

            return;
        }

        if (
            $statuses->contains(TestRequestItem::STATUS_COMPLETED)
            && $statuses->contains(TestRequestItem::STATUS_PENDING)
        ) {

            $request->update([
                'overall_status' => TestRequest::STATUS_PARTIALLY_COMPLETED,
            ]);

            return;
        }

        $request->update([
            'overall_status' => TestRequest::STATUS_PENDING,
        ]);
    }
}