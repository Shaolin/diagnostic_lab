<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ChartOfAccount;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\JournalEntryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Services\AuditLogService;

class ExpenseController extends Controller
{

public function __construct(
    protected JournalEntryService $journalEntryService
) {}
    public function index(Request $request): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $query = Expense::with([
            'branch',
            'expenseAccount',
            'createdBy',
        ])
            ->where('laboratory_id', $laboratoryId)
            ->latest('expense_date')
            ->latest('id');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('expense_account_id')) {
            $query->where('expense_account_id', $request->expense_account_id);
        }

        if ($request->filled('from')) {
            $query->whereDate('expense_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('expense_date', '<=', $request->to);
        }

        $expenses = $query->paginate(20)->withQueryString();

        $branches = Branch::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $expenseAccounts = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->where('type', 'expense')
            ->where('is_active', true)
            ->orderBy('code')
            ->get();

        $totalExpenses = (clone $query)->sum('amount');

        return view('accounting.expenses.index', compact(
            'expenses',
            'branches',
            'expenseAccounts',
            'totalExpenses'
        ));
    }

    public function create(): View
{
    $laboratoryId = auth()->user()->laboratory_id;

    $branches = Branch::where('laboratory_id', $laboratoryId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    $expenseAccounts = ChartOfAccount::where('laboratory_id', $laboratoryId)
        ->where('type', 'expense')
        ->where('is_active', true)
        ->orderBy('code')
        ->get();

    return view('accounting.expenses.create', compact(
        'branches',
        'expenseAccounts'
    ));
}

public function store(Request $request)
{
    $laboratoryId = auth()->user()->laboratory_id;

    $validated = $request->validate([
        'branch_id' => [
            'nullable',
            'integer',
            Rule::exists('branches', 'id')
                ->where('laboratory_id', $laboratoryId),
        ],

        'expense_account_id' => [
            'required',
            'integer',
            Rule::exists('chart_of_accounts', 'id')
                ->where('laboratory_id', $laboratoryId)
                ->where('type', 'expense')
                ->where('is_active', true),
        ],

        'amount' => [
            'required',
            'numeric',
            'min:0.01',
        ],

        'payment_method' => [
            'required',
            'string',
            Rule::in(['Cash', 'Transfer', 'POS', 'Other']),
        ],

        'payment_reference' => [
            'nullable',
            'string',
            'max:255',
        ],

        'expense_date' => [
            'required',
            'date',
        ],

        'description' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    DB::transaction(function () use ($validated, $laboratoryId) {

        $expense = Expense::create([
            'laboratory_id' => $laboratoryId,
            'branch_id' => $validated['branch_id'] ?? null,
            'expense_account_id' => $validated['expense_account_id'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_reference' => $validated['payment_reference'] ?? null,
            'expense_date' => $validated['expense_date'],
            'description' => $validated['description'] ?? null,
            'created_by' => auth()->id(),
        ]);

        /*
         * Determine which asset account was used to pay.
         *
         * Cash     → Cash account (1100)
         * Transfer → Bank account (1200)
         * POS      → Bank account (1200)
         * Other    → Cash account for now
         */
        $cashOrBankCode = match ($expense->payment_method) {
            'Cash' => '1100',
            'Transfer', 'POS' => '1200',
            default => '1100',
        };

        $expenseAccount = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->where('id', $expense->expense_account_id)
            ->where('type', 'expense')
            ->where('is_active', true)
            ->firstOrFail();

        $cashOrBankAccount = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->where('code', $cashOrBankCode)
            ->firstOrFail();

        $this->journalEntryService->create([
            'laboratory_id' => $laboratoryId,
            'branch_id' => $expense->branch_id,
            'entry_date' => $expense->expense_date->toDateString(),
            'reference' => 'EXP-' . $expense->id,
            'description' => $expense->description
                ?: 'Operating expense - ' . $expenseAccount->name,
            'source_type' => Expense::class,
            'source_id' => $expense->id,
            'created_by' => auth()->id(),
            'status' => 'posted',
            'posted_at' => now(),
        ], [
            [
                'account_id' => $expenseAccount->id,
                'debit' => $expense->amount,
                'credit' => 0,
                'description' => $expenseAccount->name . ' expense',
            ],
            [
                'account_id' => $cashOrBankAccount->id,
                'debit' => 0,
                'credit' => $expense->amount,
                'description' => $expense->payment_method . ' payment',
            ],
        ]);

       
    });

    return redirect()
        ->route('accounting.expenses')
        ->with('success', 'Expense recorded successfully.');
}
}