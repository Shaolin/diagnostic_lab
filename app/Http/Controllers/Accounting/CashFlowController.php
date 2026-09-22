<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;

class CashFlowController extends Controller
{
    public function index(Request $request)
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $from = $request->input(
            'from',
            now()->startOfMonth()->toDateString()
        );

        $to = $request->input(
            'to',
            now()->toDateString()
        );

        /*
        |--------------------------------------------------------------------------
        | Cash, Petty Cash & Bank Accounts
        |--------------------------------------------------------------------------
        */

        $cashAccountIds = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->whereIn('code', ['1100', '1150', '1200'])
            ->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | Opening Cash Balance
        |--------------------------------------------------------------------------
        */

        $openingDebit = JournalEntryLine::whereIn('account_id', $cashAccountIds)
            ->whereHas('journalEntry', function ($query) use ($laboratoryId, $from) {
                $query->where('laboratory_id', $laboratoryId)
                    ->where('entry_date', '<', $from)
                    ->where('status', 'posted');
            })
            ->sum('debit');

        $openingCredit = JournalEntryLine::whereIn('account_id', $cashAccountIds)
            ->whereHas('journalEntry', function ($query) use ($laboratoryId, $from) {
                $query->where('laboratory_id', $laboratoryId)
                    ->where('entry_date', '<', $from)
                    ->where('status', 'posted');
            })
            ->sum('credit');

        $openingBalance = $openingDebit - $openingCredit;

        /*
        |--------------------------------------------------------------------------
        | Cash Movement During Selected Period
        |--------------------------------------------------------------------------
        */

        $lines = JournalEntryLine::with('journalEntry')
            ->whereIn('account_id', $cashAccountIds)
            ->whereHas('journalEntry', function ($query) use ($laboratoryId, $from, $to) {
                $query->where('laboratory_id', $laboratoryId)
                    ->whereBetween('entry_date', [$from, $to])
                    ->where('status', 'posted');
            })
            ->get();

        $operatingInflows = 0;
        $operatingOutflows = 0;

        $investingInflows = 0;
        $investingOutflows = 0;

        $financingInflows = 0;
        $financingOutflows = 0;

        foreach ($lines as $line) {

            /*
            |--------------------------------------------------------------------------
            | Get the other side of the journal entry
            |--------------------------------------------------------------------------
            */

            $otherLines = JournalEntryLine::where('journal_entry_id', $line->journal_entry_id)
                ->where('id', '!=', $line->id)
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Ignore transfers between Cash, Petty Cash and Bank
            |--------------------------------------------------------------------------
            */

            if ($otherLines->contains(function ($otherLine) use ($cashAccountIds) {
                return $cashAccountIds->contains($otherLine->account_id);
            })) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Process each other account
            |--------------------------------------------------------------------------
            */

            foreach ($otherLines as $otherLine) {

                $otherAccount = ChartOfAccount::find($otherLine->account_id);

                if (!$otherAccount) {
                    continue;
                }

                $amount = (float) ($line->debit > 0
                    ? $line->debit
                    : $line->credit);

                /*
                |--------------------------------------------------------------------------
                | Operating Activities
                |--------------------------------------------------------------------------
                */

                if (in_array($otherAccount->code, ['4100', '4200'])) {

                    if ($line->debit > 0) {
                        $operatingInflows += $amount;
                    }
                }

                elseif (in_array($otherAccount->code, [
                    '5100',
                    '5200',
                    '5300',
                    '5400',
                    '5500',
                    '5600',
                    '5700',
                ])) {

                    if ($line->credit > 0) {
                        $operatingOutflows += $amount;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Investing Activities
                |--------------------------------------------------------------------------
                */

                elseif ($otherAccount->code === '1500') {

                    if ($line->credit > 0) {
                        $investingOutflows += $amount;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Financing Activities
                |--------------------------------------------------------------------------
                */

                elseif ($otherAccount->code === '2200') {

                    if ($line->debit > 0) {
                        $financingInflows += $amount;
                    } elseif ($line->credit > 0) {
                        $financingOutflows += $amount;
                    }
                }

                elseif ($otherAccount->code === '3100') {

                    if ($line->credit > 0) {
                        $financingInflows += $amount;
                    } elseif ($line->debit > 0) {
                        $financingOutflows += $amount;
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Totals
        |--------------------------------------------------------------------------
        */

        $operatingNet = $operatingInflows - $operatingOutflows;

        $investingNet = $investingInflows - $investingOutflows;

        $financingNet = $financingInflows - $financingOutflows;

        $netCashFlow = $operatingNet
            + $investingNet
            + $financingNet;

        $closingBalance = $openingBalance + $netCashFlow;

        return view('accounting.cash-flow.index', compact(
            'from',
            'to',
            'openingBalance',
            'operatingInflows',
            'operatingOutflows',
            'operatingNet',
            'investingInflows',
            'investingOutflows',
            'investingNet',
            'financingInflows',
            'financingOutflows',
            'financingNet',
            'netCashFlow',
            'closingBalance'
        ));
    }
}