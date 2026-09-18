<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankAccountController extends Controller
{
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

        BankAccount::create([
            'laboratory_id' => $laboratoryId,
            'branch_id' => $validated['branch_id'] ?? null,
            'bank_name' => $validated['bank_name'],
            'account_name' => $validated['account_name'],
            'account_number' => $validated['account_number'] ?? null,
            'opening_balance' => $validated['opening_balance'],
            'is_active' => true,
        ]);

        return redirect()
            ->route('accounting.bank-accounts.index')
            ->with('success', 'Bank account added successfully.');
    }
}