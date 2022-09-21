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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class UserController extends ApiController
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function me(Request $request): JsonResponse
    {
        $authToken = $this->userRepository->getCurrentUserBearerToken();
        $currentUser = $request->user();
        return response()->json([
            'success' => true,
            'user' => $currentUser,
            'token' => $authToken
        ]);
    }

    public function index()
    {
        Gate::authorize('viewAny-user');
        $users = User::all();
        return new UserCollection($users);
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
            $newBearerToken = $this->userRepository->generateApiToken($user);
            return response()->json([
                'token' => $newBearerToken,
                'user' => $user,
                'success' => true,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], HttpStatus::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $userFirstName = explode(_SPACE, $user->name, 3)[0];
        if ($user) {
            $this->userRepository->revokeApiTokens($user);
            return response()->json([
                'success' => true,
                'message' => "$userFirstName, You have been logged out!"
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'You are not logged in!'
            ], HttpStatus::HTTP_UNAUTHORIZED);
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
