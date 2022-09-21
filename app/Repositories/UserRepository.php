<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class UserRepository implements UserRepositoryInterface
{
    public function revokeApiTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    public function generateApiToken(User $user): string
    {
        return $user->createToken(API_TOKEN_KEY)->plainTextToken;
    }

    public function getCurrentUserBearerToken(): ?string
    {
        return Request::bearerToken();
    }
}
