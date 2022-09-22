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
        $requestIsApiEndpoint = $this->isApiRequest();
        $passwordHasChanged = $user->isDirty("password");

        if ($passwordHasChanged || $requestIsApiEndpoint) $this->userRepository->revokeApiTokens($user);
    }

    public function deleted(User $user): void
    {
        $this->userRepository->revokeApiTokens($user);

        if (!$user->isAdmin()) $user->notify(new FarewellNotification($user));
    }
}
