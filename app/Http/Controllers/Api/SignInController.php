<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignInController extends Controller
{
    /**
     * Login teacher (email + password).
     * Returns JWT access_token and user data.
     */
    public function run(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'code'    => 401,
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        /** @var \App\Models\Teacher $teacher */
        $teacher = Auth::guard('api')->user();

        if (!$teacher->hasVerifiedEmail()) {
            Auth::guard('api')->logout();
            return response()->json([
                'code'    => 403,
                'success' => false,
                'message' => 'Email belum diverifikasi. Silakan cek inbox dan klik link verifikasi, atau minta kirim ulang.',
            ], 403);
        }

        return response()->json([
            'code'    => 200,
            'success' => true,
            'result'  => [
                'access_token' => $token,
                'data'         => $teacher,
            ],
        ]);
    }
}
