<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Role $role): bool
    {
        return $user->can(PermissionsEnum::ASSIGN_ROLES->value);
    }
}
