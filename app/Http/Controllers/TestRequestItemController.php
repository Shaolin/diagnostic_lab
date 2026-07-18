<?php

namespace App\Http\Controllers;

use App\Models\TestRequest;
use App\Models\TestRequestItem;
use App\Services\TestRequestStatusService;

class TestRequestItemController extends Controller
{
    public function __construct(
        protected TestRequestStatusService $statusService
    ) {
    }

    /**
     * Ensure the item belongs to the authenticated user's laboratory.
     */
    protected function authorizeItem(TestRequestItem $item): void
    {
        abort_if(
            $item->testRequest->laboratory_id !== auth()->user()->laboratory_id,
            403
        );
    }

    /**
     * Mark sample as collected.
     */
    public function collectSample(TestRequestItem $testRequestItem)
    {
        $this->authorizeItem($testRequestItem);

        $testRequestItem->update([
            'sample_status' => TestRequestItem::SAMPLE_COLLECTED,
        ]);

        return back()->with(
            'success',
            'Sample marked as collected.'
        );
    }

    /**
     * Start the laboratory test.
     */
    public function start(TestRequestItem $testRequestItem)
    {
        $this->authorizeItem($testRequestItem);

        $testRequestItem->update([
            'status' => TestRequestItem::STATUS_IN_PROGRESS,
        ]);

        $this->statusService->update(
            $testRequestItem->testRequest
        );

        return back()->with(
            'success',
            'Test started successfully.'
        );
    }

    /**
     * Mark test as completed.
     */
    public function complete(TestRequestItem $testRequestItem)
    {
        $this->authorizeItem($testRequestItem);

        $testRequestItem->update([
            'status' => TestRequestItem::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        $this->statusService->update(
            $testRequestItem->testRequest
        );

        return back()->with(
            'success',
            'Test completed successfully.'
        );
    }

    /**
     * Mark result as ready.
     */
    public function markResultReady(TestRequestItem $testRequestItem)
    {
        $this->authorizeItem($testRequestItem);

        $testRequestItem->update([
            'result_status' => TestRequestItem::RESULT_READY,
        ]);

        return back()->with(
            'success',
            'Result marked as ready.'
        );
    }

    /**
     * Mark result as sent.
     */
    public function markResultSent(TestRequestItem $testRequestItem)
    {
        $this->authorizeItem($testRequestItem);

        $testRequestItem->update([
            'result_status' => TestRequestItem::RESULT_SENT,
        ]);

        return back()->with(
            'success',
            'Result marked as sent.'
        );
    }
}