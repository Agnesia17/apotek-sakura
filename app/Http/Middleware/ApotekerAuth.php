<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApotekerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = Auth::user();

        if ($user->role !== 'apoteker') {
            return redirect()->back()->with('error', 'Akses ditolak! Anda tidak memiliki hak akses untuk halaman ini.');
        }

        return $next($request);
    }
}
