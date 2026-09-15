<?php

namespace App\Actions;

use App\Models\Branch;

class DeleteBranch
{
    public function execute(Branch $branch): void
    {
        $branch->delete();
    }
}