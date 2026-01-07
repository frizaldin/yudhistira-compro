<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user() && !auth()->guard('company')->user() && !auth()->guard('supplier')->user()) {
            return redirect('backend/login');
        } else if (auth()->user() && auth()->guard('company')->user() && auth()->guard('supplier')->user()) {
            auth()->logout();
            auth()->guard('company')->logout();
            auth()->guard('supplier')->logout();
            return redirect('backend/login');
        }
        return $next($request);
    }
}
