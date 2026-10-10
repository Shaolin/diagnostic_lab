<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Enums\UserRole;
use App\Http\Requests\RegisterLaboratoryRequest;
use App\Models\Laboratory;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Services\ChartOfAccountsService;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
   /**
 * Handle an incoming registration request.
 */
public function store(RegisterLaboratoryRequest $request): RedirectResponse
{
    $data = $request->validated();

    $user = DB::transaction(function () use ($data) {

        /*
        |--------------------------------------------------------------------------
        | Create Laboratory
        |--------------------------------------------------------------------------
        */

        $laboratory = Laboratory::create([
            'name' => $data['laboratory_name'],

            // Temporary placeholder.
            // Later this will come from the registration link.
            'subdomain' => 'temporary-' . uniqid(),

            'phone' => $data['phone'] ?? null,
            'email' => $data['laboratory_email'] ?? null,
            'address' => $data['address'] ?? null,
            'country' => $data['country'] ?? null,
            'currency_code' => $data['currency_code'],
            'currency_symbol' => $data['currency_symbol'],
            'timezone' => $data['timezone'],
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Create Administrator
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'laboratory_id' => $laboratory->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        return $user;
    });

    event(new Registered($user));

    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}
}

// laboratory controller

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laboratory;
use Illuminate\Support\Facades\Storage;
class LaboratoryController extends Controller
{
    
   /**
 * Display a listing of laboratories.
 */
public function index(Request $request)
{

$this->authorize('viewAny', Laboratory::class);
    $search = $request->input('search');

    $laboratories = Laboratory::query()

    ->when(! auth()->user()->isSuperAdmin(), function ($query) {
        $query->where('id', auth()->user()->laboratory_id);
    })

        ->when(! auth()->user()->isSuperAdmin(), function ($query) {
            $query->where('id', auth()->user()->laboratory_id);
        })

        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('subdomain', 'like', "%{$search}%");
            });
        })

        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('laboratories.index', compact('laboratories', 'search'));
}
    
    /**
 * Show the form for creating a new laboratory.
 */
public function create()
{
    return view('laboratories.create');
}

    
   /**
 * Store a newly created laboratory in storage.
 */
public function store(Request $request)
{
    $validated = $request->validate([
        'name'              => ['required', 'string', 'max:255'],
        'subdomain'         => ['required', 'alpha_dash', 'max:255', 'unique:laboratories,subdomain'],
        'logo'              => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        'phone'             => ['nullable', 'string', 'max:50'],
        'email'             => ['nullable', 'email', 'max:255'],
        'address'           => ['nullable', 'string'],
        'country'           => ['nullable', 'string', 'max:100'],
        'currency_code'     => ['required', 'string', 'max:10'],
        'currency_symbol'   => ['required', 'string', 'max:10'],
        'timezone'          => ['nullable', 'string', 'max:100'],
        'is_active'         => ['nullable', 'boolean'],
    ]);

    // Always store the subdomain in lowercase
    $validated['subdomain'] = strtolower($validated['subdomain']);

    // Handle logo upload
    if ($request->hasFile('logo')) {
        $validated['logo'] = $request->file('logo')->store('laboratories', 'public');
    }

    // Checkbox handling
    $validated['is_active'] = $request->boolean('is_active');

    Laboratory::create($validated);

    return redirect()
        ->route('laboratories.index')
        ->with('success', 'Laboratory created successfully.');
}

    
   /**
    * Display the specified laboratory.
   */
public function show(Laboratory $laboratory)
{
    $this->authorize('view', $laboratory);
    return view('laboratories.show', compact('laboratory'));
}

    
   /**
 * Show the form for editing the specified laboratory.
 */
public function edit(Laboratory $laboratory)
{
    $this->authorize('update', $laboratory);
    return view('laboratories.edit', compact('laboratory'));
}

   
    /**
 * Update the specified laboratory in storage.
 */
public function update(Request $request, Laboratory $laboratory)
{
    $this->authorize('update', $laboratory);
    $validated = $request->validate([
        'name'              => ['required', 'string', 'max:255'],
        'subdomain'         => [
            'required',
            'alpha_dash',
            'max:255',
            'unique:laboratories,subdomain,' . $laboratory->id,
        ],
        'logo'              => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        'phone'             => ['nullable', 'string', 'max:50'],
        'email'             => ['nullable', 'email', 'max:255'],
        'address'           => ['nullable', 'string'],
        'country'           => ['nullable', 'string', 'max:100'],
        'currency_code'     => ['required', 'string', 'max:10'],
        'currency_symbol'   => ['required', 'string', 'max:10'],
        'timezone'          => ['nullable', 'string', 'max:100'],
        'is_active'         => ['nullable', 'boolean'],
    ]);

    // Always store the subdomain in lowercase.
    $validated['subdomain'] = strtolower($validated['subdomain']);

    // Upload a new logo if one was provided.
  if ($request->hasFile('logo')) {
    $file = $request->file('logo');

    $filename = $file->hashName();

    Storage::disk('public')->put(
        'laboratories/' . $filename,
        file_get_contents($file->getPathname())
    );

    $validated['logo'] = 'laboratories/' . $filename;
}
    // Handle checkbox value.
    $validated['is_active'] = $request->boolean('is_active');

    $laboratory->update($validated);

    return redirect()
        ->route('laboratories.index')
        ->with('success', 'Laboratory updated successfully.');
}

   
   /**
 * Remove the specified laboratory from storage.
 */
public function destroy(Laboratory $laboratory)
{
    $this->authorize('delete', $laboratory);
    $laboratory->delete();

    return redirect()
        ->route('laboratories.index')
        ->with('success', 'Laboratory deleted successfully.');
}
}

//chart of account seeder

<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Models\Laboratory;
use Illuminate\Database\Seeder;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laboratories = Laboratory::all();

        foreach ($laboratories as $laboratory) {

            // Assets
            $assets = ChartOfAccount::firstOrCreate(
                [
                    'laboratory_id' => $laboratory->id,
                    'code' => '1000',
                ],
                [
                    'name' => 'Assets',
                    'type' => 'asset',
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => 100,
                ]
            );

            $this->createAccount($laboratory->id, $assets->id, '1100', 'Cash', 'asset', 110);
            $this->createAccount($laboratory->id, $assets->id, '1150', 'Petty Cash', 'asset', 115);
            $this->createAccount($laboratory->id, $assets->id, '1200', 'Bank', 'asset', 120);
            $this->createAccount($laboratory->id, $assets->id, '1300', 'Accounts Receivable', 'asset', 130);
            $this->createAccount($laboratory->id, $assets->id, '1400', 'Inventory', 'asset', 140);
            $this->createAccount($laboratory->id, $assets->id, '1500', 'Fixed Assets', 'asset', 150);
            $this->createAccount($laboratory->id, $assets->id, '1550', 'Accumulated Depreciation', 'asset', 155);

            // Liabilities
            $liabilities = ChartOfAccount::firstOrCreate(
                [
                    'laboratory_id' => $laboratory->id,
                    'code' => '2000',
                ],
                [
                    'name' => 'Liabilities',
                    'type' => 'liability',
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => 200,
                ]
            );

            $this->createAccount($laboratory->id, $liabilities->id, '2100', 'Accounts Payable', 'liability', 210);
            $this->createAccount($laboratory->id, $liabilities->id, '2200', 'Loans Payable', 'liability', 220);

            // Equity
            $equity = ChartOfAccount::firstOrCreate(
                [
                    'laboratory_id' => $laboratory->id,
                    'code' => '3000',
                ],
                [
                    'name' => 'Equity',
                    'type' => 'equity',
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => 300,
                ]
            );

            $this->createAccount($laboratory->id, $equity->id, '3100', "Owner's / Company Equity", 'equity', 310);

            // Income
            $income = ChartOfAccount::firstOrCreate(
                [
                    'laboratory_id' => $laboratory->id,
                    'code' => '4000',
                ],
                [
                    'name' => 'Income',
                    'type' => 'income',
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => 400,
                ]
            );

            $this->createAccount($laboratory->id, $income->id, '4100', 'Laboratory Services', 'income', 410);
            $this->createAccount($laboratory->id, $income->id, '4200', 'Other Income', 'income', 420);

            // Expenses
            $expenses = ChartOfAccount::firstOrCreate(
                [
                    'laboratory_id' => $laboratory->id,
                    'code' => '5000',
                ],
                [
                    'name' => 'Expenses',
                    'type' => 'expense',
                    'is_system' => true,
                    'is_active' => true,
                    'sort_order' => 500,
                ]
            );

            $this->createAccount($laboratory->id, $expenses->id, '5100', 'Salaries', 'expense', 510);
            $this->createAccount($laboratory->id, $expenses->id, '5200', 'Reagents & Laboratory Supplies', 'expense', 520);
            $this->createAccount($laboratory->id, $expenses->id, '5300', 'Transport & Logistics', 'expense', 530);
            $this->createAccount($laboratory->id, $expenses->id, '5400', 'Utilities', 'expense', 540);
            $this->createAccount($laboratory->id, $expenses->id, '5500', 'Repairs & Maintenance', 'expense', 550);
            $this->createAccount($laboratory->id, $expenses->id, '5600', 'Stationery', 'expense', 560);
            $this->createAccount($laboratory->id, $expenses->id, '5700', 'Other Expenses', 'expense', 570);
            $this->createAccount($laboratory->id, $expenses->id, '5800', 'Depreciation Expense', 'expense', 580);
        }
    }

    private function createAccount(
        int $laboratoryId,
        int $parentId,
        string $code,
        string $name,
        string $type,
        int $sortOrder
    ): ChartOfAccount {
        return ChartOfAccount::firstOrCreate(
            [
                'laboratory_id' => $laboratoryId,
                'code' => $code,
            ],
            [
                'parent_id' => $parentId,
                'name' => $name,
                'type' => $type,
                'is_system' => true,
                'is_active' => true,
                'sort_order' => $sortOrder,
            ]
        );
    }
}

//create test request

<?php

namespace App\Actions;

use App\Models\TestRequest;
use App\Models\TestType;
use App\Services\TestRequestCalculator;
use App\Services\TrackingCodeGenerator;
use Illuminate\Support\Facades\DB;
use App\Models\TestRequestItem;
use App\Models\ChartOfAccount;
use App\Services\JournalEntryService;

class CreateTestRequest
{
    public function __construct(
        protected TrackingCodeGenerator $trackingCodeGenerator,
        protected TestRequestCalculator $calculator,
          protected JournalEntryService $journalEntryService
    ) {
    }

    /**
     * Create a Test Request with its items.
     */
  public function execute(array $data): TestRequest
{
    return DB::transaction(function () use ($data) {

        /*
        |--------------------------------------------------------------------------
        | Calculate Total
        |--------------------------------------------------------------------------
        */

        $totalAmount = $this->calculator->calculate($data['items']);

        /*
        |--------------------------------------------------------------------------
        | Fetch All Test Types (Avoid N+1 Queries)
        |--------------------------------------------------------------------------
        */

        $testTypes = TestType::whereIn(
            'id',
            collect($data['items'])->pluck('test_type_id')
        )
        ->get()
        ->keyBy('id');

        /*
        |--------------------------------------------------------------------------
        | Create Test Request
        |--------------------------------------------------------------------------
        */

        $testRequest = TestRequest::create([
            'laboratory_id' => auth()->user()->laboratory_id,
             'branch_id' => auth()->user()->branch_id,
            'patient_id' => $data['patient_id'],
            'tracking_code' => $this->trackingCodeGenerator->generate(),
            'total_amount' => $totalAmount,
            'remarks' => $data['remarks'] ?? null,
            'overall_status' => TestRequest::STATUS_PENDING,
            'requested_by' => auth()->id(),
        ]);

        $receivableAccount = ChartOfAccount::where('laboratory_id', $testRequest->laboratory_id)
    ->where('code', '1300')
    ->firstOrFail();

$incomeAccount = ChartOfAccount::where('laboratory_id', $testRequest->laboratory_id)
    ->where('code', '4100')
    ->firstOrFail();

$this->journalEntryService->create([
    'laboratory_id' => $testRequest->laboratory_id,
    'branch_id' => $testRequest->branch_id,
    'entry_date' => $testRequest->created_at->toDateString(),
    'reference' => 'TR-' . $testRequest->id,
    'description' => 'Laboratory service billed',
    'source_type' => TestRequest::class,
    'source_id' => $testRequest->id,
    'created_by' => auth()->id(),
    'status' => 'posted',
    'posted_at' => now(),
], [
    [
        'account_id' => $receivableAccount->id,
        'debit' => $totalAmount,
        'credit' => 0,
        'description' => 'Amount receivable from patient',
    ],
    [
        'account_id' => $incomeAccount->id,
        'debit' => 0,
        'credit' => $totalAmount,
        'description' => 'Laboratory service income',
    ],
]);

        /*
        |--------------------------------------------------------------------------
        | Create Test Request Items
        |--------------------------------------------------------------------------
        */

        foreach ($data['items'] as $item) {

            $testType = $testTypes->get($item['test_type_id']);

            $testRequest->items()->create([
                'test_type_id' => $testType->id,
                'test_name'    => $testType->name,
                'price'        => $item['price'],

                'status'         => TestRequestItem::STATUS_PENDING,
                'sample_status'  => TestRequestItem::SAMPLE_PENDING,
                'result_status'  => TestRequestItem::RESULT_NOT_READY,
            ]);
        }

        return $testRequest->load([
            'patient',
            'items.testType',
            'requestedBy',
        ]);
    });
}
}

