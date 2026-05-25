@extends('layouts.admin')

@section('content')
<div class="p-6 sm:p-10 space-y-6">
    <div class="mb-6">
        <h1 class="text-3xl font-semibold mb-2 text-slate-800">Tambah Kategori Baru</h1>
        <h2 class="text-slate-500">Silakan masukkan nama kategori event yang ingin ditambahkan.</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 max-w-2xl">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Kategori</label>
                <input type="text" name="name" id="name" placeholder="Contoh: Seminar, Konser..." class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none" required>
                @error('name')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition">Simpan Kategori</button>
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-medium transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection