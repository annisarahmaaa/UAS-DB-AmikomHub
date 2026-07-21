<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function create()
    {
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses Ditolak!');
        }

        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses Ditolak!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,organizer,superadmin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses Ditolak!');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses Ditolak!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,organizer,superadmin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'superadmin') {
            abort(403, 'Akses Ditolak!');
        }

        if ($user->id === Auth::id()) {
            return back()->with('info', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
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