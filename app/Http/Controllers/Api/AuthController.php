<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // login
    public function login(Request $request)
    {
        $credentials = $request->only(['username', 'password']);
        // attemt login
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Gagal Login.',
                'error' => 'Username atau password tidak sesuai.',
            ], 500);
        }

        // get logged in user with relation
        $attemptedUser = JWTAuth::user();
        $user = User::getProfile($attemptedUser->id);

        return response()->json([
            'statusCode' => 200,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'user' => $user
            ]
        ]);
    }

    // logout
    public function logout()
    {
        try {
            // $user = JWTAuth::parseToken()->authenticate();

            // Menggunakan JWTAuth untuk menghapus token yang ada
            JWTAuth::invalidate(JWTAuth::getToken());

            // Menyediakan respons bahwa logout berhasil
            return response()->json([
                'statusCode' => 200,
                'message' => 'Logout berhasil.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Kesalahan server.',
                'error' => 'Username atau password tidak sesuai.',
            ], 500);
        }
    }
}
