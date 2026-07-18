<?php

namespace App\Services;

use App\Models\Patient;

class PatientNumberGenerator
{
    /**
     * Generate the next patient number for a laboratory.
     *
     * Format: PAT-000001
     */
    public function generate(int $laboratoryId): string
    {
        $lastPatient = Patient::where('laboratory_id', $laboratoryId)
            ->orderByDesc('id')
            ->first();

        if (! $lastPatient) {
            return 'PAT-000001';
        }

        // Extract numeric part from PAT-000123
        $lastNumber = (int) substr($lastPatient->patient_number, 4);

        $nextNumber = $lastNumber + 1;

        return 'PAT-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
}