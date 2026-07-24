<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Amikom Event Hub - Platform Ticketing')</title>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4f46e5">
    <link rel="apple-touch-icon" href="{{ asset('icons/square-logo-dark.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('icons/rounded-logo-dark.png') }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
    <nav class="glass sticky top-4 z-50 mx-4 mt-4 px-6 py-4 rounded-2xl border border-white/20 shadow-lg">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <img src="{{ asset('icons/rounded-logo-dark.png') }}" alt="Amikom Event Hub" class="h-10">
                <span class="text-xl font-bold tracking-tight">AmikomEventHub</span>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex gap-8 font-medium items-center">
                <a href="/" class="{{ request()->is('/') ? 'text-indigo-600 font-bold' : 'hover:text-indigo-600 transition' }}">Jelajahi</a>
                <a href="/#events" class="hover:text-indigo-600 transition">Kategori</a>
                <a href="#" class="hover:text-indigo-600 transition">Tentang Kami</a>

                <!-- Tombol Login / User Profile -->
                @auth
                    <div class="flex items-center gap-4 ml-4">
                        <span class="text-sm font-bold text-slate-700">Halo, {{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-5 py-2 text-sm font-bold text-white bg-rose-600 rounded-full hover:bg-rose-700 transition">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="ml-4 px-6 py-2 text-sm font-bold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20">
                        Login
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-slate-700 p-2 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </div>

        <!-- Mobile Menu Dropdown (Clean & Attached) -->
        <div id="mobile-menu" class="hidden md:hidden pt-4 mt-4 border-t border-slate-200/60 flex-col gap-3">
            <a href="/" class="text-base font-bold {{ request()->is('/') ? 'text-indigo-600' : 'text-slate-700 hover:text-indigo-600' }}">Jelajahi</a>
            <a href="/#events" class="text-base font-bold text-slate-700 hover:text-indigo-600">Kategori</a>
            <a href="#" class="text-base font-bold text-slate-700 hover:text-indigo-600">Tentang Kami</a>
            <hr class="border-slate-100 my-1">
            @auth
                <span class="text-sm font-bold text-slate-700 block">Halo, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="block w-full">
                    @csrf
                    <button type="submit" class="w-full px-5 py-2.5 text-center text-sm font-bold text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block w-full text-center px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20">
                    Login
                </a>
            @endauth
        </div>
    </nav>

    {{-- Tempat konten dari welcome.blade.php akan muncul --}}
    @yield('content')

    <!-- Footer -->
    <footer class="bg-indigo-900 text-indigo-100 py-20 px-6 mt-20">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-5 gap-12">
            
            {{-- Kolom Branding --}}
            <div class="space-y-4 col-span-1 md:col-span-2">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('icons/rounded-logo-light.png') }}" alt="Amikom Event Hub" class="h-12">
                    <span class="text-2xl font-bold text-white">AmikomEventHub</span>
                </div>
                <p class="max-w-xs text-indigo-300 leading-relaxed">
                    Platform reservasi tiket event online terbaik untuk mahasiswa dan penyelenggara profesional.
                </p>
            </div>

            {{-- Kolom Kategori --}}
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
                    <li>support@eventtiket.com</li>
                    <li>+62 812 3456 7890</li>
                </ul>
            </div>
        </div>

        <div class="max-w-7xl mx-auto pt-12 mt-12 border-t border-indigo-800 text-center text-indigo-400 text-sm">
            &copy; 2026 AmikomEventHub. Built with Laravel & Tailwind CSS.
        </div>
    </footer>
    @stack('scripts')

    <!-- PWA Service Worker & UI Scripts -->
    <script>
        // Toggle Mobile Menu
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileBtn && mobileMenu) {
            mobileBtn.addEventListener('click', () => {
                if (mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.remove('hidden');
                    mobileMenu.classList.add('flex');
                } else {
                    mobileMenu.classList.add('hidden');
                    mobileMenu.classList.remove('flex');
                }
            });
        }

        // Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(err => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
</body>

</html>