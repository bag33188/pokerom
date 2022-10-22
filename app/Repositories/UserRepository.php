<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    public function revokeApiTokens(User $user): int
    {
        return $user->tokens()->delete();
    }

    public function generateApiToken(User $user): string
    {
        $abilities = $user->isAdmin() ? ["*"] : [];
        return $user->createToken(API_TOKEN_KEY, $abilities)->plainTextToken;
    }

    public function getCurrentUserBearerToken(Request $request): ?string
    {
        return $request->bearerToken();
    }
}
