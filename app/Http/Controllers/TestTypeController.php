<?php

namespace App\Http\Controllers;

use App\Models\TestType;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTestTypeRequest;
use App\Http\Requests\UpdateTestTypeRequest;

use App\Services\TestTypeCodeGenerator;

class TestTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = TestType::with(['creator'])
            ->where('laboratory_id', $user->laboratory_id);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->status !== null && $request->status !== '') {

            $query->where(
                'is_active',
                filter_var($request->status, FILTER_VALIDATE_BOOLEAN)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sort
        |--------------------------------------------------------------------------
        */

        $testTypes = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Category Dropdown
        |--------------------------------------------------------------------------
        */

        $categories = TestType::where('laboratory_id', $user->laboratory_id)
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('test-types.index', compact(
            'testTypes',
            'categories'
        ));
    }

   
   /**
 * Show the form for creating a new Test Type.
 */
public function create()
{
    $user = auth()->user();

    $categories = TestType::where('laboratory_id', $user->laboratory_id)
        ->whereNotNull('category')
        ->distinct()
        ->orderBy('category')
        ->pluck('category');

    return view('test-types.create', compact('categories'));
}

   
   /**
 * Store a newly created Test Type.
 */
public function store(
    StoreTestTypeRequest $request,
    TestTypeCodeGenerator $codeGenerator
) {
    $user = auth()->user();

    $testType = TestType::create([
        'laboratory_id' => $user->laboratory_id,
        'code' => $codeGenerator->generate($user->laboratory_id),
        'name' => $request->name,
        'category' => $request->category,
        'description' => $request->description,
        'default_price' => $request->default_price,
        'estimated_turnaround_hours' => $request->estimated_turnaround_hours,
        'is_active' => $request->boolean('is_active'),
        'created_by' => $user->id,
        'updated_by' => null,
    ]);

    return redirect()
        ->route('test-types.show', $testType)
        ->with('success', 'Test Type created successfully.');
}

   /**
 * Display the specified Test Type.
 */
public function show(TestType $testType)
{
    $this->authorizeLaboratory($testType);

    $testType->load([
        'laboratory',
        'creator',
        'updater',
    ]);

    return view('test-types.show', [
        'testType' => $testType,
    ]);
}

   
    /**
 * Show the form for editing the specified Test Type.
 */
public function edit(TestType $testType)
{
    $this->authorizeLaboratory($testType);

    $user = auth()->user();

    $categories = TestType::where('laboratory_id', $user->laboratory_id)
        ->whereNotNull('category')
        ->distinct()
        ->orderBy('category')
        ->pluck('category');

    return view('test-types.edit', compact(
        'testType',
        'categories'
    ));
}

    /**
 * Update the specified Test Type.
 */
public function update(
    UpdateTestTypeRequest $request,
    TestType $testType
) {
    $this->authorizeLaboratory($testType);

    $user = auth()->user();

    $testType->update([
        'name' => $request->name,
        'category' => $request->category,
        'description' => $request->description,
        'default_price' => $request->default_price,
        'estimated_turnaround_hours' => $request->estimated_turnaround_hours,
        'is_active' => $request->boolean('is_active'),
        'updated_by' => $user->id,
    ]);

    return redirect()
        ->route('test-types.show', $testType)
        ->with('success', 'Test Type updated successfully.');
}

    /**
 * Activate the specified Test Type.
 */
public function activate(TestType $testType)
{
    $this->authorizeLaboratory($testType);

    if ($testType->is_active) {
        return redirect()
            ->back()
            ->with('info', 'Test Type is already active.');
    }

    $testType->update([
        'is_active' => true,
        'updated_by' => auth()->id(),
    ]);

    return redirect()
        ->route('test-types.index')
        ->with('success', 'Test Type activated successfully.');
}

   
   /**
 * Deactivate the specified Test Type.
 */
public function deactivate(TestType $testType)
{
    $this->authorizeLaboratory($testType);

    if (! $testType->is_active) {
        return redirect()
            ->back()
            ->with('info', 'Test Type is already inactive.');
    }

    /*
    |--------------------------------------------------------------------------
    | Future Enhancement
    |--------------------------------------------------------------------------
    |
    | Prevent deactivation if this Test Type is currently being used in
    | active or pending Test Requests.
    |
    | Example:
    |
    | if ($testType->testRequestItems()->exists()) {
    |     return back()->with('error',
    |         'This Test Type cannot be deactivated because it is currently in use.'
    |     );
    | }
    |
    */

    $testType->update([
        'is_active' => false,
        'updated_by' => auth()->id(),
    ]);

    return redirect()
        ->route('test-types.index')
        ->with('success', 'Test Type deactivated successfully.');
}

    /**
 * Ensure the Test Type belongs to the authenticated user's laboratory.
 */
private function authorizeLaboratory(TestType $testType): void
{
    abort_if(
        $testType->laboratory_id !== auth()->user()->laboratory_id,
        403,
        'Unauthorized access to this Test Type.'
    );
}
}