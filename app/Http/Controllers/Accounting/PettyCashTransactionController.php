<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\ChartOfAccount;
use App\Models\PettyCashFund;
use App\Models\PettyCashTransaction;
use Illuminate\Http\Request;
use App\Services\JournalEntryService;
use Illuminate\Support\Facades\DB;

class PettyCashTransactionController extends Controller
{
    public function index(PettyCashFund $pettyCashFund)
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $pettyCashFund = PettyCashFund::where('id', $pettyCashFund->id)
            ->where('laboratory_id', $laboratoryId)
            ->with(['branch', 'custodian'])
            ->firstOrFail();

        $transactions = $pettyCashFund->transactions()
            ->with(['account', 'sourceAccount', 'recordedBy'])
            ->latest('transaction_date')
            ->latest('id')
            ->get();

        return view('accounting.petty-cash.transactions.index', compact(
            'pettyCashFund',
            'transactions'
        ));
    }

    public function create(PettyCashFund $pettyCashFund)
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $pettyCashFund = PettyCashFund::where('id', $pettyCashFund->id)
            ->where('laboratory_id', $laboratoryId)
            ->firstOrFail();

        $expenseAccounts = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->where('type', 'expense')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $sourceAccounts = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->whereIn('type', ['asset'])
            ->where('is_active', true)
            ->whereIn('name', ['Cash', 'Bank'])
            ->orderBy('name')
            ->get();

        return view('accounting.petty-cash.transactions.create', compact(
            'pettyCashFund',
            'expenseAccounts',
            'sourceAccounts'
        ));
    }

   public function store(
    Request $request,
    PettyCashFund $pettyCashFund,
    JournalEntryService $journalEntryService
) {
    $laboratoryId = auth()->user()->laboratory_id;

    $pettyCashFund = PettyCashFund::where('id', $pettyCashFund->id)
        ->where('laboratory_id', $laboratoryId)
        ->firstOrFail();

    $validated = $request->validate([
        'type' => [
            'required',
            'in:expense,replenishment',
        ],
        'amount' => [
            'required',
            'numeric',
            'min:0.01',
        ],
        'description' => [
            'required',
            'string',
            'max:255',
        ],
        'reference' => [
            'nullable',
            'string',
            'max:100',
        ],
        'transaction_date' => [
            'required',
            'date',
        ],
        'account_id' => [
            'nullable',
            'exists:chart_of_accounts,id',
        ],
        'source_account_id' => [
            'nullable',
            'exists:chart_of_accounts,id',
        ],
    ]);

    return DB::transaction(function () use (
        $validated,
        $pettyCashFund,
        $laboratoryId,
        $journalEntryService
    ) {

        $pettyCashAccount = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->where('code', '1150')
            ->where('is_active', true)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | EXPENSE
        |--------------------------------------------------------------------------
        */

        if ($validated['type'] === 'expense') {

            if (empty($validated['account_id'])) {
                return back()
                    ->withErrors([
                        'account_id' => 'Please select an expense account.',
                    ])
                    ->withInput();
            }

            if ((float) $validated['amount'] > (float) $pettyCashFund->current_balance) {
                return back()
                    ->withErrors([
                        'amount' => 'The expense amount cannot be greater than the current petty cash balance.',
                    ])
                    ->withInput();
            }

            $account = ChartOfAccount::where('id', $validated['account_id'])
                ->where('laboratory_id', $laboratoryId)
                ->where('type', 'expense')
                ->where('is_active', true)
                ->firstOrFail();

            $pettyCashFund->decrement(
                'current_balance',
                $validated['amount']
            );

            $transaction = PettyCashTransaction::create([
                'laboratory_id' => $laboratoryId,
                'branch_id' => $pettyCashFund->branch_id,
                'petty_cash_fund_id' => $pettyCashFund->id,
                'account_id' => $account->id,
                'source_account_id' => null,
                'recorded_by' => auth()->id(),
                'type' => 'expense',
                'amount' => $validated['amount'],
                'description' => $validated['description'],
                'reference' => $validated['reference'] ?? null,
                'transaction_date' => $validated['transaction_date'],
            ]);

            $journalEntryService->create(
                [
                    'laboratory_id' => $laboratoryId,
                    'branch_id' => $pettyCashFund->branch_id,
                    'entry_date' => $validated['transaction_date'],
                    'reference' => 'PETTY-' . $transaction->id,
                    'description' => $validated['description'],
                    'source_type' => PettyCashTransaction::class,
                    'source_id' => $transaction->id,
                    'created_by' => auth()->id(),
                    'status' => 'posted',
                    'posted_at' => now(),
                ],
                [
                    [
                        'account_id' => $account->id,
                        'debit' => $validated['amount'],
                        'credit' => 0,
                    ],
                    [
                        'account_id' => $pettyCashAccount->id,
                        'debit' => 0,
                        'credit' => $validated['amount'],
                    ],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | REPLENISHMENT
        |--------------------------------------------------------------------------
        */

        if ($validated['type'] === 'replenishment') {

            if (empty($validated['source_account_id'])) {
                return back()
                    ->withErrors([
                        'source_account_id' => 'Please select the source cash or bank account.',
                    ])
                    ->withInput();
            }

            $sourceAccount = ChartOfAccount::where('id', $validated['source_account_id'])
                ->where('laboratory_id', $laboratoryId)
                ->where('type', 'asset')
                ->whereIn('name', ['Cash', 'Bank'])
                ->where('is_active', true)
                ->firstOrFail();

            $pettyCashFund->increment(
                'current_balance',
                $validated['amount']
            );

            $transaction = PettyCashTransaction::create([
                'laboratory_id' => $laboratoryId,
                'branch_id' => $pettyCashFund->branch_id,
                'petty_cash_fund_id' => $pettyCashFund->id,
                'account_id' => null,
                'source_account_id' => $sourceAccount->id,
                'recorded_by' => auth()->id(),
                'type' => 'replenishment',
                'amount' => $validated['amount'],
                'description' => $validated['description'],
                'reference' => $validated['reference'] ?? null,
                'transaction_date' => $validated['transaction_date'],
            ]);

            $journalEntryService->create(
                [
                    'laboratory_id' => $laboratoryId,
                    'branch_id' => $pettyCashFund->branch_id,
                    'entry_date' => $validated['transaction_date'],
                    'reference' => 'PETTY-' . $transaction->id,
                    'description' => $validated['description'],
                    'source_type' => PettyCashTransaction::class,
                    'source_id' => $transaction->id,
                    'created_by' => auth()->id(),
                    'status' => 'posted',
                    'posted_at' => now(),
                ],
                [
                    [
                        'account_id' => $pettyCashAccount->id,
                        'debit' => $validated['amount'],
                        'credit' => 0,
                    ],
                    [
                        'account_id' => $sourceAccount->id,
                        'debit' => 0,
                        'credit' => $validated['amount'],
                    ],
                ]
            );
        }

        return redirect()
            ->route('accounting.petty-cash.transactions.index', $pettyCashFund)
            ->with('success', 'Petty cash transaction recorded successfully.');
    });
}
}