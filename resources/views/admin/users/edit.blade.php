@extends('layouts.admin')
@section('title', 'Edit Pengguna')
@section('page_title', 'Edit Data Pengguna')

@section('content')
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden max-w-3xl">
    <div class="p-8 border-b">
        <h3 class="font-black text-xl">Form Edit Pengguna</h3>
    </div>

    <div class="p-8">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full border-slate-200 rounded-xl px-4 py-3 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-rose-500 @enderror" required>
                @error('name')
                    <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full border-slate-200 rounded-xl px-4 py-3 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-rose-500 @enderror" required>
                @error('email')
                    <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="role" class="block text-sm font-bold text-slate-700 mb-2">Role Pengguna</label>
                <select name="role" id="role" class="w-full border-slate-200 rounded-xl px-4 py-3 focus:ring-indigo-500 focus:border-indigo-500 @error('role') border-rose-500 @enderror" required>
                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User (Pembeli Tiket)</option>
                    <option value="organizer" {{ old('role', $user->role) === 'organizer' ? 'selected' : '' }}>Penyelenggara (Pembuat Event)</option>
                    <option value="superadmin" {{ old('role', $user->role) === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                </select>
                @error('role')
                    <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password Baru (Opsional)</label>
                <input type="password" name="password" id="password" class="w-full border-slate-200 rounded-xl px-4 py-3 focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-rose-500 @enderror" placeholder="Kosongkan jika tidak ingin mengubah password">
                @error('password')
                    <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border-slate-200 rounded-xl px-4 py-3 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ulangi password baru di atas jika diisi">
            </div>

            <div class="flex justify-end space-x-4 border-t pt-6">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition">Batal</a>
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
