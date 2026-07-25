@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan - Amikom Event Hub')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-32 flex flex-col items-center justify-center text-center">
    <div class="mb-8 relative">
        <h1 class="text-9xl font-extrabold text-slate-200 tracking-widest">404</h1>
        <div class="absolute inset-0 flex items-center justify-center">
            <span class="text-4xl md:text-5xl font-black text-indigo-600">Oops!</span>
        </div>
    </div>
    
    <h2 class="text-2xl md:text-3xl font-bold text-slate-800 mb-4">Halaman yang Anda cari tidak ditemukan</h2>
    <p class="text-lg text-slate-500 mb-8 max-w-lg">
        Rute atau halaman yang Anda akses mungkin telah dihapus, namanya diubah, atau sementara tidak tersedia.
    </p>
    
    <a href="{{ route('home') }}" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Beranda
    </a>
</section>
@endsection
