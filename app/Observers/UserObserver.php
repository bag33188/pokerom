<?php

namespace App\Observers;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserObserver
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function updated(User $user): void
    {
        if (request()->is('api/*')) {
            $this->userRepository->revokeApiTokens($user);
        }
    }

    public function deleted(User $user): void
    {
        if (request()->is('api/*')) {
            $this->userRepository->revokeApiTokens($user);
        }
    }
}
