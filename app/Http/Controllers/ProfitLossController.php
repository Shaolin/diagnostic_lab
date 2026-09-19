<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfitLossController extends Controller
{
    public function index(Request $request): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $branches = Branch::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $query = JournalEntryLine::query()
            ->with('account')
            ->whereHas('journalEntry', function ($query) use ($laboratoryId) {
                $query->where('laboratory_id', $laboratoryId)
                    ->where('status', 'posted');
            });

        $query->when($request->from, function ($query, $from) {
            $query->whereHas('journalEntry', function ($query) use ($from) {
                $query->whereDate('entry_date', '>=', $from);
            });
        });

        $query->when($request->to, function ($query, $to) {
            $query->whereHas('journalEntry', function ($query) use ($to) {
                $query->whereDate('entry_date', '<=', $to);
            });
        });

        $query->when($request->branch_id, function ($query, $branchId) {
            $query->whereHas('journalEntry', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            });
        });

        $incomeAccounts = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->where('type', 'income')
            ->orderBy('code')
            ->get();

        $expenseAccounts = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->where('type', 'expense')
            ->orderBy('code')
            ->get();

        $income = [];
        $expenses = [];

        foreach ($incomeAccounts as $account) {
            $amount = (clone $query)
                ->where('account_id', $account->id)
                ->sum('credit');

            if ($amount > 0) {
                $income[] = [
                    'account' => $account,
                    'amount' => $amount,
                ];
            }
        }

        foreach ($expenseAccounts as $account) {
            $amount = (clone $query)
                ->where('account_id', $account->id)
                ->sum('debit');

            if ($amount > 0) {
                $expenses[] = [
                    'account' => $account,
                    'amount' => $amount,
                ];
            }
        }

        $totalIncome = collect($income)->sum('amount');
        $totalExpenses = collect($expenses)->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;

        return view('accounting.profit-loss.index', compact(
            'branches',
            'income',
            'expenses',
            'totalIncome',
            'totalExpenses',
            'netProfit'
        ));
    }
}