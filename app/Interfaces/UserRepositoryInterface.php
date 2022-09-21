<?php

namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    function revokeApiTokens(User $user): int;

    function generateApiToken(User $user): string;

    function getCurrentUserBearerToken(): string|null;
}
