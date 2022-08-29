<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller as ApiController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function me(Request $request)
    {
        return $request->user();
    }

    public function register(RegisterUserRequest $request)
    {
        $user = User::create($request->all());
        $bearerToken = $this->userRepository->generateApiToken($user);

        return response()->json([
            'user' => $user,
            'token' => $bearerToken,
            'success' => true
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->json('email'))->firstOrFail();
        if ($user->checkPassword($request->json('password'))) {
            $bearerToken = $this->userRepository->generateApiToken($user);
            return response()->json([
                'token' => $bearerToken,
                'user' => $user,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $this->userRepository->revokeApiTokens($user);
            return response()->json([
                'success' => true,
                'message' => 'You have been logged out!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are not logged in!'
            ], 401);
        }
    }

    /**
     * @throws AuthorizationException
     */
    public function show(int $userId)
    {
        $user = User::findOrFail($userId);
        $this->authorize('view', $user);
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, int $userId)
    {
        $user = User::findOrFail($userId);
        $user->update($request->all());
        return new UserResource($user);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(int $userId)
    {
        $user = User::findOrFail($userId);
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User successfully deleted! ' . $user->name
        ]);
    }
}
