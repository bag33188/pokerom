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

    public function generateApiToken(User $user, string $tokenName = API_TOKEN_KEY, array $tokenAbilities = []): string
    {
        if ($user->isAdmin()) $tokenAbilities = ['*'];

        return $user->createToken($tokenName, $tokenAbilities)->plainTextToken;
    }

    public function getCurrentUserBearerToken(Request $request): ?string
    {
        return $request->bearerToken();
    }
}
