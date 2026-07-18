<?php

namespace App\Services;

use App\Models\TestRequest;

class TrackingCodeGenerator
{
    /**
     * Generate a unique tracking code.
     */
    public function generate(): string
    {
        $year = now()->year;

        do {
            $number = str_pad(
                random_int(1, 99999),
                5,
                '0',
                STR_PAD_LEFT
            );

            $trackingCode = "TRK-{$year}{$number}";
        } while (
            TestRequest::where('tracking_code', $trackingCode)->exists()
        );

        return $trackingCode;
    }
}