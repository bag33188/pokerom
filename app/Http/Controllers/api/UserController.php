<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller as ApiController;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class UserController extends ApiController
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function me(Request $request): User
    {
        return $request->user();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', $request->user());
        return new UserCollection(User::all());
    }

    public function register(RegisterUserRequest $request)
    {
        $user = User::create($request->all());

        return response()->json([
            'user' => $user,
            'success' => true,
            'message' => 'You have successfully registered! Now you can login.'
        ], HttpStatus::HTTP_CREATED);
    }

    public function login(LoginUserRequest $request)
    {
        list('email' => $email, 'password' => $password) = $request->only(['email', 'password']);
        $user = User::where('email', $email)->firstOrFail();
        if ($user->checkPassword($password)) {
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
        $userName = $user->name;
        if ($user) {
            $this->userRepository->revokeApiTokens($user);
            return response()->json([
                'success' => true,
                'message' => "$userName, You have been logged out!"
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
