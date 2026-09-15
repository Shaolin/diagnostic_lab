<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\PettyCashFund;
use App\Models\User;
use Illuminate\Http\Request;

class PettyCashFundController extends Controller
{
    public function index()
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $funds = PettyCashFund::with(['branch', 'custodian'])
            ->where('laboratory_id', $laboratoryId)
            ->latest()
            ->get();

        return view('accounting.petty-cash.funds.index', compact('funds'));
    }

    public function create()
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $branches = Branch::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $users = User::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('accounting.petty-cash.funds.create', compact(
            'branches',
            'users'
        ));
    }

    public function store(Request $request)
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $validated = $request->validate([
            'branch_id' => [
                'required',
                'exists:branches,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'custodian_id' => [
                'required',
                'exists:users,id',
            ],
            'opening_balance' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        $branch = Branch::where('id', $validated['branch_id'])
            ->where('laboratory_id', $laboratoryId)
            ->firstOrFail();

        $custodian = User::where('id', $validated['custodian_id'])
            ->where('laboratory_id', $laboratoryId)
            ->firstOrFail();

        PettyCashFund::create([
            'laboratory_id' => $laboratoryId,
            'branch_id' => $branch->id,
            'name' => $validated['name'],
            'custodian_id' => $custodian->id,
            'opening_balance' => $validated['opening_balance'],
            'current_balance' => $validated['opening_balance'],
            'is_active' => true,
        ]);

        return redirect()
            ->route('accounting.petty-cash.funds.index')
            ->with('success', 'Petty cash fund created successfully.');
    }
}