<?php

namespace App\Services;

class TestRequestCalculator
{
    /**
     * Calculate the total amount for all selected tests.
     */
    public function calculate(array $items): float
    {
        return collect($items)
            ->sum(function ($item) {
                return (float) $item['price'];
            });
    }
}