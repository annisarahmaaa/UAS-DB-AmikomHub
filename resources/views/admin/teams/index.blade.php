@extends('layouts.admin')

@section('title', 'Kelola Tim')
@section('page_title', 'Kelola Anggota Tim')
@section('page_subtitle', 'Atur daftar anggota tim yang tampil di halaman depan.')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.teams.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition duration-200">
        + Tambah Anggota Tim
    </a>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Foto</th>
                    <th class="px-8 py-4">Nama Lengkap</th>
                    <th class="px-8 py-4">Jabatan (Role)</th>
                    <th class="px-8 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($teams as $team)
                <tr class="hover:bg-slate-50 transition duration-150">
                    <td class="px-8 py-4">
                        <img src="{{ $team->photo_url }}" alt="{{ $team->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-indigo-100">
                    </td>
                    <td class="px-8 py-4 font-bold text-slate-800">
                        {{ $team->name }}
                    </td>
                    <td class="px-8 py-4 text-slate-600 font-medium">
                        {{ $team->role }}
                    </td>
                    <td class="px-8 py-4 text-right">
                        <a href="{{ route('admin.teams.edit', $team->id) }}" class="inline-block px-3 py-1.5 bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-lg text-xs font-bold uppercase transition mr-2">Edit</a>
                        <form action="{{ route('admin.teams.destroy', $team->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus tim ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 bg-rose-100 text-rose-700 hover:bg-rose-200 rounded-lg text-xs font-bold uppercase transition">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-medium">
                        Belum ada data anggota tim.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
