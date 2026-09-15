<?php

namespace App\Http\Controllers;


use App\Actions\CreateBranch;
use App\Http\Requests\StoreBranchRequest;
use App\Models\Branch;
use App\Actions\UpdateBranch;
use App\Http\Requests\UpdateBranchRequest;
use App\Actions\DeleteBranch;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $laboratory = auth()->user()->laboratory;

    $branches = $laboratory->branches()
        ->latest()
        ->paginate(10);

    return view('branches.index', compact('branches'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    return view('branches.create');
}

    /**
     * Store a newly created resource in storage.
     */
   public function store(StoreBranchRequest $request)
{
    app(CreateBranch::class)->execute($request->validated());

    return redirect()
        ->route('branches.index')
        ->with('success', 'Branch created successfully.');
}

    /**
     * Display the specified resource.
     */
  public function show(Branch $branch)
{
    abort_unless(
        $branch->laboratory_id === auth()->user()->laboratory_id,
        403
    );

    return view('branches.show', compact('branch'));
}

    /**
     * Show the form for editing the specified resource.
     */
 public function edit(Branch $branch)
{
    abort_unless(
        $branch->laboratory_id === auth()->user()->laboratory_id,
        403
    );

    return view('branches.edit', compact('branch'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
{
    abort_unless(
        $branch->laboratory_id === auth()->user()->laboratory_id,
        403
    );

    app(UpdateBranch::class)->execute(
        $branch,
        $request->validated()
    );

    return redirect()
        ->route('branches.index')
        ->with('success', 'Branch updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Branch $branch)
{

 abort_unless(
        $branch->laboratory_id === auth()->user()->laboratory_id,
        403
    );
    
    app(DeleteBranch::class)->execute($branch);

    return redirect()
        ->route('branches.index')
        ->with('success', 'Branch deleted successfully.');
}
}
