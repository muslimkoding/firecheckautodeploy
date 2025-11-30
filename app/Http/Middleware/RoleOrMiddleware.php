<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleOrMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Cek apakah user memiliki salah satu dari roles yang diberikan
        $hasRole = false;
        foreach ($roles as $role) {
            if (auth()->user()->hasRole($role)) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            abort(403, 'Unauthorized action. Hanya ' . implode(' atau ', $roles) . ' yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
