@extends('layouts.admin')

@section('title', 'Kelola Harga Bertahap - ' . $event->title)

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <div class="flex items-center gap-2 mb-2">
            <a href="{{ route('admin.events.index') }}" class="text-slate-500 hover:text-indigo-600 transition">Event</a>
            <span class="text-slate-400">/</span>
            <span class="text-slate-800 font-medium">Harga Bertahap</span>
        </div>
        <h1 class="text-3xl font-bold text-slate-800">{{ $event->title }}</h1>
        <p class="text-slate-500 mt-1">Kelola penjualan bertahap (Early Bird, Presale) untuk event ini.</p>
    </div>
    <a href="{{ route('admin.events.tiers.create', $event->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition-all shadow-lg shadow-indigo-200 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Harga Baru
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-100 text-emerald-700 rounded-xl font-bold">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
    <div class="p-6 bg-slate-50 border-b border-slate-200">
        <h3 class="font-bold text-slate-800">Harga Dasar Event (Regular)</h3>
        <p class="text-slate-500 text-sm mt-1">Jika hari ini tidak masuk dalam rentang tanggal manapun di bawah ini, maka harga dasar ini yang akan dipakai: <strong class="text-indigo-600 text-lg ml-2">Rp {{ number_format($event->price, 0, ',', '.') }}</strong></p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4">Nama (Kategori)</th>
                    <th class="px-6 py-4">Harga Spesial</th>
                    <th class="px-6 py-4">Mulai Berlaku</th>
                    <th class="px-6 py-4">Berakhir Pada</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($tiers as $tier)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-bold text-slate-800">{{ $tier->name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-emerald-600">Rp {{ number_format($tier->price, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        {{ $tier->start_date->format('d M Y, H:i') }}
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        {{ $tier->end_date->format('d M Y, H:i') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.events.tiers.edit', [$event->id, $tier->id]) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                            
                            <form action="{{ route('admin.events.tiers.destroy', [$event->id, $tier->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rentang harga ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 font-medium">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                        Belum ada harga bertahap. Event ini hanya menggunakan Harga Dasar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
