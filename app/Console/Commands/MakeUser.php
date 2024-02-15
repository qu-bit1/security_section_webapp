<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;
use function Laravel\Prompts\spin;

class MakeUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = text(
            label: 'Name',
        );

        $email = text(
            label: 'Email',
            required: true,
            validate: function (string $email): ?string {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return "The email $email is not valid";
                }

                if (User::where('email', $email)->exists()) {
                    return "The email $email is already taken";
                }

                return null;
            }
        );

        $roles = multiselect(
            label: 'Role',
            options: Role::pluck('name', 'id'),
        );

        $password = password(
            label: 'Password',
            required: true,
            validate: fn(string $value) => match(true){
                strlen($value) < 8 => "The password must be at least 8 characters long",
                !preg_match('/[A-Z]/', $value) => "The password must contain at least one uppercase letter",
                !preg_match('/[a-z]/', $value) => "The password must contain at least one lowercase letter",
                !preg_match('/[0-9]/', $value) => "The password must contain at least one number",
                default => null
            }
        );

        password(
            label: 'Confirm password',
            required: true,
            validate: function (string $value) use ($password): ?string {
                if ($value !== $password) {
                    return "The password confirmation does not match";
                }

                return null;
            }
        );


        spin(
            fn () => User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
            ])->assignRole($roles),
            'Creating user...'
        );

        $this->info("User created with email: $email");
    }
}
