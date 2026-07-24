<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - AmikomEventHub</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        aside::-webkit-scrollbar { width: 4px; }
        aside::-webkit-scrollbar-thumb { background: #4338ca; border-radius: 10px; }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 flex min-h-screen overflow-x-hidden">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden lg:hidden"></div>

    <aside id="admin-sidebar" class="w-64 shrink-0 bg-indigo-900 text-indigo-100 flex flex-col p-6 fixed inset-y-0 left-0 z-50 transform -translate-x-full lg:relative lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-xl overflow-y-auto">
        <div class="flex items-center gap-3 mb-10">
            <img src="{{ asset('icons/rounded-logo-light.png') }}" alt="Amikom Event Hub" class="h-10">
            <span class="text-xl font-bold text-white tracking-tight text-nowrap">AmikomEventHub</span>
        </div>

        <nav class="flex-1 space-y-2">
            <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-4 px-4">Main Menu</p>
            
            <!-- 1. MENU UMUM (Bisa dilihat Organizer & Superadmin) -->
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.dashboard*') ? 'bg-indigo-800 text-white shadow-md' : 'text-indigo-300 hover:bg-indigo-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.events.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.events*') ? 'bg-indigo-800 text-white shadow-md' : 'text-indigo-300 hover:bg-indigo-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Kelola Event
            </a>

            <a href="{{ route('admin.coupons.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.coupons*') ? 'bg-indigo-800 text-white shadow-md' : 'text-indigo-300 hover:bg-indigo-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                </svg>
                Kupon Diskon
            </a>

            <a href="{{ route('admin.scanner') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.scanner*') ? 'bg-indigo-800 text-white shadow-md' : 'text-indigo-300 hover:bg-indigo-800 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
                Scanner Tiket
            </a>

            <!-- 2. MENU SENSITIF (HANYA BISA DILIHAT SUPERADMIN) -->
            @if(Auth::check() && Auth::user()->role === 'superadmin')
                <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mt-6 mb-2 px-4">System Admin</p>

                <a href="{{ route('admin.categories.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.categories*') ? 'bg-indigo-800 text-white shadow-md' : 'text-indigo-300 hover:bg-indigo-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                    Kelola Kategori
                </a>

                <a href="{{ route('admin.partners.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.partners*') ? 'bg-indigo-800 text-white shadow-md' : 'text-indigo-300 hover:bg-indigo-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Kelola Partner
                </a>

                <a href="{{ route('admin.transactions.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.transactions*') ? 'bg-indigo-800 text-white shadow-md' : 'text-indigo-300 hover:bg-indigo-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Laporan Transaksi
                </a>

                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('admin.users*') ? 'bg-indigo-800 text-white shadow-md' : 'text-indigo-300 hover:bg-indigo-800 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Kelola Pengguna
                </a>
            @endif
        </nav>

        <div class="pt-6 border-t border-indigo-800/50">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-indigo-300 hover:bg-rose-600/20 hover:text-rose-400 rounded-xl transition-all duration-200 font-semibold text-left">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-6 lg:p-10 w-full bg-slate-50 min-h-screen max-w-full">
        <header class="flex justify-between items-start mb-10 gap-4">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="lg:hidden p-2 bg-white rounded-xl shadow-sm text-slate-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <div>
                @hasSection('page_title')
                    <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">@yield('page_title')</h1>
                    @hasSection('page_subtitle')
                        <p class="text-slate-500 font-medium mt-1">@yield('page_subtitle')</p>
                    @endif
                @endif
                </div>
            </div>
            
            <!-- KOTAK PROFIL ADMIN DINAMIS -->
            @auth
            <div class="flex items-center gap-4 bg-white p-2 pr-4 rounded-2xl shadow-sm border border-slate-100">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" alt="Avatar">
                </div>
                <div class="hidden md:block">
                    <p class="text-sm font-bold leading-none text-slate-800">{{ Auth::user()->name }}</p>
                    
                    @if(Auth::user()->role === 'superadmin')
                        <p class="text-[11px] text-purple-600 font-black uppercase mt-1 tracking-wide">👑 Super Admin</p>
                    @elseif(Auth::user()->role === 'organizer')
                        <p class="text-[11px] text-amber-600 font-black uppercase mt-1 tracking-wide">🎪 Organizer Event</p>
                    @else
                        <p class="text-[11px] text-slate-500 font-black uppercase mt-1 tracking-wide">👤 Pembeli (User)</p>
                    @endif
                </div>
            </div>
            @endauth
        </header>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 animate-fade-in">
                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="content">
            @yield('content')
        </div>
    </main>

    <script>
        const sidebar = document.getElementById('admin-sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        const backdrop = document.getElementById('sidebar-backdrop');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }

        if(toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
        if(backdrop) backdrop.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>