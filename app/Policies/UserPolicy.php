<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function view(User $user, User $model): bool
    {
        return $this->currentUserIdMatchesRequestedUserId($user, $model);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function update(User $user, User $model): bool
    {
        return $this->currentUserIdMatchesRequestedUserId($user, $model);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param User $model
     * @return bool
     */
    public function delete(User $user, User $model): bool
    {
        return $this->currentUserIdMatchesRequestedUserId($user, $model);
    }

    private function currentUserIdMatchesRequestedUserId(User $currentUser, User $requestedUser): bool
    {
        // note that:
        # request()->user() == auth()->user() && \Request::user() === \Auth::user()

        return
            (string)$currentUser->getAttributeValue('id')
            ===
            (string)$requestedUser->getAttributeValue('id');
    }
}
