@extends('layouts.app')

@section('title', 'Cara Bayar - Amikom Event Hub')

@section('content')
<section class="max-w-4xl mx-auto px-6 py-20">
    <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12">
        <h1 class="text-4xl font-extrabold text-slate-900 mb-6">Cara Membayar Tiket Event</h1>
        <p class="text-lg text-slate-600 mb-10 leading-relaxed">
            Ikuti panduan lengkap di bawah ini untuk menyelesaikan pembayaran tiket event Anda di AmikomEventHub dengan aman dan cepat melalui sistem Midtrans.
        </p>

        <div class="space-y-8">
            {{-- Langkah 1 --}}
            <div class="flex gap-6 items-start">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-xl">
                    1
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Pilih Event & Isi Data</h3>
                    <p class="text-slate-600">Pilih event yang ingin Anda ikuti, tentukan jumlah dan kategori tiket, lalu klik tombol <strong>"Beli Tiket"</strong>. Pastikan untuk mengisi data diri Anda dengan benar sebelum melanjutkan ke pembayaran.</p>
                </div>
            </div>

            {{-- Langkah 2 --}}
            <div class="flex gap-6 items-start">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-xl">
                    2
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Checkout & Konfirmasi</h3>
                    <p class="text-slate-600">Periksa kembali detail pesanan Anda pada halaman checkout. Jika sudah sesuai, klik tombol <strong>"Bayar Sekarang"</strong>. Anda akan diarahkan ke halaman pembayaran Midtrans.</p>
                </div>
            </div>

            {{-- Langkah 3 --}}
            <div class="flex gap-6 items-start">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-xl">
                    3
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Pilih Metode Pembayaran</h3>
                    <p class="text-slate-600">Pada halaman Midtrans, pilih metode pembayaran yang Anda inginkan. Kami mendukung berbagai metode seperti Transfer Bank (Virtual Account), e-Wallet (GoPay, OVO, ShopeePay), Kartu Kredit/Debit, dan pembayaran di gerai minimarket.</p>
                </div>
            </div>

            {{-- Langkah 4 --}}
            <div class="flex gap-6 items-start">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-xl">
                    4
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Selesaikan Pembayaran</h3>
                    <p class="text-slate-600">Ikuti instruksi pembayaran sesuai metode yang dipilih. Lakukan pembayaran sebelum batas waktu (expired) yang ditentukan agar tiket tidak hangus.</p>
                </div>
            </div>

            {{-- Langkah 5 --}}
            <div class="flex gap-6 items-start">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-xl">
                    5
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Terima E-Ticket</h3>
                    <p class="text-slate-600">Setelah pembayaran berhasil dikonfirmasi secara otomatis, E-Ticket Anda akan muncul di halaman tiket dan juga dikirimkan melalui email yang terdaftar. Bawa E-Ticket ini (atau tunjukkan QR Code-nya) pada saat hari H acara.</p>
                </div>
            </div>
        </div>

        <div class="mt-12 bg-indigo-50 border border-indigo-100 rounded-2xl p-6">
            <h4 class="font-bold text-indigo-900 mb-2">Butuh Bantuan?</h4>
            <p class="text-indigo-700 text-sm">Jika Anda mengalami kendala saat melakukan pembayaran, jangan ragu untuk menghubungi layanan pelanggan kami melalui halaman <a href="/kontak" class="underline font-semibold hover:text-indigo-900">Kontak Kami</a>.</p>
        </div>
    </div>
</section>
@endsection
