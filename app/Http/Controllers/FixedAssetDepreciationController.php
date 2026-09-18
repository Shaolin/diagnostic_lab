<?php

namespace App\Http\Controllers;

use App\Models\FixedAsset;
use App\Models\FixedAssetDepreciation;
use App\Models\ChartOfAccount;
use App\Services\JournalEntryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FixedAssetDepreciationController extends Controller
{
    public function __construct(
        protected JournalEntryService $journalEntryService
    ) {}

    public function create(): View
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $assets = FixedAsset::where('laboratory_id', $laboratoryId)
            ->where('status', 'active')
            ->where('current_book_value', '>', 0)
            ->orderBy('name')
            ->get();

        return view('accounting.fixed-assets.depreciation.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $validated = $request->validate([
            'fixed_asset_id' => [
                'required',
                'integer',
                Rule::exists('fixed_assets', 'id')
                    ->where('laboratory_id', $laboratoryId)
                    ->where('status', 'active'),
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'depreciation_date' => [
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

            $asset = FixedAsset::where('laboratory_id', $laboratoryId)
                ->where('id', $validated['fixed_asset_id'])
                ->where('status', 'active')
                ->lockForUpdate()
                ->firstOrFail();

            $amount = (float) $validated['amount'];
            $bookValue = (float) $asset->current_book_value;

            if ($amount > $bookValue) {
                abort(
                    422,
                    'Depreciation amount cannot be greater than the asset\'s current book value.'
                );
            }

            $depreciation = FixedAssetDepreciation::create([
                'laboratory_id' => $laboratoryId,
                'fixed_asset_id' => $asset->id,
                'amount' => $amount,
                'depreciation_date' => $validated['depreciation_date'],
                'description' => $validated['description'] ?? null,
                'recorded_by' => auth()->id(),
            ]);

            $depreciationExpenseAccount = ChartOfAccount::where('laboratory_id', $laboratoryId)
                ->where('code', '5800')
                ->where('is_active', true)
                ->firstOrFail();

            $accumulatedDepreciationAccount = ChartOfAccount::where('laboratory_id', $laboratoryId)
                ->where('code', '1550')
                ->where('is_active', true)
                ->firstOrFail();

            $this->journalEntryService->create([
                'laboratory_id' => $laboratoryId,
                'branch_id' => $asset->branch_id,
                'entry_date' => $depreciation->depreciation_date->toDateString(),
                'reference' => 'DEP-' . $depreciation->id,
                'description' => 'Depreciation - ' . $asset->name,
                'source_type' => FixedAssetDepreciation::class,
                'source_id' => $depreciation->id,
                'created_by' => auth()->id(),
                'status' => 'posted',
                'posted_at' => now(),
            ], [
                [
                    'account_id' => $depreciationExpenseAccount->id,
                    'debit' => $amount,
                    'credit' => 0,
                    'description' => 'Depreciation expense - ' . $asset->name,
                ],
                [
                    'account_id' => $accumulatedDepreciationAccount->id,
                    'debit' => 0,
                    'credit' => $amount,
                    'description' => 'Accumulated depreciation - ' . $asset->name,
                ],
            ]);

            $asset->accumulated_depreciation =
                (float) $asset->accumulated_depreciation + $amount;

            $asset->current_book_value =
                (float) $asset->acquisition_cost - $asset->accumulated_depreciation;

            $asset->save();
        });

        return redirect()
            ->route('accounting.fixed-assets.index')
            ->with('success', 'Depreciation recorded successfully.');
    }
}