<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;

class MonthlyFinancialReportController extends Controller
{
    public function index(Request $request)
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $month = $request->input('month', now()->format('Y-m'));

        $from = $month . '-01';
        $to = date('Y-m-t', strtotime($from));

        /*
        |--------------------------------------------------------------------------
        | Income
        |--------------------------------------------------------------------------
        */

        $incomeAccountIds = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->whereIn('code', ['4100', '4200'])
            ->pluck('id');

        $income = JournalEntryLine::whereIn('account_id', $incomeAccountIds)
            ->whereHas('journalEntry', function ($query) use ($laboratoryId, $from, $to) {
                $query->where('laboratory_id', $laboratoryId)
                    ->whereBetween('entry_date', [$from, $to])
                    ->where('status', 'posted');
            })
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

        $expenses = JournalEntryLine::whereIn('account_id', $expenseAccountIds)
            ->whereHas('journalEntry', function ($query) use ($laboratoryId, $from, $to) {
                $query->where('laboratory_id', $laboratoryId)
                    ->whereBetween('entry_date', [$from, $to])
                    ->where('status', 'posted');
            })
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

        $cashInflows = JournalEntryLine::whereIn('account_id', $cashAccountIds)
            ->whereHas('journalEntry', function ($query) use ($laboratoryId, $from, $to) {
                $query->where('laboratory_id', $laboratoryId)
                    ->whereBetween('entry_date', [$from, $to])
                    ->where('status', 'posted');
            })
            ->sum('debit');

        $cashOutflows = JournalEntryLine::whereIn('account_id', $cashAccountIds)
            ->whereHas('journalEntry', function ($query) use ($laboratoryId, $from, $to) {
                $query->where('laboratory_id', $laboratoryId)
                    ->whereBetween('entry_date', [$from, $to])
                    ->where('status', 'posted');
            })
            ->sum('credit');

        $netCashFlow = $cashInflows - $cashOutflows;

        /*
        |--------------------------------------------------------------------------
        | Return Report
        |--------------------------------------------------------------------------
        */

        return view('accounting.monthly-financial-report.index', compact(
            'month',
            'from',
            'to',
            'income',
            'expenses',
            'netProfit',
            'cashInflows',
            'cashOutflows',
            'netCashFlow'
        ));
    }
}