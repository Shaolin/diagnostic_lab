<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchIncomeController extends Controller
{
    public function index(Request $request): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $incomeAccount = ChartOfAccount::where('laboratory_id', $laboratoryId)
            ->where('code', '4100')
            ->firstOrFail();

        $branches = Branch::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $query = JournalEntryLine::query()
            ->with([
                'journalEntry.branch',
            ])
            ->where('account_id', $incomeAccount->id)
            ->whereHas('journalEntry', function ($query) use ($laboratoryId) {
                $query->where('laboratory_id', $laboratoryId)
                    ->where('status', 'posted');
            });

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Branch Filter
        |--------------------------------------------------------------------------
        */

        $query->when($request->branch_id, function ($query, $branchId) {
            $query->whereHas('journalEntry', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            });
        });

        $totalIncome = (clone $query)->sum('credit');

        $incomeEntries = $query
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('accounting.branch-income.index', compact(
            'incomeEntries',
            'branches',
            'totalIncome'
        ));
    }
}