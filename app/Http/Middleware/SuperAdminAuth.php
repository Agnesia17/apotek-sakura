<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated, has admin session, and is superadmin
        if (
            !auth()->check() ||
            !session('admin_logged_in') ||
            !auth()->user()->isSuperAdmin()
        ) {

            return redirect()->route('dashboard')->with('error', 'Akses ditolak! Anda tidak memiliki hak akses untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
