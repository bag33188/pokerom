<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly Application $app)
    {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function revokeApiTokens(User $user, bool $revokeAll = true): int
    {
        // resolve current app/container `Request` instance
        // needed so we can target the SPECIFIC token used for said request (if `revokeAll` is false)
        $request = $this->app->get(Request::class);

        return $revokeAll ? $user->tokens()->delete() : $request->user()->currentAccessToken()->delete();
    }

    public function generateApiToken(User $user, string $tokenName = API_TOKEN_KEY, array $tokenAbilities = []): string
    {
        if ($user->isAdmin()) $tokenAbilities = ["*"];

        return $user->createToken($tokenName, $tokenAbilities)->plainTextToken;
    }

    public function getCurrentUserApiToken(Request $request): ?string
    {
        return $request->bearerToken();
    }
}
