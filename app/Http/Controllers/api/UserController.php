<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as ApiController;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Gate;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Str;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class UserController extends ApiController
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function me(Request $request): JsonResponse
    {
        $currentUser = $request->user();
        Gate::authorize('view-currentUserData', $currentUser);
        return response()->json([
            'success' => true,
            'user' => $currentUser,
        ], HttpStatus::HTTP_OK);
    }

    public function token(Request $request): JsonResponse
    {
        $currentUser = $request->user();
        Gate::authorize('view-currentUserData', $currentUser);
        $userApiToken = $this->userRepository->getCurrentUserApiToken($request);

        return response()->json([
            'success' => true,
            'token' => $userApiToken,
        ], HttpStatus::HTTP_OK, ['X-Auth-Type' => 'Bearer Token']);
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
            'success' => true,
            'user' => $user,
            'message' => 'You have successfully registered! Now you can login.'
        ], HttpStatus::HTTP_CREATED);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $user = $request->input('user');
        $apiTokenName = sprintf("%s_%u", Str::slug(uniqid(API_TOKEN_PREFIX, true), '_'), $user->id);
        $bearerToken = $this->userRepository->generateApiToken($user, $apiTokenName);

        $removeExtraProps = fn(int|string $value, string $key): bool => in_array($key, ['id', 'role', 'name', 'email']);
        $userJSON = json_encode(collect($user->toArray())->filter($removeExtraProps));

        return response()->json([
            'success' => true,
            'token' => $bearerToken,
            'message' => 'You have successfully logged in.',
        ], HttpStatus::HTTP_OK, ['X-User-Data' => $userJSON]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $userFirstName = explode(_SPACE, $user->name, 3)[0];
        $this->userRepository->revokeApiTokens($user, true);
        return response()->json([
            'success' => true,
            'message' => "$userFirstName, You have been logged out!"
        ], HttpStatus::HTTP_OK);
    }

    /**
     * @param int $userId
     * @return UserResource
     * @throws AuthorizationException
     */
    public function show(int $userId): UserResource
    {
        $user = User::findOrFail($userId);
        $this->authorize('view', $user);
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, int $userId): UserResource
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
