@extends('layouts.app')

@section('title', 'Checkout - ' . $event->title)

@section('content')
@php
    $activePriceData = $event->getActivePrice();
    $activePrice = $activePriceData['price'];
    $tierName = $activePriceData['tier_name'];
    $adminFee = ($activePrice > 0) ? 5000 : 0;
@endphp
<main class="max-w-3xl mx-auto px-6 py-20">
    <div class="mb-12">
        <a href="{{ route('events.show', $event->id) }}" class="text-indigo-600 font-bold flex items-center gap-2 mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Event
        </a>
        <h1 class="text-4xl font-extrabold">Checkout</h1>
        <p class="text-slate-500 mt-2">Lengkapi data Anda untuk mendapatkan tiket.</p>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl font-bold">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8">
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <h3 class="text-xl font-bold mb-6 border-b pb-4">Pesanan Anda</h3>
            <div class="flex gap-6 items-start">
                <img src="{{ $event->poster_url }}"
                     alt="Event" class="w-24 h-24 rounded-2xl object-cover">
                <div>
                    <h4 class="font-extrabold text-lg">{{ $event->title }}</h4>
                    <p class="text-slate-500">{{ $event->date->format('d M Y') }} • {{ $event->location }}</p>
                    <div class="mt-2 flex items-center gap-2">
                        @if($tierName !== 'Regular')
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-lg uppercase tracking-wide">{{ $tierName }}</span>
                        @endif
                        <p class="text-indigo-600 font-bold">1 x Rp {{ number_format($activePrice, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t space-y-3">
                <div class="flex justify-between text-slate-500">
                    <span>Harga Tiket <span class="text-xs text-slate-400">({{ $tierName }})</span></span>
                    <span>Rp {{ number_format($activePrice, 0, ',', '.') }}</span>
                </div>
                
                <!-- Section Kupon -->
                <div class="py-4 my-2 border-y border-dashed">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Punya Kode Promo/Voucher?</label>
                    <div class="flex gap-2">
                        <input type="text" id="couponInput" placeholder="Masukkan kode kupon..." class="flex-1 px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-medium uppercase">
                        <button type="button" id="applyCouponBtn" class="px-6 py-2 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-700 transition">Terapkan</button>
                    </div>
                    <p id="couponMessage" class="text-sm font-semibold mt-2 hidden"></p>
                </div>
                <!-- End Section Kupon -->

                <div class="flex justify-between text-emerald-600 font-bold hidden" id="discountRow">
                    <span>Diskon (<span id="discountLabel"></span>)</span>
                    <span>- Rp <span id="discountAmount">0</span></span>
                </div>

                <div class="flex justify-between text-slate-500">
                    <span>Biaya Layanan</span>
                    <span>Rp {{ number_format($adminFee, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-2xl font-black mt-4 pt-4 border-t">
                    <span>Total Bayar</span>
                    <span class="text-indigo-600">Rp <span id="totalPayLabel">{{ number_format($activePrice + $adminFee, 0, ',', '.') }}</span></span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <h3 class="text-xl font-bold mb-6 italic text-indigo-600 underline underline-offset-8">📦 Data Pemesan (Tanpa Login)</h3>
            
            <form action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-6">
                @csrf
                <!-- Input hidden untuk mengirim kupon ke backend -->
                <input type="hidden" name="applied_coupon" id="appliedCouponInput" value="">
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" name="customer_name" placeholder="Masukkan nama sesuai identitas"
                           class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                           required value="{{ old('customer_name') }}">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Email Aktif</label>
                        <input type="email" name="customer_email" placeholder="contoh@gmail.com"
                               class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                               required value="{{ old('customer_email') }}">
                        <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-tighter">*E-Ticket akan dikirim ke email ini</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">No. WhatsApp</label>
                        <input type="tel" name="customer_phone" placeholder="08xxxxxxx"
                               class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                               required value="{{ old('customer_phone') }}">
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">
                    Lanjut Pembayaran
                </button>
                <p class="text-center text-xs text-slate-400">Dengan menekan tombol di atas, Anda menyetujui Syarat & Ketentuan kami.</p>
            </form>
        </div>
    </div>
</main>

<script>
    const activePrice = {{ $activePrice }};
    const adminFee = {{ $adminFee }};
    
    document.getElementById('applyCouponBtn').addEventListener('click', async function() {
        const code = document.getElementById('couponInput').value.trim();
        const msgEl = document.getElementById('couponMessage');
        const applyBtn = this;
        
        if(!code) return;
        
        applyBtn.innerText = 'Mengecek...';
        applyBtn.disabled = true;
        msgEl.classList.add('hidden');
        
        try {
            const response = await fetch(`/api/coupons/validate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    code: code,
                    total_price: activePrice,
                    event_id: {{ $event->id }}
                })
            });
            
            const data = await response.json();
            
            if(data.success) {
                // Berhasil
                msgEl.innerText = data.message;
                msgEl.className = "text-sm font-semibold mt-2 text-emerald-600";
                msgEl.classList.remove('hidden');
                
                // Update UI Total
                const discount = data.discount;
                document.getElementById('discountRow').classList.remove('hidden');
                document.getElementById('discountLabel').innerText = data.code;
                document.getElementById('discountAmount').innerText = discount.toLocaleString('id-ID');
                
                const finalTotal = activePrice - discount + adminFee;
                document.getElementById('totalPayLabel').innerText = finalTotal.toLocaleString('id-ID');
                
                // Set hidden input form
                document.getElementById('appliedCouponInput').value = data.code;
            } else {
                // Gagal
                msgEl.innerText = data.message || "Kupon tidak valid.";
                msgEl.className = "text-sm font-semibold mt-2 text-rose-600";
                msgEl.classList.remove('hidden');
                
                // Reset UI
                document.getElementById('discountRow').classList.add('hidden');
                const finalTotal = activePrice + adminFee;
                document.getElementById('totalPayLabel').innerText = finalTotal.toLocaleString('id-ID');
                document.getElementById('appliedCouponInput').value = "";
            }
            
        } catch (error) {
            console.error(error);
            msgEl.innerText = "Terjadi kesalahan saat memvalidasi kupon.";
            msgEl.className = "text-sm font-semibold mt-2 text-rose-600";
            msgEl.classList.remove('hidden');
        } finally {
            applyBtn.innerText = 'Terapkan';
            applyBtn.disabled = false;
        }
    });
</script>
@endsection