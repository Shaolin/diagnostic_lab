<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Services\PatientNumberGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;




class PatientController extends Controller
{
    /**
     * Display a listing of patients.
     */
   public function index(Request $request)
{
    $this->authorize('viewAny', Patient::class);

    $patients = Patient::with('creator')
        ->where('laboratory_id', auth()->user()->laboratory_id)
        ->search($request->search)
        ->latest()
        ->paginate(15)
        ->withQueryString();

    return view('patients.index', compact('patients'));
}

    /**
     * Show the form for creating a new patient.
     */
   public function create()
{
    $this->authorize('create', Patient::class);

    return view('patients.create');
}

    /**
     * Store a newly created patient.
     */
    public function store(
    StorePatientRequest $request,
    PatientNumberGenerator $generator
) {
    $this->authorize('create', Patient::class);

    $patient = DB::transaction(function () use ($request, $generator) {
        return Patient::create([
            ...$request->validated(),

            'laboratory_id' => auth()->user()->laboratory_id,

            'patient_number' => $generator->generate(
                auth()->user()->laboratory_id
            ),

            'created_by' => auth()->id(),
        ]);
    });

    return redirect()
        ->route('patients.show', $patient)
        ->with('success', 'Patient registered successfully.');
}

    /**
     * Display the specified patient.
     */
    public function show(Patient $patient)
{
    $this->authorize('view', $patient);

    $patient->load('creator');

    return view('patients.show', compact('patient'));
}

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient)
{
    $this->authorize('update', $patient);

    return view('patients.edit', compact('patient'));
}

    /**
     * Update the specified patient.
     */
    public function update(
    UpdatePatientRequest $request,
    Patient $patient
)
{
    $this->authorize('update', $patient);

    $patient->update([
        ...$request->validated(),

        'updated_by' => auth()->id(),
    ]);

    return redirect()
        ->route('patients.show', $patient)
        ->with('success', 'Patient updated successfully.');
}
// Is patient active or inactive
public function toggleStatus(Patient $patient)
{
    $this->authorize('deactivate', $patient);

    $patient->update([
        'is_active' => ! $patient->is_active,
        'updated_by' => auth()->id(),
    ]);

    return back()->with(
        'success',
        $patient->is_active
            ? 'Patient activated successfully.'
            : 'Patient deactivated successfully.'
    );
}
}