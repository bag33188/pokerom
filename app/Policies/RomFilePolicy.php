<?php

namespace App\Policies;

use App\Models\RomFile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RomFilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, RomFile $romFile): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param RomFile $romFile
     * @return bool
     */
    public function delete(User $user, RomFile $romFile): bool
    {
        return $user->isAdmin();
    }
}
