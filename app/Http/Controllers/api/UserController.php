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

    /**
     * @throws AuthorizationException
     */
    public function index(): UserCollection
    {
        $this->authorize('viewAny', User::class);
        $users = User::all();
        return new UserCollection($users);
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = User::create($request->all());

        return response()->json([
            'user' => $user,
            'success' => true,
            'message' => 'You have successfully registered! Now you can login.'
        ], HttpStatus::HTTP_CREATED);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $user = $request->input('user');
        $newBearerToken = $this->userRepository->generateApiToken($user);
        return response()->json([
            'token' => $newBearerToken,
            'user' => $user,
            'success' => true,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $userFirstName = explode(_SPACE, $user->name, 3)[0];
        $this->userRepository->revokeApiTokens($user);
        return response()->json([
            'success' => true,
            'message' => "$userFirstName, You have been logged out!"
        ]);
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
    public function destroy(int $userId): JsonResponse
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
