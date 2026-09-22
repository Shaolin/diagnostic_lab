<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;

class BranchReportController extends Controller
{
    public function index(Request $request)
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $month = $request->input('month', now()->format('Y-m'));

        $from = $month . '-01';
        $to = date('Y-m-t', strtotime($from));

        $branchId = $request->input('branch_id');

        /*
        |--------------------------------------------------------------------------
        | Branches
        |--------------------------------------------------------------------------
        */

        $branches = Branch::where('laboratory_id', $laboratoryId)
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Base Journal Query
        |--------------------------------------------------------------------------
        */

        $baseQuery = JournalEntryLine::query()
            ->whereHas('journalEntry', function ($query) use (
                $laboratoryId,
                $from,
                $to,
                $branchId
            ) {
                $query->where('laboratory_id', $laboratoryId)
                    ->whereBetween('entry_date', [$from, $to])
                    ->where('status', 'posted');

                if ($branchId) {
                    $query->where('branch_id', $branchId);
                }
            });

        /*
        |--------------------------------------------------------------------------
        | Income
        |--------------------------------------------------------------------------
        */

        $incomeAccountIds = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->whereIn('code', ['4100', '4200'])
            ->pluck('id');

        $income = (clone $baseQuery)
            ->whereIn('account_id', $incomeAccountIds)
            ->sum('credit');

        /*
        |--------------------------------------------------------------------------
        | Expenses
        |--------------------------------------------------------------------------
        */

        $expenseAccountIds = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->whereIn('code', [
                '5100',
                '5200',
                '5300',
                '5400',
                '5500',
                '5600',
                '5700',
                '5800',
            ])
            ->pluck('id');

        $expenses = (clone $baseQuery)
            ->whereIn('account_id', $expenseAccountIds)
            ->sum('debit');

        /*
        |--------------------------------------------------------------------------
        | Net Profit / Loss
        |--------------------------------------------------------------------------
        */

        $netProfit = $income - $expenses;

        /*
        |--------------------------------------------------------------------------
        | Cash & Bank
        |--------------------------------------------------------------------------
        */

        $cashAccountIds = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->whereIn('code', ['1100', '1150', '1200'])
            ->pluck('id');

        $cashInflows = (clone $baseQuery)
            ->whereIn('account_id', $cashAccountIds)
            ->sum('debit');

        $cashOutflows = (clone $baseQuery)
            ->whereIn('account_id', $cashAccountIds)
            ->sum('credit');

        $netCashFlow = $cashInflows - $cashOutflows;

        /*
        |--------------------------------------------------------------------------
        | Report Type
        |--------------------------------------------------------------------------
        */

        $reportType = $branchId ? 'branch' : 'consolidated';

        $selectedBranch = $branchId
            ? $branches->firstWhere('id', $branchId)
            : null;

        return view('accounting.branch-reports.index', compact(
            'month',
            'from',
            'to',
            'branchId',
            'branches',
            'selectedBranch',
            'reportType',
            'income',
            'expenses',
            'netProfit',
            'cashInflows',
            'cashOutflows',
            'netCashFlow'
        ));
    }
}