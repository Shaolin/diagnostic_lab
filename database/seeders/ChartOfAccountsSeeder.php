<?php
namespace Database\Seeders;

use App\Models\Laboratory;
use App\Services\ChartOfAccountsService;
use Illuminate\Database\Seeder;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chartOfAccountsService = app(ChartOfAccountsService::class);

        Laboratory::chunkById(100, function ($laboratories) use ($chartOfAccountsService) {
            foreach ($laboratories as $laboratory) {
                $chartOfAccountsService->initializeFor($laboratory);
            }
        });
    }
}

