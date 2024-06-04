<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid email or password.'
            ], 401);
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => 'Invalid email or password.'
            ], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        $token = $user->createToken('admin-token', ['create', 'update', 'delete']);
        $token->accessToken->expires_at = Carbon::now()->addMinutes(60);
        $token->accessToken->save();

        return response()->json([
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            $tokenId = $user->currentAccessToken()->id;
            $user->tokens()->where('id', $tokenId)->delete();

            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }

        return response()->json([
            'message' => 'No authenticated user found'
        ], 401);
    }

    public function refreshToken(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        /** @var User $user */
        $user = Auth::user();

        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        $newToken = $user->createToken('admin-token', ['create', 'update', 'delete']);
        $newToken->accessToken->expires_at = Carbon::now()->addMinutes(60);
        $newToken->accessToken->save();

        return response()->json([
            'token' => $newToken->plainTextToken,
            'expires_at' => $newToken->accessToken->expires_at
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('admin-token', ['create', 'update', 'delete']);
        $token->accessToken->expires_at = Carbon::now()->addMinutes(60);
        $token->accessToken->save();

        return response()->json([
            'message' => 'User created successfully.',
            'token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at
        ], 201);
    }
}
