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
use Exception;
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
        $authToken = $this->userRepository->getCurrentUserBearerToken($request);
        $currentUser = $request->user();
        return response()->json([
            'success' => true,
            'user' => $currentUser,
            'token' => $authToken,
            'role' => $currentUser->role,
        ]);
    }


    /**
     * @throws Exception
     */
    public function token(Request $request): JsonResponse
    {
        try {
            $currentUser = $request->user();
            $this->authorize('view', $currentUser);
            $userApiToken = $this->userRepository->getCurrentUserBearerToken($request);


            $encryption_key = random_bytes(strlen($currentUser->password) + 4);
            $ciphering_algorithm = "AES-256-CTR"; # $method = 'aes-256-cbc'; // AES-256-GCM
            $iv_length = openssl_cipher_iv_length($ciphering_algorithm);
            $initialization_vector = openssl_random_pseudo_bytes($iv_length, $strong_result);
            $options = 0; # OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING
            $encrypted = openssl_encrypt($userApiToken, $ciphering_algorithm, $encryption_key, $options, $initialization_vector);
            $decrypted = openssl_decrypt($encrypted, $ciphering_algorithm, $encryption_key, $options, $initialization_vector);


            return response()->json([
                'success' => true,
                'token' => $decrypted,
            ], HttpStatus::HTTP_OK, ['X-Auth-Type' => 'Bearer Token']);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?? 'Error: Unauthorized',
                'token' => NULL
            ], HttpStatus::HTTP_UNAUTHORIZED);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: appropriate level of "randomness" was not achieved.',
                'token' => "Failed to parse token"
            ], HttpStatus::HTTP_I_AM_A_TEAPOT);
        }
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
            'role' => $user->role,
            'message' => 'You have successfully logged in.'
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
