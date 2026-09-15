<?php

namespace App\Actions;

use App\Models\Branch;

class UpdateBranch
{
    public function execute(Branch $branch, array $data): Branch
    {
        $branch->update([
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'is_active' => $data['is_active'] ?? false,
        ]);

        return $branch->fresh();
    }
}