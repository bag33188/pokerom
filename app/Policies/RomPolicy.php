<?php

namespace App\Policies;

use App\Models\Rom;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RomPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Rom  $rom
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Rom $rom)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Rom  $rom
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Rom $rom)
    {
        return $user->isAdmin();
    }
}
