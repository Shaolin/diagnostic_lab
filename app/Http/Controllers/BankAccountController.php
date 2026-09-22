<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Branch;
use App\Models\ChartOfAccount;
use App\Services\JournalEntryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\JournalEntry;


class BankAccountController extends Controller
{
    public function __construct(
        protected JournalEntryService $journalEntryService
    ) {}

    public function index(): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $bankAccounts = BankAccount::with('branch')
            ->where('laboratory_id', $laboratoryId)
            ->latest()
            ->paginate(20);

        return view('accounting.bank-accounts.index', compact('bankAccounts'));
    }

    public function create(): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $branches = Branch::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('accounting.bank-accounts.create', compact('branches'));
    }

    public function edit(BankAccount $bankAccount): View
{
    abort_unless(
        $bankAccount->laboratory_id === auth()->user()->laboratory_id,
        404
    );

    $branches = Branch::where('laboratory_id', auth()->user()->laboratory_id)
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('accounting.bank-accounts.edit', compact(
        'bankAccount',
        'branches'
    ));
}

public function update(Request $request, BankAccount $bankAccount)
{
    $laboratoryId = auth()->user()->laboratory_id;

    abort_unless(
        $bankAccount->laboratory_id === $laboratoryId,
        404
    );

    $validated = $request->validate([
        'bank_name' => [
            'required',
            'string',
            'max:255',
        ],
        'account_name' => [
            'required',
            'string',
            'max:255',
        ],
        'account_number' => [
            'nullable',
            'string',
            'max:50',
        ],
        'branch_id' => [
            'nullable',
            'exists:branches,id',
        ],
    ]);

    if (!empty($validated['branch_id'])) {
        Branch::where('laboratory_id', $laboratoryId)
            ->where('id', $validated['branch_id'])
            ->where('is_active', true)
            ->firstOrFail();
    }

    $bankAccount->update([
        'bank_name' => $validated['bank_name'],
        'account_name' => $validated['account_name'],
        'account_number' => $validated['account_number'] ?? null,
        'branch_id' => $validated['branch_id'] ?? null,
    ]);

    return redirect()
        ->route('accounting.bank-accounts.index')
        ->with('success', 'Bank account updated successfully.');
}

    public function store(Request $request)
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $validated = $request->validate([
            'bank_name' => [
                'required',
                'string',
                'max:255',
            ],
            'account_name' => [
                'required',
                'string',
                'max:255',
            ],
            'account_number' => [
                'nullable',
                'string',
                'max:50',
            ],
            'branch_id' => [
                'nullable',
                'exists:branches,id',
            ],
            'opening_balance' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        if (!empty($validated['branch_id'])) {
            Branch::where('laboratory_id', $laboratoryId)
                ->where('id', $validated['branch_id'])
                ->where('is_active', true)
                ->firstOrFail();
        }

        DB::transaction(function () use ($validated, $laboratoryId) {

            $bankAccount = BankAccount::create([
                'laboratory_id' => $laboratoryId,
                'branch_id' => $validated['branch_id'] ?? null,
                'bank_name' => $validated['bank_name'],
                'account_name' => $validated['account_name'],
                'account_number' => $validated['account_number'] ?? null,
                'opening_balance' => $validated['opening_balance'],
                'is_active' => true,
            ]);

            /*
             * Opening balance:
             *
             * Debit  → Bank
             * Credit → Owner's / Company Equity
             *
             * The accounts are looked up within the current
             * laboratory rather than using hardcoded IDs.
             */
            if ((float) $bankAccount->opening_balance > 0) {

                $bankCoa = ChartOfAccount::where('laboratory_id', $laboratoryId)
                    ->where('name', 'Bank')
                    ->where('type', 'asset')
                    ->where('is_active', true)
                    ->firstOrFail();

                $equityCoa = ChartOfAccount::where('laboratory_id', $laboratoryId)
                    ->where('name', "Owner's / Company Equity")
                    ->where('type', 'equity')
                    ->where('is_active', true)
                    ->firstOrFail();

                $this->journalEntryService->create([
                    'laboratory_id' => $laboratoryId,
                    'branch_id' => $bankAccount->branch_id,
                    'entry_date' => now()->toDateString(),
                    'reference' => 'OPEN-BANK-' . $bankAccount->id,
                    'description' => 'Opening balance - ' . $bankAccount->bank_name,
                    'source_type' => BankAccount::class,
                    'source_id' => $bankAccount->id,
                    'created_by' => auth()->id(),
                    'status' => 'posted',
                    'posted_at' => now(),
                ], [
                    [
                        'account_id' => $bankCoa->id,
                        'debit' => $bankAccount->opening_balance,
                        'credit' => 0,
                        'description' => 'Opening bank balance',
                    ],
                    [
                        'account_id' => $equityCoa->id,
                        'debit' => 0,
                        'credit' => $bankAccount->opening_balance,
                        'description' => 'Opening equity',
                    ],
                ]);
            }
        });

        return redirect()
            ->route('accounting.bank-accounts.index')
            ->with('success', 'Bank account added successfully.');
    }

    public function destroy(BankAccount $bankAccount)
{
    $laboratoryId = auth()->user()->laboratory_id;

    abort_unless(
        $bankAccount->laboratory_id === $laboratoryId,
        404
    );

    DB::transaction(function () use ($bankAccount) {

        JournalEntry::where('laboratory_id', $bankAccount->laboratory_id)
            ->where('source_type', BankAccount::class)
            ->where('source_id', $bankAccount->id)
            ->delete();

        $bankAccount->delete();
    });

    return redirect()
        ->route('accounting.bank-accounts.index')
        ->with('success', 'Bank account deleted successfully.');
}

public function correctOpeningBalance(BankAccount $bankAccount): View
{
    $laboratoryId = auth()->user()->laboratory_id;

    abort_unless(
        $bankAccount->laboratory_id === $laboratoryId,
        404
    );

    return view(
        'accounting.bank-accounts.correct-opening-balance',
        compact('bankAccount')
    );
}

public function updateOpeningBalance(
    Request $request,
    BankAccount $bankAccount
) {
    $laboratoryId = auth()->user()->laboratory_id;

    abort_unless(
        $bankAccount->laboratory_id === $laboratoryId,
        404
    );

    $validated = $request->validate([
        'opening_balance' => [
            'required',
            'numeric',
            'min:0',
        ],
        'reason' => [
            'required',
            'string',
            'max:1000',
        ],
    ]);

    $oldBalance = (float) $bankAccount->opening_balance;
    $newBalance = (float) $validated['opening_balance'];

    if ($oldBalance === $newBalance) {
        return back()
            ->withErrors([
                'opening_balance' => 'The new opening balance must be different from the current balance.',
            ])
            ->withInput();
    }

    DB::transaction(function () use (
        $bankAccount,
        $laboratoryId,
        $oldBalance,
        $newBalance,
        $validated
    ) {
        $bankAccountAccount = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->where('type', 'asset')
            ->where('name', 'Bank')
            ->where('is_active', true)
            ->firstOrFail();

        $equityAccount = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->where('type', 'equity')
            ->where('name', "Owner's / Company Equity")
            ->where('is_active', true)
            ->firstOrFail();

        $difference = abs($newBalance - $oldBalance);

        if ($newBalance > $oldBalance) {
            // Increase opening balance:
            // Dr Bank
            // Cr Equity

            $lines = [
                [
                    'account_id' => $bankAccountAccount->id,
                    'debit' => $difference,
                    'credit' => 0,
                    'description' => 'Opening balance correction - Bank',
                ],
                [
                    'account_id' => $equityAccount->id,
                    'debit' => 0,
                    'credit' => $difference,
                    'description' => 'Opening balance correction - Equity',
                ],
            ];
        } else {
            // Decrease opening balance:
            // Dr Equity
            // Cr Bank

            $lines = [
                [
                    'account_id' => $equityAccount->id,
                    'debit' => $difference,
                    'credit' => 0,
                    'description' => 'Opening balance correction - Equity',
                ],
                [
                    'account_id' => $bankAccountAccount->id,
                    'debit' => 0,
                    'credit' => $difference,
                    'description' => 'Opening balance correction - Bank',
                ],
            ];
        }

        $this->journalEntryService->create([
            'laboratory_id' => $laboratoryId,
            'branch_id' => $bankAccount->branch_id,
            'entry_date' => now()->toDateString(),
            'reference' => 'OPEN-BANK-CORR-' . $bankAccount->id . '-' . now()->format('YmdHis'),
            'description' => $validated['reason'],
            'source_type' => BankAccount::class,
            'source_id' => $bankAccount->id,
            'created_by' => auth()->id(),
            'status' => 'posted',
            'posted_at' => now(),
        ], $lines);

        $bankAccount->update([
            'opening_balance' => $newBalance,
        ]);
    });

    return redirect()
        ->route('accounting.bank-accounts.index')
        ->with('success', 'Opening balance corrected successfully.');
}
}