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
