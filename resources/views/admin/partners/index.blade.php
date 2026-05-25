@extends('layouts.admin')

@section('title', 'Kelola Partner - Admin')
@section('page_title', 'Kelola Partner')
@section('page_subtitle', 'Manajemen daftar mitra Amikom Event Hub.')

@section('content')
<div class="mb-6 flex justify-between items-center">
    {{-- Form Pencarian (Search Basic) untuk UTS Soal 3 --}}
    <form action="{{ route('admin.partners.index') }}" method="GET" class="flex gap-2 w-1/2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama partner..." class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 outline-none shadow-sm">
        <button type="submit" class="px-6 py-3 bg-slate-800 text-white rounded-2xl font-bold shadow-sm">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.partners.index') }}" class="px-6 py-3 bg-rose-50 text-rose-600 rounded-2xl font-bold">Reset</a>
        @endif
    </form>

    <a href="{{ route('admin.partners.create') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg">
        Tambah Partner Baru
    </a>
</div>

{{-- Alert Sukses --}}
@if(session('success'))
    <div class="mb-6 px-6 py-4 bg-green-50 text-green-700 rounded-2xl font-bold border border-green-100">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50/50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-5">No</th>
                    <th class="px-8 py-5">Logo</th>
                    <th class="px-8 py-5">Nama Partner</th>
                    <th class="px-8 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($partners as $index => $partner)
                <tr class="hover:bg-slate-50/30 transition-colors">
                    <td class="px-8 py-6 font-bold text-slate-400">{{ $index + 1 }}</td>
                    <td class="px-8 py-6">
                        <div class="w-16 h-16 bg-slate-100 rounded-xl overflow-hidden border border-slate-100">
                            <img src="{{ $partner->logo_url }}" class="w-full h-full object-cover">
                        </div>
                    </td>
                    <td class="px-8 py-6 font-black text-slate-800">{{ $partner->name }}</td>
                    <td class="px-8 py-6 text-center">
                        {{-- Tombol Aksi Fungsional (Soal 2) --}}
                        <div class="flex justify-center gap-2">
                            {{-- Tombol Edit --}}
                            <a href="{{ route('admin.partners.edit', $partner->id) }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-lg font-bold transition">Edit</a>
                            
                            {{-- Form Hapus --}}
                            <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus partner ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-lg font-bold transition">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-6 text-center font-bold text-slate-400">Tidak ada data partner.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection