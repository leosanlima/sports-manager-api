<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * Handle the user login process.
     *
     * @param Request $request 
     * @return \Illuminate\Http\JsonResponse 
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json(['token' => $token], 200)
            ->header('X-Authorization', $token);
    }

    /**
     * Handle the user logout process.
     *
     * Deletes all authentication tokens for the currently authenticated user.
     * If the user is not authenticated, returns an unauthorized error response.
     *
     * @param Request $request 
     * @return \Illuminate\Http\JsonResponse 
     */
    public function logout(Request $request): JsonResponse
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out successfully'], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
