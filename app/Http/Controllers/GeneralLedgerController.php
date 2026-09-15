<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Services\GeneralLedgerService;
use Illuminate\Http\Request;
use App\Models\Branch;

class GeneralLedgerController extends Controller
{
    public function __construct(
        private GeneralLedgerService $ledgerService
    ) {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $laboratoryId = $user->laboratory_id;
        $branches = Branch::where('laboratory_id', $laboratoryId)
    ->where('is_active', true)
    ->orderBy('name')
    ->get();

        $accountId = $request->integer('account_id') ?: null;
        $branchId = $request->integer('branch_id') ?: null;

        $from = $request->input('from');
        $to = $request->input('to');

        $accounts = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $ledger = $this->ledgerService->query(
            $laboratoryId,
            $branchId,
            $accountId,
            $from,
            $to
        )->paginate(20)
->withQueryString();

        $totalDebit = $ledger->sum('debit');
        $totalCredit = $ledger->sum('credit');

        return view('accounting.index', compact(
            'ledger',
            'accounts',
             'branches',
            'totalDebit',
            'totalCredit',
            'from',
            'to',
            'accountId',
            'branchId'
        ));
    }
}