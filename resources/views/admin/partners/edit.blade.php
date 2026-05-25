@extends('layouts.admin')

@section('title', 'Edit Partner - Admin')
@section('page_title', 'Edit Partner')
@section('page_subtitle', 'Ubah data mitra Amikom Event Hub.')

@section('content')
<div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm max-w-2xl">
    <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <label for="name" class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Partner</label>
            <input type="text" name="name" id="name" value="{{ old('name', $partner->name) }}" class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 outline-none" required>
            @error('name') <span class="text-sm text-rose-500 mt-1 font-semibold">{{ $message }}</span> @enderror
        </div>

        <div class="mb-8">
            <label for="logo_url" class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">URL Logo Partner</label>
            <input type="url" name="logo_url" id="logo_url" value="{{ old('logo_url', $partner->logo_url) }}" class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 outline-none" required>
            @error('logo_url') <span class="text-sm text-rose-500 mt-1 font-semibold">{{ $message }}</span> @enderror
            <p class="text-xs text-slate-400 mt-2 font-medium">*Masukkan link gambar (URL) yang valid.</p>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg hover:bg-indigo-700 transition">Simpan Perubahan</button>
            <a href="{{ route('admin.partners.index') }}" class="px-8 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition">Batal</a>
        </div>
    </form>
</div>
@endsection