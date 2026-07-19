<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login atau belum
        if (!Auth::check()) {
            // Jika belum login, tendang paksa ke halaman login admin
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu untuk mengakses dashboard.');
        }

        // 2. Jika sudah login, izinkan mereka melanjutkan ke rute yang dituju (dashboard, events, dll)
        return $next($request);
    }
}