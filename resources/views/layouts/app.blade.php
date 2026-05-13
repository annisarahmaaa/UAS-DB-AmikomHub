<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AmikomEventHub - Temukan Event Seru!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900">

    <!-- Navigation -->
    <nav
        class="glass sticky top-8 z-40 mx-4 mt-4 px-6 py-4 rounded-2xl border border-white/20 shadow-lg flex justify-between items-center">
        <div class="flex items-center gap-2">
            <div
                class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                AH</div>
            <span class="text-xl font-bold tracking-tight">AmikomEventHub</span>
        </div>
        <div class="hidden md:flex gap-8 font-medium">
            <a href="/" class="{{ request()->is('/') ? 'text-indigo-600' : 'hover:text-indigo-600 transition' }}">Jelajahi</a>
            <a href="/#events" class="hover:text-indigo-600 transition">Kategori</a>
            <a href="#" class="hover:text-indigo-600 transition">Tentang Kami</a>
        </div>
    </nav>

    {{-- Tempat konten dari welcome.blade.php akan muncul --}}
    @yield('content')

    <!-- Footer -->
    <footer class="bg-indigo-900 text-indigo-100 py-20 px-6 mt-20">
        {{-- Grid diubah: col-span-2 untuk deskripsi, lalu 3 kolom navigasi di sampingnya --}}
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-5 gap-12">
            
            {{-- Kolom Branding (Lebih Luas) --}}
            <div class="space-y-4 col-span-1 md:col-span-2">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl">
                        AH
                    </div>
                    <span class="text-2xl font-bold text-white">AmikomEventHub</span>
                </div>
                <p class="max-w-xs text-indigo-300 leading-relaxed">
                    Platform reservasi tiket event online terbaik untuk mahasiswa dan penyelenggara profesional.
                </p>
            </div>

                        {{-- Kolom Kategori (BARU & DINAMIS) --}}
            <div>
                <h4 class="text-white font-bold mb-6">Kategori</h4>
                <ul class="space-y-4">
                    @isset($categories)
                        @foreach($categories->take(4) as $cat)
                            <li>
                                <a href="/?category={{ $cat->slug }}#events" class="text-indigo-300 hover:text-white transition">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    @else
                        {{-- Fallback jika data belum ada di DB --}}
                        <li><a href="#" class="text-indigo-300 hover:text-white transition">Musik</a></li>
                        <li><a href="#" class="text-indigo-300 hover:text-white transition">Workshop</a></li>
                    @endisset
                </ul>
            </div>

            {{-- Kolom Navigasi --}}
            <div>
                <h4 class="text-white font-bold mb-6">Navigasi</h4>
                <ul class="space-y-4">
                    <li><a href="/" class="hover:text-white transition">Home</a></li>
                    <li><a href="/#events" class="hover:text-white transition">Semua Event</a></li>
                    <li><a href="#" class="hover:text-white transition">Cara Bayar</a></li>
                </ul>
            </div>



            {{-- Kolom Hubungi Kami --}}
            <div>
                <h4 class="text-white font-bold mb-6">Hubungi Kami</h4>
                <ul class="space-y-4 text-indigo-300">
                    <li class="flex items-center gap-2">
                        <span>support@eventtiket.com</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span>+62 812 3456 7890</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto pt-12 mt-12 border-t border-indigo-800 text-center text-indigo-400 text-sm">
            &copy; 2024 AmikomEventHub. Built with Laravel & Tailwind CSS.
        </div>
    </footer>

</body>

</html>