<?php

namespace App\Policies;

use App\Models\ImageJob;
use App\Models\User;

class ImageJobPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can view their own image jobs
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ImageJob $imageJob): bool
    {
        return $user->id === $imageJob->user_id || $user->canAccessWorkspace($imageJob->workspace);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Users can create image jobs in their workspace
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ImageJob $imageJob): bool
    {
        return $user->id === $imageJob->user_id || $user->canAccessWorkspace($imageJob->workspace);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ImageJob $imageJob): bool
    {
        return $user->id === $imageJob->user_id || $user->canAccessWorkspace($imageJob->workspace);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ImageJob $imageJob): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ImageJob $imageJob): bool
    {
        return false;
    }
}
