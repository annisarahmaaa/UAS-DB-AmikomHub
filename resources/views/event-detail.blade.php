@extends('layouts.app')

@section('content')

    <!-- Global navbar is inherited from layouts.app -->

    <main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-1">
            <div class="sticky top-32">
                <img src="{{ $event->poster_url }}" 
                     alt="{{ $event->title }}"
                     class="w-full rounded-[2.5rem] shadow-2xl border-8 border-white object-cover aspect-[3/4]">
                
                <div class="mt-8 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <h4 class="font-bold mb-4">Penyelenggara</h4>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                            AB</div>
                        <div>
                            <p class="font-bold text-slate-800">ABP Productions</p>
                            <p class="text-xs text-slate-500">Verified Organizer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-12">
            <div class="space-y-4">
                <span class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">
                    {{ $event->category->name ?? 'Uncategorized' }}
                </span>
                
                <h1 class="text-4xl md:text-5xl font-black leading-tight">
                    {{ $event->title }}
                </h1>
                
                <div class="flex flex-wrap gap-6 text-slate-500 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>{{ $event->location }}</span>
                    </div>
                </div>
            </div>

            <div class="prose prose-slate max-w-none">
                <h3 class="text-2xl font-bold mb-4">Deskripsi Event</h3>
                <p class="text-lg text-slate-600 leading-relaxed whitespace-pre-line">
                    {{ $event->description ?? 'Tidak ada rincian deskripsi untuk acara ini.' }}
                </p>
            </div>

            <div class="bg-indigo-600 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div>
                        @php
                            $activePriceData = $event->getActivePrice();
                        @endphp
                        @if($activePriceData['tier_name'] !== 'Regular')
                            <p class="text-amber-300 font-bold uppercase tracking-widest text-sm mb-2">⭐ Harga {{ $activePriceData['tier_name'] }}!</p>
                        @else
                            <p class="text-indigo-200 font-bold uppercase tracking-widest text-sm mb-2">Harga Tiket</p>
                        @endif
                        <h2 class="text-5xl font-black">
                            @if($activePriceData['price'] > 0)
                                Rp {{ number_format($activePriceData['price'], 0, ',', '.') }} <span class="text-lg font-medium text-indigo-200">/ orang</span>
                            @else
                                Gratis
                            @endif
                        </h2>
                        <p class="mt-4 text-indigo-100 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Sisa stok: <span class="font-bold underline">{{ $event->stock }} Tiket lagi!</span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ url('checkout/' . $event->id) }}"
                            class="inline-block px-10 py-5 bg-white text-indigo-600 rounded-2xl font-black text-xl hover:scale-105 transition-transform shadow-xl">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white opacity-10 rounded-full"></div>
            </div>

            <!-- --- SECTION ULASAN & PENILAIAN --- -->
            <div class="mt-12 bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                <h3 class="text-xl font-bold mb-6">⭐ Ulasan & Penilaian</h3>

                @if(session('success'))
                    <div class="p-4 mb-4 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-semibold">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="p-4 mb-4 bg-rose-50 text-rose-600 rounded-xl text-sm font-semibold">{{ session('error') }}</div>
                @endif

                @auth
                    <form action="{{ route('reviews.store') }}" method="POST" class="mb-8 bg-slate-50 p-6 rounded-2xl">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        
                        <!-- KODE BARU: Bintang Interaktif yang Bisa Diklik -->
                        <div class="mb-6">
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Beri Rating</label>
                            
                            {{-- Input tersembunyi yang menyimpan angka rating (default: 5) --}}
                            <input type="hidden" name="rating" id="rating-value" value="5" required>
                            
                            {{-- Barisan Tombol Bintang --}}
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" onclick="setRating({{ $i }})" 
                                        class="star-btn text-3xl text-amber-400 hover:scale-125 transition-transform duration-150 focus:outline-none" 
                                        data-val="{{ $i }}">
                                        ★
                                    </button>
                                @endfor
                                <span id="rating-label" class="ml-3 text-sm font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">(5 Bintang)</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-bold uppercase text-slate-500 mb-2">Tulis Ulasan</label>
                            <textarea name="comment" rows="3" required placeholder="Bagaimana pengalamanmu di event ini? Ceritakan di sini..." class="w-full p-4 border rounded-xl focus:ring-2 focus:ring-indigo-600 focus:outline-none transition"></textarea>
                        </div>
                        <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition">Kirim Ulasan</button>
                    </form>

                    {{-- Script kecil untuk animasi & fungsi klik bintang --}}
                    <script>
                        function setRating(val) {
                            // Update nilai pada input hidden supaya tersimpan saat form dikirim
                            document.getElementById('rating-value').value = val;
                            document.getElementById('rating-label').innerText = `(${val} Bintang)`;

                            // Ubah warna bintang sesuai yang diklik
                            let stars = document.querySelectorAll('.star-btn');
                            stars.forEach((star, index) => {
                                if (index < val) {
                                    star.classList.remove('text-slate-300');
                                    star.classList.add('text-amber-400');
                                } else {
                                    star.classList.remove('text-amber-400');
                                    star.classList.add('text-slate-300');
                                }
                            });
                        }
                    </script>
                @else
                    <p class="mb-8 p-4 bg-slate-50 rounded-xl text-sm text-slate-600 text-center">Login untuk memberikan ulasan!</p>
                @endauth

                <div class="space-y-4">
                    @forelse(\App\Models\Review::where('event_id', $event->id)->latest()->get() as $rev)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="flex justify-between font-bold text-sm">
                                <span>{{ $rev->user->name }}</span>
                                <span class="text-amber-500">{{ str_repeat('⭐', $rev->rating) }}</span>
                            </div>
                            <p class="text-sm text-slate-600 mt-2">{{ $rev->comment }}</p>
                        </div>
                    @empty
                        <p class="text-slate-400 text-sm italic">Belum ada ulasan.</p>
                    @endforelse
                </div>
            </div>
            
        </div>
    </main>

@endsection