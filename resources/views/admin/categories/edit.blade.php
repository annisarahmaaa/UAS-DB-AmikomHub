@extends('layouts.admin')

@section('content')
<div class="p-6 sm:p-10 space-y-6">
    <div class="mb-6">
        <h1 class="text-3xl font-semibold mb-2 text-slate-800">Edit Kategori</h1>
        <h2 class="text-slate-500">Ubah nama kategori sesuai kebutuhan.</h2>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 max-w-2xl">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Wajib ada untuk proses update data di Laravel --}}
            
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Kategori</label>
                {{-- Value diisi dengan data nama kategori sebelumnya --}}
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none" required>
                @error('name')
                    <span class="text-sm text-red-500 mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition">Simpan Perubahan</button>
                <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 font-medium transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection