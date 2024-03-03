<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->can(PermissionsEnum::CREATE_COMMENTS->value)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        if ($user->can(PermissionsEnum::EDIT_ALL_COMMENTS->value)) {
            return true;
        }

        if ($user->can(PermissionsEnum::EDIT_OWN_COMMENTS->value)){
            return $user->id === $comment->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        if ($user->can(PermissionsEnum::DELETE_ALL_COMMENTS->value)) {
            return true;
        }

        if ($user->can(PermissionsEnum::DELETE_OWN_COMMENTS->value)){
            return $user->id === $comment->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Comment $comment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return false;
    }
}
