<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    function revokeApiTokens(User $user): int;

    function generateApiToken(User $user, string $tokenName, array $tokenAbilities): string;

    function getCurrentUserBearerToken(Request $request): string|null;
}
