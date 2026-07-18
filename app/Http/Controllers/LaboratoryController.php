<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laboratory;

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
        $validated['logo'] = $request->file('logo')->store('laboratories', 'public');

        // Later, we can delete the old logo here if desired.
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
