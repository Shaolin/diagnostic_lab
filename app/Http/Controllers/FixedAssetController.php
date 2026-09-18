<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ChartOfAccount;
use App\Models\FixedAsset;
use App\Services\JournalEntryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FixedAssetController extends Controller
{

public function __construct(
        protected JournalEntryService $journalEntryService
    ) {}
    public function index(Request $request): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $query = FixedAsset::with('branch')
            ->where('laboratory_id', $laboratoryId)
            ->latest('acquisition_date')
            ->latest('id');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assets = $query->paginate(20)->withQueryString();

        $branches = Branch::where('laboratory_id', $laboratoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $totalCost = (clone $query)->sum('acquisition_cost');
        $totalBookValue = (clone $query)->sum('current_book_value');

        return view('accounting.fixed-assets.index', compact(
            'assets',
            'branches',
            'totalCost',
            'totalBookValue'
        ));
    }

    public function create(): View
{
    $laboratoryId = auth()->user()->laboratory_id;

    $branches = Branch::where('laboratory_id', $laboratoryId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('accounting.fixed-assets.create', compact('branches'));
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

        'name' => [
            'required',
            'string',
            'max:255',
        ],

        'category' => [
            'nullable',
            'string',
            'max:255',
        ],

        'description' => [
            'nullable',
            'string',
            'max:2000',
        ],

        'acquisition_date' => [
            'required',
            'date',
        ],

        'acquisition_cost' => [
            'required',
            'numeric',
            'min:0.01',
        ],

        'useful_life_years' => [
            'nullable',
            'integer',
            'min:1',
        ],
        'payment_method' => [
    'required',
    Rule::in(['Cash', 'Bank', 'Accounts Payable']),
],

        'depreciation_method' => [
            'required',
            Rule::in(['straight_line']),
        ],
    ]);

  DB::transaction(function () use ($validated, $laboratoryId) {

    $asset = FixedAsset::create([
        'laboratory_id' => $laboratoryId,
        'branch_id' => $validated['branch_id'] ?? null,
        'name' => $validated['name'],
        'category' => $validated['category'] ?? null,
        'description' => $validated['description'] ?? null,
        'acquisition_date' => $validated['acquisition_date'],
        'acquisition_cost' => $validated['acquisition_cost'],
        'useful_life_years' => $validated['useful_life_years'] ?? null,
        'depreciation_method' => $validated['depreciation_method'],
        'accumulated_depreciation' => 0,
        'current_book_value' => $validated['acquisition_cost'],
        'status' => 'active',
        'created_by' => auth()->id(),
    ]);

    $creditAccountCode = match ($validated['payment_method']) {
        'Cash' => '1100',
        'Bank' => '1200',
        'Accounts Payable' => '2100',
    };

    $creditAccount = ChartOfAccount::where('laboratory_id', $laboratoryId)
        ->where('code', $creditAccountCode)
        ->where('is_active', true)
        ->firstOrFail();

    $fixedAssetAccount = ChartOfAccount::where('laboratory_id', $laboratoryId)
        ->where('code', '1500')
        ->where('is_active', true)
        ->firstOrFail();

    $this->journalEntryService->create([
        'laboratory_id' => $laboratoryId,
        'branch_id' => $asset->branch_id,
        'entry_date' => $asset->acquisition_date->toDateString(),
        'reference' => 'FA-' . $asset->id,
        'description' => 'Fixed asset purchase - ' . $asset->name,
        'source_type' => FixedAsset::class,
        'source_id' => $asset->id,
        'created_by' => auth()->id(),
        'status' => 'posted',
        'posted_at' => now(),
    ], [
        [
            'account_id' => $fixedAssetAccount->id,
            'debit' => $asset->acquisition_cost,
            'credit' => 0,
            'description' => 'Fixed asset - ' . $asset->name,
        ],
        [
            'account_id' => $creditAccount->id,
            'debit' => 0,
            'credit' => $asset->acquisition_cost,
            'description' => $validated['payment_method'] . ' payment',
        ],
    ]);
});

    return redirect()
        ->route('accounting.fixed-assets.index')
        ->with('success', 'Fixed asset recorded successfully.');
}
}