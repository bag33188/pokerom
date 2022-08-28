<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
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
}
