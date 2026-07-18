<?php

namespace App\Services;

use App\Models\TestType;

class TestTypeCodeGenerator
{
    /**
     * Generate the next test code for a laboratory.
     *
     * Example:
     * TT-000001
     * TT-000002
     */
    public function generate(int $laboratoryId): string
    {
        $lastCode = TestType::where('laboratory_id', $laboratoryId)
            ->orderByDesc('id')
            ->value('code');

        if (! $lastCode) {
            return 'TT-000001';
        }

        $number = (int) substr($lastCode, 3);

        return 'TT-' . str_pad($number + 1, 6, '0', STR_PAD_LEFT);
    }
}