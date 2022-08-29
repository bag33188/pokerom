<?php

namespace App\Policies;

use App\Models\Rom;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RomPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Rom $rom
     * @return Response|bool
     */
    public function update(User $user, Rom $rom): Response|bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Rom $rom
     * @return Response|bool
     */
    public function delete(User $user, Rom $rom): Response|bool
    {
        return $user->isAdmin();
    }
}
