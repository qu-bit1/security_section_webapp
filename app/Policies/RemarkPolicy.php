<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\Remark;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RemarkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->can(PermissionsEnum::ACCESS_ALL_REMARKS->value)) {
            return true;
        }

        if ($user->can(PermissionsEnum::ACCESS_OWN_REMARKS->value)){
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Remark $remark): bool
    {
        if ($user->can(PermissionsEnum::ACCESS_ALL_REMARKS->value)) {
            return true;
        }

        if ($user->can(PermissionsEnum::ACCESS_OWN_REMARKS->value)){
            return $user->id === $remark->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->can(PermissionsEnum::CREATE_REMARKS->value)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Remark $remark): bool
    {
        if ($user->can(PermissionsEnum::EDIT_ALL_REMARKS->value)) {
            return true;
        }

        if ($user->can(PermissionsEnum::EDIT_OWN_REMARKS->value)){
            return $user->id === $remark->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Remark $remark): bool
    {
        if ($user->can(PermissionsEnum::DELETE_ALL_REMARKS->value)) {
            return true;
        }

        if ($user->can(PermissionsEnum::DELETE_OWN_REMARKS->value)){
            return $user->id === $remark->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Remark $remark): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Remark $remark): bool
    {
        return false;
    }
}
