<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;

class EnsureTeacherApiAuth
{
    /**
     * Handle an incoming request.
     * Pastikan request punya JWT token teacher (guard api) yang valid.
     * Jika belum login / token invalid: response 402 dengan message "Harap Login Terlebih Dahulu".
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (!Auth::guard('api')->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Harap Login Terlebih Dahulu',
                ], 402);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Harap Login Terlebih Dahulu',
            ], 402);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Harap Login Terlebih Dahulu',
            ], 402);
        }

        return $next($request);
    }
}
