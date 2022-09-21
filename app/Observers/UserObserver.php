<?php

namespace App\Observers;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;

class UserObserver
{
    public function __construct(private readonly UserRepositoryInterface $userRepository,
                                private readonly Request                 $request)
    {
    }

    public function updated(User $user): void
    {
        $requestIsApiEndpoint = $this->request->is('api/*');
        $passwordHasChanged = $user->isDirty('password');

        if ($passwordHasChanged || $requestIsApiEndpoint) $this->userRepository->revokeApiTokens($user);
    }

    public function deleted(User $user): void
    {
        $this->userRepository->revokeApiTokens($user);
    }
}
