@extends('layouts.app')

@section('content')
    {{-- Section Hero --}}
    <section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 space-y-8">
            <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">
                #1 Event Platform
            </span>
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight">
                Temukan & Pesan <span class="text-indigo-600">Tiket Event</span> Impianmu.
            </h1>
            <p class="text-lg text-slate-500 max-w-lg leading-relaxed">
                Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan Midtrans.
            </p>
            <div class="flex gap-4">
                <a href="#events" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform">
                    Mulai Jelajah
                </a>
                <a href="#" class="px-8 py-4 border-2 border-slate-200 rounded-2xl font-bold text-lg hover:border-indigo-600 hover:text-indigo-600 transition">
                    Cara Pesan
                </a>
            </div>
        </div>
        <div class="flex-1 relative">
            <div class="absolute -top-10 -left-10 w-64 h-64 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            {{-- Gambar Hero menggunakan asset dari folder public/assets --}}
            <img src="{{ asset('assets/concert.png') }}" alt="Concert" class="rounded-[2rem] shadow-2xl relative z-10 w-full object-cover aspect-[4/5] object-center">

            <div class="absolute -bottom-6 -left-6 glass p-6 rounded-2xl shadow-xl z-20 border border-white">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold uppercase">Terverifikasi</p>
                        <p class="font-bold">Pembayaran Aman via Midtrans</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Section Daftar Event --}}
    <section id="events" class="max-w-7xl mx-auto px-6 py-20">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-extrabold mb-2">Event Terdekat</h2>
                <p class="text-slate-500 font-medium">Jangan sampai ketinggalan acara seru minggu ini!</p>
            </div>
        </div>

        {{-- Blok Navigasi Filter Kategori Dinamis --}}
        <div class="mb-12 flex flex-wrap gap-4 justify-center">
            <a href="/#events" 
                class="px-6 py-2 {{ !request('category') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-gray-200 text-black hover:bg-gray-300' }} rounded-full font-bold transition">
                Semua Kategori
            </a>

            @foreach($categories as $cat)
                <a href="/?category={{ $cat->slug }}#events" 
                    class="px-6 py-2 {{ request('category') == $cat->slug ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }} rounded-full font-bold transition">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        {{-- Zona Menampilkan Grid List Event Dinamis --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($events as $event)
            <div class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden">
                <div class="relative overflow-hidden aspect-[3/4]">
                    {{-- Menggunakan pengondisian dinamis sesuai instruksi soal 9.4.5 --}}
                    <img src="{{ ($event->poster_path && \Storage::disk('public')->exists($event->poster_path)) ? asset('storage/' . $event->poster_path) : 'https://placehold.co/200x600' }}" 
                         alt="{{ $event->title }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    
                    <div class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600">
                        {{-- Memanggil relasi nama kategori secara bersambung --}}
                        {{ $event->category->name ?? 'Uncategorized' }}
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition">{{ $event->title }}</h3>
                    <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{-- Parsing tanggal menggunakan Carbon --}}
                        <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t">
                        <span class="text-2xl font-black text-indigo-600">
                            {{ $event->price > 0 ? 'Rp ' . number_format($event->price, 0, ',', '.') : 'Gratis' }}
                        </span>
                        {{-- PERBAIKAN (9.4.6 Langkah 3): Mengubah rute statis menjadi pemanggilan rute dinamis --}}
                        <a href="{{ route('events.show', $event->id) }}"
                            class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-20">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2 2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3.586a1 1 0 00-.707.293l-1.414 1.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 009.586 13H4" />
                    </svg>
                </div>
                <p class="text-slate-500 font-bold text-lg">Belum ada event tersedia.</p>
                <p class="text-slate-400">Coba pilih kategori lain atau kembali lagi nanti.</p>
            </div>
            @endforelse
        </div>
    </section>

    {{-- ================= BAGIAN PARTNER (SOAL 4 UTS) ================= --}}
    <section class="py-16 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 sm:px-8">
            {{-- Judul Section --}}
            <div class="text-center mb-12">
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Official Partners</h2>
                <p class="text-slate-400 text-sm font-medium mt-1">Mitra resmi yang mendukung kelancaran berbagai event di AmikomEventHub</p>
            </div>

            {{-- Proses Perulangan @forelse untuk Merender Daftar Partner --}}
            <div class="flex flex-wrap justify-center items-center gap-8 md:gap-16">
                @forelse($partners as $partner)
                    <div class="w-36 h-20 flex items-center justify-center p-3 bg-slate-50/50 rounded-2xl border border-slate-100 hover:shadow-md hover:border-indigo-100 transition-all duration-300 group">
                        {{-- Menampilkan Gambar Logo dari URL Database --}}
                        <img src="{{ $partner->logo_url }}" 
                            alt="Logo {{ $partner->name }}" 
                            title="{{ $partner->name }}" 
                            class="max-h-full max-w-full object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300">
                    </div>
                @empty
                    {{-- Tampilan jika data partner di database masih kosong --}}
                    <div class="text-center py-4">
                        <p class="text-slate-400 text-sm italic">Belum ada partner yang terdaftar saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    {{-- =============================================================== --}}

@endsection