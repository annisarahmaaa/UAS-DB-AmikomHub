@extends('layouts.admin')

@section('title', 'Kelola Partner - Admin')
@section('page_title', 'Kelola Partner')
@section('page_subtitle', 'Manajemen daftar mitra Amikom Event Hub.')

@section('content')
<div class="mb-6 flex justify-end">
    <a href="{{ route('admin.partners.create') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg">
        Tambah Partner Baru
    </a>
</div>

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
                {{-- Pembuka Loop (Pastikan ini ada!) --}}
                @foreach($partners as $index => $partner)
                <tr class="hover:bg-slate-50/30 transition-colors">
                    <td class="px-8 py-6 font-bold text-slate-400">{{ $index + 1 }}</td>
                    <td class="px-8 py-6">
                        <div class="w-16 h-16 bg-slate-100 rounded-xl overflow-hidden border border-slate-100">
                            <img src="{{ $partner->logo_url }}" class="w-full h-full object-cover">
                        </div>
                    </td>
                    <td class="px-8 py-6 font-black text-slate-800">{{ $partner->name }}</td>
                    <td class="px-8 py-6 text-center">
                        {{-- Tombol Aksi --}}
                        <div class="flex justify-center gap-2">
                            <button class="p-2 bg-rose-50 text-rose-600 rounded-lg">Hapus</button>
                        </div>
                    </td>
                </tr>
                {{-- Penutup Loop --}}
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection