<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->json('email'))->firstOrFail();
        if ($user->checkPassword($request->json('password'))) {
            return response()->json([
                'token' => $user->createToken(API_TOKEN_KEY)->plainTextToken,
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

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'You have been logged out!'
        ]);
    }
}
