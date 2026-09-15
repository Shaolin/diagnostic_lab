<?php

namespace App\Actions;

use App\Models\Branch;

class CreateBranch
{
    public function execute(array $data): Branch
    {
        return Branch::create([
            'laboratory_id' => auth()->user()->laboratory_id,
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'is_active' => $data['is_active'] ?? false,
        ]);
    }
}