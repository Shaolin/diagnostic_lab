<?php
namespace App\Console\Commands;


use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    protected $signature = 'app:create-super-admin';

    protected $description = 'Create a Super Admin account';

    public function handle(): int
    {
        $name = $this->ask('Super Admin name');

        $email = $this->ask('Super Admin email');

        $password = $this->secret('Super Admin password');

        $passwordConfirmation = $this->secret('Confirm password');

        if ($password !== $passwordConfirmation) {
            $this->error('Passwords do not match.');

            return self::FAILURE;
        }
        
if (User::where('role', UserRole::SUPER_ADMIN)->exists()) {
    $this->error('A Super Admin account already exists.');

    return self::FAILURE;
}



        if (User::where('email', $email)->exists()) {
            $this->error('A user with this email already exists.');

            return self::FAILURE;
        }

        User::create([
            'laboratory_id' => null,
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => UserRole::SUPER_ADMIN,
            'is_active' => true,
        ]);

        $this->info('Super Admin account created successfully.');

        return self::SUCCESS;
    }
}

