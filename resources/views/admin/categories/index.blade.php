@extends('layouts.admin')

@section('content')
<div class="p-6 sm:p-10 space-y-6">
    <div class="flex flex-col space-y-6 md:space-y-0 md:flex-row justify-between">
        <div class="mr-6">
            <h1 class="text-4xl font-semibold mb-2 text-slate-800">Manajemen Kategori</h1>
            <h2 class="text-slate-500">Kelola daftar kategori event AmikomEventHub.</h2>
        </div>
        <div class="flex flex-wrap items-start justify-end -mb-3">
            {{-- Tombol Tambah Kategori (Dinamis Route) --}}
            <a href="{{ route('admin.categories.create') }}" class="inline-flex px-5 py-3 text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl font-medium shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Kategori
            </a>
        </div>
    </div>

    {{-- Alert Pesan Sukses --}}
    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Pencarian / Search Basic (Soal 3 UTS) --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kategori..." class="w-full md:w-1/3 px-4 py-2 border border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 outline-none">
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition">Cari</button>
            @if(request('search'))
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 text-left text-sm font-semibold uppercase tracking-wider">
                    <tr>
                        {{-- UBAH 1: Mengganti judul kolom dari ID menjadi No --}}
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4">Slug</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    {{-- Looping Data Kategori dari Database --}}
                    @forelse($categories as $category)
                    <tr class="hover:bg-slate-50 transition-colors">
                        {{-- UBAH 2: Menggunakan loop iteration agar selalu urut 1,2,3... --}}
                        <td class="px-6 py-4 font-medium">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-slate-500">{{ $category->slug }}</td>
                        <td class="px-6 py-4 flex justify-center gap-3">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1.5 rounded-lg text-sm font-semibold transition">Edit</a>
                            
                            {{-- Form Hapus --}}
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1.5 rounded-lg text-sm font-semibold transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-slate-500">Tidak ada data kategori ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection