<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends Controller
{
    /**
     * Verifikasi email dari link yang dikirim ke user.
     * GET /api/v1/email/verify/{id}/{hash}?expires=...&signature=...
     */
    public function verify(Request $request): JsonResponse
    {
        if (!URL::hasValidSignature($request)) {
            return response()->json([
                'code' => 403,
                'success' => false,
                'message' => 'Link verifikasi tidak valid atau sudah kadaluarsa.',
            ], 403);
        }

        $teacher = Teacher::findOrFail($request->route('id'));

        if (hash_equals(
            (string) $request->route('hash'),
            (string) sha1($teacher->getEmailForVerification())
        )) {
            if ($teacher->hasVerifiedEmail()) {
                return response()->json([
                    'code' => 200,
                    'success' => true,
                    'message' => 'Email sudah terverifikasi sebelumnya.',
                ]);
            }

            $teacher->markEmailAsVerified();

            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Email berhasil diverifikasi. Silakan login.',
            ]);
        }

        return response()->json([
            'code' => 403,
            'success' => false,
            'message' => 'Link verifikasi tidak valid.',
        ], 403);
    }

    /**
     * Kirim ulang email verifikasi (user sudah login tapi belum verify).
     * POST /api/v1/email/resend
     */
    public function resend(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:teachers,email',
        ]);

        $teacher = Teacher::where('email', $request->email)->firstOrFail();

        if ($teacher->hasVerifiedEmail()) {
            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => 'Email sudah terverifikasi.',
            ]);
        }

        $teacher->sendEmailVerificationNotification();

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Link verifikasi baru telah dikirim ke email Anda.',
        ]);
    }
}
