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
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu untuk mengakses dashboard.');
        }

        $user = Auth::user();

        // 2. MODE KETAT: Jika rute meminta 'superadmin', pastikan rolenya benar-benar superadmin!
        if ($role === 'superadmin' && $user->role !== 'superadmin') {
            abort(403, 'BAHAYA! Kamu tidak memiliki hak akses Superadmin untuk menu manajemen sistem ini.');
        }

        // 3. MODE UMUM: Izinkan masuk JIKA rolenya 'superadmin' ATAU 'organizer'
        if ($user->role === 'superadmin' || $user->role === 'organizer') {
            return $next($request);
        }

        // 4. Jika masih user biasa, blokir aksesnya!
        abort(403, 'Akses Ditolak! Kamu harus menjadi Penyelenggara Event atau Superadmin untuk mengakses halaman ini.');
    }
}