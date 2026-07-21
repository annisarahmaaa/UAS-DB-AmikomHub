<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    // Mengarahkan ke halaman login Google
    public function redirectToGoogle(\Illuminate\Http\Request $request)
    {
        if ($request->has('role')) {
            session(['register_role' => $request->role]);
        }
        return Socialite::driver('google')->redirect();
    }

    // Menangani callback dari Google
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // 1. Cari apakah user dengan email tersebut sudah ada di database
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Ambil role dari session jika ada, atau default ke 'user'
                $role = session('register_role', 'user');

                // Jika user belum ada, buat akun baru
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt('password_default_rahasia_google_sso_12345'),
                    'role' => $role, 
                ]);
                
                // Hapus session role
                session()->forget('register_role');
            } else {
                // Jika akun sudah ada (misal sudah di-set jadi admin), cukup update google_id saja
                // TANPA merubah role yang sudah ada!
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            }

            Auth::login($user);

            // 2. CEK ROLE USER: Jika dia adalah Admin, langsung lempar ke Dashboard Admin!
            if (trim(strtolower($user->role)) === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Admin!');
            }

            // Jika user biasa (pembeli), arahkan ke homepage publik
            return redirect()->route('home')->with('success', 'Berhasil login menggunakan Google!');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login via Google: ' . $e->getMessage());
        }
    }
}