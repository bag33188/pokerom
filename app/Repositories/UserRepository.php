<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    function __construct(private readonly Request $request)
    {
    }

    public function revokeApiTokens(User $user): int
    {
        return $user->tokens()->delete();
    }

    public function generateApiToken(User $user): string
    {
        return $user->createToken(API_TOKEN_KEY)->plainTextToken;
    }

    public function getCurrentUserBearerToken(): ?string
    {
        return $this->request->bearerToken();
    }
}
