<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    //
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'phone' => 'required|string|max:15|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => bcrypt($request->password),
        'verification_code' => random_int(100000, 999999),
        'is_verified' => false,
    ]);


    return response()->json([
        'user' => $user,
        'message' => 'User registered successfully, please check the verification code in logs.'
    ]);
}
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    if (!$user->is_verified) {
        return response()->json(['message' => 'Account not verified'], 403);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'access_token' => $token,
    ]);
}
public function verifyCode(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'verification_code' => 'required|numeric',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || $user->verification_code != $request->verification_code) {
        return response()->json(['message' => 'Invalid verification code'], 400);
    }

    $user->is_verified = true;
    $user->verification_code = null; // Clear the code after verification
    $user->save();

    return response()->json(['message' => 'Account verified successfully']);
}

}
