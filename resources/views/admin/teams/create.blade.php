@extends('layouts.admin')

@section('title', 'Tambah Anggota Tim')
@section('page_title', 'Tambah Anggota Tim Baru')

@section('content')
<div class="max-w-2xl bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
    <form action="{{ route('admin.teams.store') }}" method="POST">
        @csrf
        
        <div class="mb-6">
            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
            <input type="text" name="name" class="w-full border-slate-200 rounded-xl px-4 py-3 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('name') }}" required>
            @error('name') <span class="text-rose-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold text-slate-700 mb-2">Jabatan (Role)</label>
            <input type="text" name="role" class="w-full border-slate-200 rounded-xl px-4 py-3 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('role') }}" placeholder="Contoh: CEO & Founder" required>
            @error('role') <span class="text-rose-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-bold text-slate-700 mb-2">URL Foto (Link saja)</label>
            <input type="url" name="photo_url" class="w-full border-slate-200 rounded-xl px-4 py-3 focus:ring-indigo-500 focus:border-indigo-500" value="{{ old('photo_url') }}" placeholder="https://..." required>
            <p class="text-xs text-slate-500 mt-2">Anda bisa menggunakan link dari ui-avatars atau image hosting lainnya.</p>
            @error('photo_url') <span class="text-rose-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition">Simpan Anggota</button>
            <a href="{{ route('admin.teams.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold transition">Batal</a>
        </div>
    </form>
</div>
@endsection
