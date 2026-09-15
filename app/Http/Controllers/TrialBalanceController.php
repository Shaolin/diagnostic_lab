<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrialBalanceController extends Controller
{
    public function index(Request $request): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $from = $request->input('from');
        $to = $request->input('to');

        $accounts = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $accounts->each(function ($account) use ($laboratoryId, $from, $to) {
            $account->debit_total = $account->journalEntryLines()
                ->whereHas('journalEntry', function ($query) use ($laboratoryId, $from, $to) {
                    $query->where('laboratory_id', $laboratoryId)
                        ->where('status', 'posted');

                    if ($from) {
                        $query->whereDate('entry_date', '>=', $from);
                    }

                    if ($to) {
                        $query->whereDate('entry_date', '<=', $to);
                    }
                })
                ->sum('debit');

            $account->credit_total = $account->journalEntryLines()
                ->whereHas('journalEntry', function ($query) use ($laboratoryId, $from, $to) {
                    $query->where('laboratory_id', $laboratoryId)
                        ->where('status', 'posted');

                    if ($from) {
                        $query->whereDate('entry_date', '>=', $from);
                    }

                    if ($to) {
                        $query->whereDate('entry_date', '<=', $to);
                    }
                })
                ->sum('credit');
        });

        $accounts = $accounts->filter(function ($account) {
            return $account->debit_total > 0
                || $account->credit_total > 0;
        });

        $totalDebit = $accounts->sum('debit_total');
        $totalCredit = $accounts->sum('credit_total');

        return view('accounting.trial-balance.index', compact(
            'accounts',
            'from',
            'to',
            'totalDebit',
            'totalCredit'
        ));
    }
}