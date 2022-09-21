<?php

namespace App\Observers;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Notifications\FarewellNotification;
use App\Notifications\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class UserObserver
{
    public function __construct(private readonly UserRepositoryInterface $userRepository,
                                private readonly Request                 $request)
    {
    }

    public function created(User $user): void
    {
        Notification::send($user, new WelcomeNotification($user));
    }

    public function updated(User $user): void
    {
        $requestIsApiEndpoint = $this->request->is("api/*");
        $passwordHasChanged = $user->isDirty("password");

        if ($passwordHasChanged || $requestIsApiEndpoint) $this->userRepository->revokeApiTokens($user);
    }

    public function deleted(User $user): void
    {
        $this->userRepository->revokeApiTokens($user);
        $user->notify(new FarewellNotification($user));
    }
}
