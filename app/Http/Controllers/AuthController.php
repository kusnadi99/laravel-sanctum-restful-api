<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'       => 'required|email',
            'password'    => 'required',
        ]);
     
        $user = User::where('email', $request->email)->first();
     
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'user'  => $user,
            'token' => $user->createToken('apiAuthToken')->plainTextToken
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('apiAuthToken')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token
        ], Response::HTTP_CREATED);
    }

    public function logout(Request $request)
    {
        // Revoke all tokens...
        // $request->user()->tokens()->delete();

        // Revoke specific token...
        $tokenId = $request->user()->currentAccessToken()->id;
        $request->user()->tokens()->where('id', $tokenId)->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
