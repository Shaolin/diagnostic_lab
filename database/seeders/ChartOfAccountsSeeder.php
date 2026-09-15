<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Models\Laboratory;
use Illuminate\Database\Seeder;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laboratories = Laboratory::all();

        foreach ($laboratories as $laboratory) {

            // Assets
            $assets = ChartOfAccount::firstOrCreate(
                [
                    'laboratory_id' => $laboratory->id,
                    'code' => '1000',
                ],
                [
                    'name' => 'Assets',
                    'type' => 'asset',
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => 100,
                ]
            );

            $this->createAccount($laboratory->id, $assets->id, '1100', 'Cash', 'asset', 110);
            $this->createAccount($laboratory->id, $assets->id, '1200', 'Bank', 'asset', 120);
            $this->createAccount($laboratory->id, $assets->id, '1300', 'Accounts Receivable', 'asset', 130);
            $this->createAccount($laboratory->id, $assets->id, '1400', 'Inventory', 'asset', 140);
            $this->createAccount($laboratory->id, $assets->id, '1500', 'Fixed Assets', 'asset', 150);

            // Liabilities
            $liabilities = ChartOfAccount::firstOrCreate(
                [
                    'laboratory_id' => $laboratory->id,
                    'code' => '2000',
                ],
                [
                    'name' => 'Liabilities',
                    'type' => 'liability',
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => 200,
                ]
            );

            $this->createAccount($laboratory->id, $liabilities->id, '2100', 'Accounts Payable', 'liability', 210);
            $this->createAccount($laboratory->id, $liabilities->id, '2200', 'Loans Payable', 'liability', 220);

            // Equity
            $equity = ChartOfAccount::firstOrCreate(
                [
                    'laboratory_id' => $laboratory->id,
                    'code' => '3000',
                ],
                [
                    'name' => 'Equity',
                    'type' => 'equity',
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => 300,
                ]
            );

            $this->createAccount($laboratory->id, $equity->id, '3100', "Owner's / Company Equity", 'equity', 310);

            // Income
            $income = ChartOfAccount::firstOrCreate(
                [
                    'laboratory_id' => $laboratory->id,
                    'code' => '4000',
                ],
                [
                    'name' => 'Income',
                    'type' => 'income',
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => 400,
                ]
            );

            $this->createAccount($laboratory->id, $income->id, '4100', 'Laboratory Services', 'income', 410);
            $this->createAccount($laboratory->id, $income->id, '4200', 'Other Income', 'income', 420);

            // Expenses
            $expenses = ChartOfAccount::firstOrCreate(
                [
                    'laboratory_id' => $laboratory->id,
                    'code' => '5000',
                ],
                [
                    'name' => 'Expenses',
                    'type' => 'expense',
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => 500,
                ]
            );

            $this->createAccount($laboratory->id, $expenses->id, '5100', 'Salaries', 'expense', 510);
            $this->createAccount($laboratory->id, $expenses->id, '5200', 'Reagents & Laboratory Supplies', 'expense', 520);
            $this->createAccount($laboratory->id, $expenses->id, '5300', 'Transport & Logistics', 'expense', 530);
            $this->createAccount($laboratory->id, $expenses->id, '5400', 'Utilities', 'expense', 540);
            $this->createAccount($laboratory->id, $expenses->id, '5500', 'Repairs & Maintenance', 'expense', 550);
            $this->createAccount($laboratory->id, $expenses->id, '5600', 'Stationery', 'expense', 560);
            $this->createAccount($laboratory->id, $expenses->id, '5700', 'Other Expenses', 'expense', 570);
        }
    }

    private function createAccount(
        int $laboratoryId,
        int $parentId,
        string $code,
        string $name,
        string $type,
        int $sortOrder
    ): ChartOfAccount {
        return ChartOfAccount::firstOrCreate(
            [
                'laboratory_id' => $laboratoryId,
                'code' => $code,
            ],
            [
                'parent_id' => $parentId,
                'name' => $name,
                'type' => $type,
                'is_system' => true,
                'is_active' => true,
                'sort_order' => $sortOrder,
            ]
        );
    }
}