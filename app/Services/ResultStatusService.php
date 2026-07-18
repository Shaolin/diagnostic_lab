<?php

namespace App\Services;

use App\Models\TestRequest;
use App\Models\TestRequestItem;

class ResultStatusService
{
    /**
     * Mark a Test Request Item as Result Ready.
     */
    public function markReady(TestRequestItem $item): void
    {
        $item->update([
            'result_status' => TestRequestItem::RESULT_READY,
        ]);

        $this->updateOverallStatus($item->testRequest);
    }

    /**
     * Mark a Test Request Item as Result Sent.
     */
    public function markSent(TestRequestItem $item): void
    {
        $item->update([
            'result_status' => TestRequestItem::RESULT_SENT,
        ]);

        $this->updateOverallStatus($item->testRequest);
    }

    /**
     * Update the overall status of the parent Test Request.
     */
    public function updateOverallStatus(TestRequest $testRequest): void
    {
        // Reload items to ensure fresh data
        $testRequest->load('items');

        $items = $testRequest->items;

        if ($items->isEmpty()) {
            return;
        }

        $totalItems = $items->count();

        $readyCount = $items
            ->where('result_status', TestRequestItem::RESULT_READY)
            ->count();

        $sentCount = $items
            ->where('result_status', TestRequestItem::RESULT_SENT)
            ->count();

        // Every result has been sent
        if ($sentCount === $totalItems) {
            $testRequest->update([
               'overall_status' => TestRequest::STATUS_COMPLETED,
            ]);

            return;
        }

        // Every result is ready (or already sent)
        if (($readyCount + $sentCount) === $totalItems) {
            $testRequest->update([
                'overall_status' => TestRequest::STATUS_COMPLETED,
            ]);

            return;
        }

        // Some results are ready/sent but not all
        if (($readyCount + $sentCount) > 0) {
            $testRequest->update([
               'overall_status' => TestRequest::STATUS_PARTIALLY_COMPLETED,
            ]);

            return;
        }

        // Nothing uploaded yet
        $testRequest->update([
          'overall_status' => TestRequest::STATUS_IN_PROGRESS,
        ]);
    }
}