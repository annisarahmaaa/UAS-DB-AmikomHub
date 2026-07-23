@extends('layouts.admin')

@section('title', 'Tambah Harga Bertahap - ' . $event->title)

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('admin.events.index') }}" class="text-slate-500 hover:text-indigo-600 transition">Event</a>
        <span class="text-slate-400">/</span>
        <a href="{{ route('admin.events.tiers.index', $event->id) }}" class="text-slate-500 hover:text-indigo-600 transition">Harga Bertahap</a>
        <span class="text-slate-400">/</span>
        <span class="text-slate-800 font-medium">Baru</span>
    </div>
    <h1 class="text-3xl font-bold text-slate-800">Tambah Rentang Harga</h1>
</div>

@if($errors->any())
<div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl font-bold">
    <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.events.tiers.store', $event->id) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden max-w-3xl">
    @csrf
    
    <div class="p-8 space-y-8">
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kategori/Tier</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   placeholder="Misal: Early Bird, Presale 1"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Harga Spesial (Rp)</label>
            <input type="number" name="price" value="{{ old('price') }}" required min="0"
                   placeholder="Misal: 50000"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            <p class="text-xs text-slate-500 mt-2">Pastikan harga ini lebih murah dari harga dasar (Rp {{ number_format($event->price, 0, ',', '.') }}) jika ini untuk promo awal.</p>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Mulai Berlaku</label>
                <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" required
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Berakhir Pada</label>
                <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" required
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
        </div>
    </div>
    
    <div class="px-8 py-5 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-4">
        <a href="{{ route('admin.events.tiers.index', $event->id) }}" class="text-slate-600 hover:text-slate-800 font-medium px-4 py-2">Batal</a>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-indigo-200">
            Simpan Harga Baru
        </button>
    </div>
</form>
@endsection
