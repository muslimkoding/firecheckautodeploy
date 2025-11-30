<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user memiliki role superadmin
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized action. Hanya Super Admin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
