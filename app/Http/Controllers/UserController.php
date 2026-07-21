<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // ==========================================
    // FITUR 1: SUPERADMIN KELOLA PENGGUNA
    // ==========================================
    public function index()
    {
        // Satpam: Hanya superadmin yang boleh membuka halaman ini
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses Ditolak! Halaman ini khusus Superadmin.');
        }

        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        // Satpam: Hanya superadmin yang boleh mengubah role
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses Ditolak!');
        }

        $request->validate([
            'role' => 'required|in:user,organizer,superadmin'
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Role pengguna ' . $user->name . ' berhasil diubah menjadi ' . strtoupper($request->role) . '!');
    }

    // ==========================================
    // FITUR 2: USER UPGRADE JADI PENYELENGGARA
    // ==========================================
    public function upgradeToOrganizer()
    {
        $user = Auth::user();

        // Kalau sudah jadi penyelenggara atau superadmin, hentikan
        if ($user->role === 'organizer' || $user->role === 'superadmin') {
            return back()->with('info', 'Akun kamu sudah berstatus Penyelenggara/Admin!');
        }

        // Ubah role user menjadi organizer
        $user->update(['role' => 'organizer']);

        // Langsung arahkan ke dashboard kelola event
        return redirect()->route('admin.events.index')->with('success', 'Selamat! Akunmu berhasil di-upgrade menjadi Penyelenggara Event. Sekarang kamu bisa mulai membuat acara!');
    }
}