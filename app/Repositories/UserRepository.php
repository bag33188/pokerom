<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{
    // todo: evaluate if these methods belong in this class (i mean, they are all api/auth related which is user related ??)

    public function revokeApiTokens(User $user): int
    {
        return $user->tokens()->delete();
    }

    public function generateApiToken(User $user, string $tokenName = API_TOKEN_KEY, array $abilities = []): string
    {
        if ($user->isAdmin()) {
            $abilities = ['*'];
        }
        return $user->createToken($tokenName, $abilities)->plainTextToken;
    }

    public function getCurrentUserBearerToken(Request $request): ?string
    {
        return $request->bearerToken();
    }
}
