<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Mendapatkan informasi pengunjung
            $device = $request->header('User-Agent');
            $url = $request->fullUrl();
            $ip_address = $request->ip();

            // Menyimpan data ke tabel visitor
            Visitor::create([
                'device' => $device,
                'url' => $url,
                'ip_address' => $ip_address,
            ]);
        } catch (\Exception $e) {
            // Jika terjadi error, tetap lanjutkan request
            // Log error jika diperlukan
            \Log::error('Visitor logging failed: ' . $e->getMessage());
        }

        return $next($request);
    }
}
