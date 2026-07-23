@extends('layouts.admin')

@section('title', 'Buat Kupon Baru')

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-2 mb-2">
        <a href="{{ route('admin.coupons.index') }}" class="text-slate-500 hover:text-indigo-600 transition">Kupon</a>
        <span class="text-slate-400">/</span>
        <span class="text-slate-800 font-medium">Baru</span>
    </div>
    <h1 class="text-3xl font-bold text-slate-800">Buat Kupon Diskon</h1>
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

<form action="{{ route('admin.coupons.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden max-w-3xl">
    @csrf
    
    <div class="p-8 space-y-8">
        <!-- Kode Kupon -->
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Kode Kupon</label>
            <input type="text" name="code" value="{{ old('code') }}" required
                   placeholder="Misal: MERDEKA50, PRESALE, dll"
                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none uppercase font-bold tracking-widest text-lg">
            <p class="text-xs text-slate-500 mt-2">Gunakan huruf kapital dan angka tanpa spasi. Maksimal 20 karakter.</p>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <!-- Tipe Diskon -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tipe Diskon</label>
                <select name="discount_type" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Potongan Harga (Rp)</option>
                </select>
            </div>
            
            <!-- Nilai Diskon -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nilai Diskon</label>
                <input type="number" name="discount_value" value="{{ old('discount_value') }}" required min="0"
                       placeholder="Misal: 50 untuk 50% atau 10000 untuk Rp10.000"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
        </div>

        <!-- Berlaku Untuk (Event) -->
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Berlaku Untuk Event</label>
            <select name="event_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                @if(Auth::user()->role === 'superadmin')
                    <option value="">-- General (Berlaku untuk Semua Event) --</option>
                @else
                    <option value="" disabled selected>-- Pilih Event Anda --</option>
                @endif
                
                @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>{{ $event->title }}</option>
                @endforeach
            </select>
            @if(Auth::user()->role !== 'superadmin')
                <p class="text-xs text-amber-600 font-bold mt-2">*Sebagai Organizer, Anda wajib memilih Event spesifik untuk kupon ini.</p>
            @endif
        </div>

        <!-- Batasan Kupon -->
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Batas Penggunaan (Kuota)</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" min="1"
                       placeholder="Misal: 50 (Kosongkan jika tanpa batas)"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Berlaku Hingga</label>
                <input type="datetime-local" name="valid_until" value="{{ old('valid_until') }}"
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                <p class="text-xs text-slate-500 mt-2">Kosongkan jika tidak ada batas waktu.</p>
            </div>
        </div>

        <!-- Status Aktif -->
        <div class="flex items-center gap-3 bg-slate-50 p-4 rounded-xl border border-slate-200">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                   class="w-5 h-5 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500">
            <div>
                <label for="is_active" class="font-bold text-slate-700 cursor-pointer">Aktifkan Kupon Ini</label>
                <p class="text-xs text-slate-500">Kupon yang non-aktif tidak akan bisa digunakan pembeli.</p>
            </div>
        </div>
    </div>
    
    <div class="px-8 py-5 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-4">
        <a href="{{ route('admin.coupons.index') }}" class="text-slate-600 hover:text-slate-800 font-medium px-4 py-2">Batal</a>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-indigo-200">
            Simpan Kupon
        </button>
    </div>
</form>
@endsection
