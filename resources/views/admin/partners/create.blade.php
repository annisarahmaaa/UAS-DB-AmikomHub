@extends('layouts.admin')

@section('title', 'Tambah Partner - Admin')
@section('page_title', 'Tambah Partner Baru')
@section('page_subtitle', 'Masukkan informasi mitra resmi Amikom Event Hub.')

@section('content')
<div class="max-w-2xl">
    {{-- Tombol Kembali --}}
    <div class="mb-6">
        <a href="{{ route('admin.partners.index') }}" class="text-slate-400 hover:text-indigo-600 font-bold flex items-center gap-2 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    {{-- Card Form --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-8 md:p-10">
        {{-- Form dengan method POST (Tugas 4.2) --}}
        <form action="{{ route('admin.partners.store') }}" method="POST" class="space-y-6">
            @csrf
            
            {{-- Input Nama --}}
            <div>
                <label for="name" class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">Nama Partner</label>
                <input type="text" name="name" id="name" required
                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 transition-all placeholder:text-slate-300"
                    placeholder="Contoh: PT. Amikom Media">
            </div>

            {{-- Input Logo URL --}}
            <div>
                <label for="logo_url" class="block text-sm font-black text-slate-700 uppercase tracking-wider mb-2">URL Logo Partner</label>
                <input type="text" name="logo_url" id="logo_url" required
                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:outline-none focus:ring-4 focus:ring-indigo-50 focus:border-indigo-600 transition-all placeholder:text-slate-300"
                    placeholder="https://placehold.co/200x200">
            </div>

            {{-- Action Button --}}
            <div class="pt-4">
                <button type="submit" 
                    class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black shadow-lg shadow-indigo-200 hover:bg-indigo-700 active:scale-[0.98] transition-all">
                    Simpan Partner
                </button>
            </div>
        </form>
    </div>
</div>
@endsection