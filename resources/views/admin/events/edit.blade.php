@extends('layouts.admin')

@section('title', 'Edit Event - Admin')
@section('page_title', 'Edit Event')
@section('page_subtitle', 'Ubah detail acara yang sudah ada.')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
        
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Judul Event --}}
            <div>
                <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Judul Event</label>
                <input type="text" name="title" value="{{ old('title', $event->title) }}" 
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-semibold @error('title') border-red-400 @enderror" required>
                @error('title') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Kategori</label>
                <select name="category_id" 
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-semibold @error('category_id') border-red-400 @enderror" required>
                    <option value="" disabled>Pilih Kategori Event</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Deskripsi</label>
                <textarea name="description" rows="4" 
                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-semibold @error('description') border-red-400 @enderror">{{ old('description', $event->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Tanggal & Waktu --}}
                <div>
                    <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Tanggal & Waktu</label>
                    <input type="datetime-local" name="date" value="{{ old('date', $event->date->format('Y-m-d\TH:i')) }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-semibold @error('date') border-red-400 @enderror" required>
                    @error('date') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- Lokasi --}}
                <div>
                    <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location', $event->location) }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-semibold @error('location') border-red-400 @enderror" required>
                    @error('location') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Harga --}}
                <div>
                    <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Harga (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                        <input type="number" name="price" value="{{ old('price', $event->price) }}" 
                            class="w-full pl-12 pr-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-semibold" required min="0">
                    </div>
                    @error('price') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- Kapasitas --}}
                <div>
                    <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Kapasitas (Stok)</label>
                    <input type="number" name="stock" value="{{ old('stock', $event->stock) }}" 
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-semibold" required min="1">
                    @error('stock') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Poster --}}
            <div>
                <label class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-widest">Poster Event (Opsional)</label>
                
                @if($event->poster_path)
                <div class="mb-4 flex items-center gap-4 p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                    <img src="{{ asset('storage/' . $event->poster_path) }}" class="w-20 h-28 object-cover rounded-xl shadow-md">
                    <div>
                        <p class="text-sm font-bold text-indigo-900">Poster Saat Ini</p>
                        <p class="text-xs text-indigo-500">Abaikan jika tidak ingin mengubah poster.</p>
                    </div>
                </div>
                @endif

                <div class="relative group">
                    <input type="file" name="poster" accept="image/*" 
                        class="w-full px-5 py-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl group-hover:border-indigo-400 transition cursor-pointer font-semibold">
                </div>
                @error('poster') <p class="text-red-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Action Buttons --}}
            <div class="pt-6 flex items-center justify-end gap-4 border-t border-slate-50">
                <a href="{{ route('admin.events.index') }}" class="px-6 py-4 text-slate-400 hover:text-slate-600 font-bold transition">
                    Batal
                </a>
                <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection