<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankReconciliation;
use App\Models\BankReconciliationItem;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankReconciliationController extends Controller
{
    public function index(Request $request): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $query = BankReconciliation::with(['branch', 'bankAccount', 'createdBy'])
            ->where('laboratory_id', $laboratoryId)
            ->latest('statement_date')
            ->latest('id');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reconciliations = $query
            ->paginate(20)
            ->withQueryString();

        $branches = Branch::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('accounting.bank-reconciliation.index', compact(
            'reconciliations',
            'branches'
        ));
    }

    public function create(): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $bankAccounts = BankAccount::where('laboratory_id', $laboratoryId)
    ->where('is_active', true)
    ->with('branch')
    ->orderBy('bank_name')
    ->get();

        $branches = Branch::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('accounting.bank-reconciliation.create', compact(
            'bankAccounts',
            'branches'
        ));
    }

    public function store(Request $request)
{
    $laboratoryId = auth()->user()->laboratory_id;

    $validated = $request->validate([
       'bank_account_id' => ['required','exists:bank_accounts,id'],
        'branch_id' => [
            'nullable',
            'exists:branches,id',
        ],
        'statement_date' => [
            'required',
            'date',
        ],
        'statement_opening_balance' => [
            'required',
            'numeric',
            'min:0',
        ],
        'statement_closing_balance' => [
            'required',
            'numeric',
            'min:0',
        ],
        'notes' => [
            'nullable',
            'string',
        ],
    ]);

    $bankAccount = BankAccount::where('laboratory_id', $laboratoryId)
    ->where('id', $validated['bank_account_id'])
    ->where('is_active', true)
    ->firstOrFail();

    if (!empty($validated['branch_id'])) {
        Branch::where('laboratory_id', $laboratoryId)
            ->where('id', $validated['branch_id'])
            ->where('is_active', true)
            ->firstOrFail();
    }

    $reconciliation = BankReconciliation::create([
        'laboratory_id' => $laboratoryId,
        'branch_id' => $validated['branch_id'] ?? null,
        'bank_account_id' => $bankAccount->id,
        'statement_date' => $validated['statement_date'],
        'statement_opening_balance' => $validated['statement_opening_balance'],
        'statement_closing_balance' => $validated['statement_closing_balance'],
        'reconciled_balance' => 0,
        'difference' => $validated['statement_closing_balance'],
        'status' => 'open',
        'notes' => $validated['notes'] ?? null,
        'created_by' => auth()->id(),
    ]);

    return redirect()
        ->route('accounting.bank-reconciliation.show', $reconciliation)
        ->with('success', 'Bank reconciliation created successfully.');
}

public function show(BankReconciliation $bankReconciliation): View
{
    $laboratoryId = auth()->user()->laboratory_id;

    abort_unless(
        $bankReconciliation->laboratory_id === $laboratoryId,
        403
    );

    $bankReconciliation->load([
        'branch',
        'bankAccount',
        'createdBy',
        'items.journalEntryLine.journalEntry',
    ]);

    $reconciledLineIds = $bankReconciliation->items
        ->pluck('journal_entry_line_id')
        ->toArray();

    $query = \App\Models\JournalEntryLine::with('journalEntry')
        ->whereHas('account', function ($query) use ($laboratoryId) {
    $query->where('laboratory_id', $laboratoryId)
        ->where('code', '1200');
})
        ->whereHas('journalEntry', function ($query) use ($laboratoryId, $bankReconciliation) {
            $query->where('laboratory_id', $laboratoryId)
                ->where('status', 'posted');

            if ($bankReconciliation->branch_id) {
                $query->where('branch_id', $bankReconciliation->branch_id);
            }
        })
        ->whereDate(
            'created_at',
            '<=',
            $bankReconciliation->statement_date
        )
        ->orderByDesc('created_at');

    $transactions = $query->paginate(30);

    $bookMovement = (float) (clone $query)
    ->get()
    ->sum(function ($line) {
        return (float) $line->debit - (float) $line->credit;
    });

$bookBalance =
    (float) $bankReconciliation->statement_opening_balance
    + $bookMovement;

    return view(
        'accounting.bank-reconciliation.show',
        compact(
            'bankReconciliation',
            'transactions',
            'reconciledLineIds',
             'bookBalance'
        )
    );
}
public function reconcile(Request $request, BankReconciliation $bankReconciliation)
{
    $laboratoryId = auth()->user()->laboratory_id;

    abort_unless(
        $bankReconciliation->laboratory_id === $laboratoryId,
        403
    );

    $validated = $request->validate([
        'journal_entry_line_ids' => ['required', 'array'],
        'journal_entry_line_ids.*' => ['integer', 'exists:journal_entry_lines,id'],
    ]);

    foreach ($validated['journal_entry_line_ids'] as $lineId) {

        $line = \App\Models\JournalEntryLine::with('journalEntry')
            ->where('id', $lineId)
            ->where('account_id', function ($query) use ($laboratoryId) {
                $query->select('id')
                    ->from('chart_of_accounts')
                    ->where('laboratory_id', $laboratoryId)
                    ->where('code', '1200')
                    ->limit(1);
            })
            ->whereHas('journalEntry', function ($query) use ($laboratoryId, $bankReconciliation) {
                $query->where('laboratory_id', $laboratoryId)
                    ->where('status', 'posted');

                if ($bankReconciliation->branch_id) {
                    $query->where('branch_id', $bankReconciliation->branch_id);
                }
            })
            ->firstOrFail();

        BankReconciliationItem::firstOrCreate([
            'bank_reconciliation_id' => $bankReconciliation->id,
            'journal_entry_line_id' => $line->id,
        ], [
            'reconciled_date' => now()->toDateString(),
        ]);
    }

    $reconciledLines = \App\Models\JournalEntryLine::whereIn(
    'id',
    $bankReconciliation->items()->pluck('journal_entry_line_id')
)->get();

$reconciledMovement = $reconciledLines->sum(function ($line) {
    return (float) $line->debit - (float) $line->credit;
});

$reconciledBalance =
    (float) $bankReconciliation->statement_opening_balance
    + $reconciledMovement;

$difference =
    (float) $bankReconciliation->statement_closing_balance
    - $reconciledBalance;

$bankReconciliation->update([
    'reconciled_balance' => $reconciledBalance,
    'difference' => $difference,
]);

    return back()->with('success', 'Selected transactions reconciled successfully.');
}

public function complete(BankReconciliation $bankReconciliation)
{
    $laboratoryId = auth()->user()->laboratory_id;

    abort_unless(
        $bankReconciliation->laboratory_id === $laboratoryId,
        403
    );

    if ($bankReconciliation->status === 'completed') {
        return back()->with('success', 'This reconciliation is already completed.');
    }

    $bankReconciliation->update([
        'status' => 'completed',
    ]);

    return back()->with(
        'success',
        'Bank reconciliation completed successfully.'
    );
}
}