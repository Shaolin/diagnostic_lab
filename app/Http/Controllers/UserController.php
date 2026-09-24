<?php

namespace App\Http\Controllers;
   use App\Models\User;
use Illuminate\Http\Request;
 use App\Enums\UserRole;
 use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\UserPermission;




class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    

public function index(Request $request)
{
    $this->authorize('viewAny', User::class);
   $users = User::query()
    ->with('laboratory')

    ->when(
        ! auth()->user()->isSuperAdmin(),
        function ($query) {
            $query->where(
                'laboratory_id',
                auth()->user()->laboratory_id
            );
        }
    )
        ->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->when($request->filled('role'), function ($query) use ($request) {
            $query->where('role', $request->role);
        })
        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('is_active', $request->boolean('status'));
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

  $roles = auth()->user()->isSuperAdmin()
    ? UserRole::cases()
    : [
        UserRole::ADMIN,
        UserRole::ACCOUNTANT,
        UserRole::STAFF,
    ];
  return view('users.index', compact('users', 'roles'));
}

    /**
     * Show the form for creating a new resource.
     */
  





public function create()
{
    $this->authorize('create', User::class);

   $roles = [
    UserRole::ADMIN,
    UserRole::ACCOUNTANT,
    UserRole::STAFF,
];

    $branches = auth()->user()->laboratory->branches()
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('users.create', compact('roles', 'branches'));
}



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
{
    $this->authorize('create', User::class);
    $data = $request->validated();

    // Always assign the authenticated user's laboratory
    $data['laboratory_id'] = auth()->user()->laboratory_id;

    User::create($data);

    return redirect()
        ->route('users.index')
        ->with('success', 'User created successfully.');
}

    /**
     * Display the specified resource.
//      */


public function show(User $user)
{
    $this->authorize('view', $user);

    $user->load('branch');

    return view('users.show', compact('user'));
}
    /**
     * Show the form for editing the specified resource.
     */





public function edit(User $user)
{
    $this->authorize('update', $user);

    $roles = [
        UserRole::ADMIN,
        UserRole::ACCOUNTANT,
        UserRole::STAFF,
    ];

    $branches = auth()->user()->laboratory->branches()
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    $permissions = $user->permissions()
        ->where('enabled', true)
        ->pluck('module')
        ->toArray();

    return view('users.edit', compact(
        'user',
        'roles',
        'branches',
        'permissions'
    ));
}



    /**
     * Update the specified resource in storage.
     */
   public function update(UpdateUserRequest $request, User $user)
{
    $this->authorize('update', $user);

    $data = $request->validated();

    if (blank($data['password'])) {
        unset($data['password']);
    }

    $user->update($data);

    /*
     * Save user permissions.
     *
     * Only the modules submitted by the form will be enabled.
     * Any previously enabled module that is no longer checked
     * will be disabled.
     */
    $selectedPermissions = $request->input('permissions', []);

    foreach (UserPermission::MODULES as $module) {
        $user->permissions()->updateOrCreate(
            ['module' => $module],
            ['enabled' => in_array($module, $selectedPermissions)]
        );
    }

    return redirect()
        ->route('users.index')
        ->with('success', 'User updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
{
    $this->authorize('delete', $user);

    $user->update([
        'is_active' => false,
    ]);

    return redirect()
        ->route('users.index')
        ->with('success', 'User has been deactivated successfully.');
}
public function activate(User $user)
{
    $this->authorize('update', $user);

    $user->update([
        'is_active' => true,
    ]);

    return redirect()
        ->route('users.index')
        ->with('success', 'User has been activated successfully.');
}
}
