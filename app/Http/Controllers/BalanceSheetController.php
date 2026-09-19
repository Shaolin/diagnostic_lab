<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BalanceSheetController extends Controller
{
    public function index(Request $request): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $branches = Branch::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $asAt = $request->as_at ?? now()->toDateString();

        $query = JournalEntryLine::query()
            ->whereHas('journalEntry', function ($query) use ($laboratoryId, $asAt) {
                $query->where('laboratory_id', $laboratoryId)
                    ->where('status', 'posted')
                    ->whereDate('entry_date', '<=', $asAt);
            });

        $query->when($request->branch_id, function ($query, $branchId) {
            $query->whereHas('journalEntry', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            });
        });

        $accountBalances = function ($type) use ($query, $laboratoryId) {
            $accounts = ChartOfAccount::where('laboratory_id', $laboratoryId)
                ->where('type', $type)
                ->orderBy('code')
                ->get();

            return $accounts->map(function ($account) use ($query) {
                $debit = (clone $query)
                    ->where('account_id', $account->id)
                    ->sum('debit');

                $credit = (clone $query)
                    ->where('account_id', $account->id)
                    ->sum('credit');

                return [
                    'account' => $account,
                    'balance' => $debit - $credit,
                ];
            })->filter(fn ($item) => $item['balance'] != 0)->values();
        };

        $assets = $accountBalances('asset');
        $liabilities = $accountBalances('liability');
        $equity = $accountBalances('equity');

        $totalAssets = $assets->sum('balance');
        $totalLiabilities = $liabilities->sum('balance');
        $totalEquity = $equity->sum('balance');

        // Current period profit/loss
        $income = (clone $query)
            ->whereHas('account', function ($query) {
                $query->where('type', 'income');
            })
            ->sum('credit');

        $expenses = (clone $query)
            ->whereHas('account', function ($query) {
                $query->where('type', 'expense');
            })
            ->sum('debit');

        $currentProfitLoss = $income - $expenses;

        $totalEquityWithProfit = $totalEquity + $currentProfitLoss;
        $totalLiabilitiesAndEquity = $totalLiabilities + $totalEquityWithProfit;

        return view('accounting.balance-sheet.index', compact(
            'branches',
            'asAt',
            'assets',
            'liabilities',
            'equity',
            'totalAssets',
            'totalLiabilities',
            'totalEquity',
            'currentProfitLoss',
            'totalEquityWithProfit',
            'totalLiabilitiesAndEquity'
        ));
    }
}