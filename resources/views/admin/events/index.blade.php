@extends('layouts.admin')

@section('title', 'Kelola Event - Admin')
@section('page_title', 'Kelola Event')
@section('page_subtitle', 'Manajemen daftar acara dan aktivitas Amikom Event Hub.')

@section('content')
{{-- Action Header --}}
<div class="mb-6 flex justify-end">
    <a href="{{ route('admin.events.create') }}"
        class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Event Baru
    </a>
</div>

{{-- Table Container --}}
<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50/50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-5 w-20">No</th>
                    <th class="px-8 py-5">Poster</th>
                    <th class="px-8 py-5">Informasi Event</th>
                    <th class="px-8 py-5">Harga & Stok</th>
                    <th class="px-8 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($events as $index => $event)
                <tr class="hover:bg-slate-50/30 transition-colors">
                    {{-- Nomor --}}
                    <td class="px-8 py-6 font-bold text-slate-400">
                        {{ $events->firstItem() + $index }}
                    </td>

                    {{-- Poster --}}
                    <td class="px-8 py-6">
                        <div class="w-14 h-20 bg-slate-100 rounded-xl overflow-hidden shadow-sm border border-slate-100">
                            @if($event->poster_path)
                                {{-- PERBAIKAN: Mengarah ke folder public/assets --}}
                                <img src="{{ asset('assets/' . $event->poster_path) }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.src='https://placehold.co/400x600?text=No+File'">
                            @else
                                <img src="https://placehold.co/400x600?text=No+Image" class="w-full h-full object-cover grayscale opacity-50">
                            @endif
                        </div>
                    </td>

                    {{-- Informasi --}}
                    <td class="px-8 py-6">
                        <p class="font-black text-slate-800 text-base leading-tight mb-1">{{ $event->title }}</p>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded text-[10px] font-bold uppercase tracking-wide">
                                {{ $event->category->name ?? 'Uncategorized' }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium italic">
                                {{-- Pastikan kolom date sudah di-cast sebagai date di Model Event --}}
                                {{ is_string($event->date) ? $event->date : $event->date->format('d M Y') }}
                            </span>
                        </div>
                    </td>

                    {{-- Harga & Stok --}}
                    <td class="px-8 py-6">
                        <p class="font-bold text-indigo-600">Rp {{ number_format($event->price, 0, ',', '.') }}</p>
                        <p class="text-xs font-semibold {{ $event->stock < 10 ? 'text-rose-500' : 'text-slate-400' }}">
                            Stok: {{ $event->stock }}
                        </p>
                    </td>

                    {{-- Tombol Aksi --}}
                    <td class="px-8 py-6 text-center">
                        <div class="flex justify-center gap-2">
                            {{-- Edit --}}
                            <a href="{{ route('admin.events.edit', $event->id) }}" 
                               class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                               title="Edit Event">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- Hapus --}}
                            <form action="{{ route('admin.events.destroy', $event->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara ini secara permanen?');"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition-all shadow-sm"
                                        title="Hapus Event">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    {{-- PERBAIKAN: Colspan disesuaikan dengan jumlah kolom (5) --}}
                    <td colspan="5" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mb-4 border border-dashed border-slate-200">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2 2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.586a1 1 0 00-.707.293l-1.414 1.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 009.586 13H4" />
                                </svg>
                            </div>
                            <p class="text-slate-500 font-black text-lg">Event Kosong</p>
                            <p class="text-slate-400 text-sm mt-1">Mulai buat event seru pertama Anda sekarang.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Area --}}
    @if($events->hasPages())
    <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
        {{ $events->links() }}
    </div>
    @endif
</div>
@endsection