<?php

namespace App\Observers;

use App\Actions\ApiMethodsTrait as ApiMethods;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Notifications\FarewellNotification;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;

class UserObserver
{
    use ApiMethods;

    // make sure db transaction is committed successfully.
    // user data is more sensitive
    protected bool $afterCommit = true;

    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function created(User $user): void
    {
        Notification::send($user, new WelcomeNotification($user));
    }

    public function updated(User $user): void
    {
        $passwordHasChanged = $user->isDirty("password");

        // revoke any existing tokens if password has changed
        // or user is updating from API endpoint
        if ($passwordHasChanged || $this->isApiRequest())
            $this->userRepository->revokeApiTokens($user);
    }

    public function deleted(User $user): void
    {
        $this->userRepository->revokeApiTokens($user);

        if (!$user->isAdmin()) $user->notify(new FarewellNotification($user));
    }
}
