<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman formulir login admin.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Memproses validasi dan autentikasi submit login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        // Menggunakan 'with' agar langsung nangkep di @if(session('error')) pada UI Blade-mu
        return back()->with('error', 'Email atau password yang Anda masukkan salah.');
    }

    /**
     * Memproses registrasi akun baru.
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,organizer',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::login($user);

        return redirect()->route($user->role === 'organizer' ? 'admin.dashboard' : 'home')->with('success', 'Pendaftaran berhasil!');
    }

    /**
     * Memproses logout admin dan menghapus session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}