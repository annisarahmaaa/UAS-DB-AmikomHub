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
        /* Custom scrollbar untuk sidebar agar lebih halus */
        aside::-webkit-scrollbar { width: 4px; }
        aside::-webkit-scrollbar-thumb { background: #4338ca; border-radius: 10px; }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 flex min-h-screen">

    <aside class="w-64 bg-indigo-900 text-indigo-100 flex flex-col p-6 sticky top-0 h-screen shadow-xl overflow-y-auto">
        <div class="flex items-center gap-3 mb-10">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl shadow-lg">AH</div>
            <span class="text-xl font-bold text-white tracking-tight text-nowrap">AmikomEventHub</span>
        </div>

        <nav class="flex-1 space-y-2">
            <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-4 px-4">Main Menu</p>
        @php
            $menus = [
                ['route' => 'admin.dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z', 'label' => 'Dashboard'],
                ['route' => 'admin.events.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Kelola Event'],
                    //Menu Manage Partners
                ['route' => 'admin.partners.index', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Kelola Partner'],
                ['route' => 'admin.transactions.index', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Laporan Transaksi'],
        
            ];
        @endphp

            @foreach($menus as $menu)
            <a href="{{ route($menu['route']) }}" 
            class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 
            {{ request()->routeIs($menu['route'] . '*') ? 'bg-indigo-800 text-white shadow-md' : 'text-indigo-300 hover:bg-indigo-800 hover:text-white' }}">
                <svg class="w-5 h-5 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}"></path>
                </svg>
                {{ $menu['label'] }}
            </a>
            @endforeach
        </nav>

        <div class="pt-6 border-t border-indigo-800/50">
            <form action="#" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-indigo-400 hover:text-rose-400 transition-colors font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 p-10 w-full bg-slate-50 min-h-screen">
        <header class="flex justify-between items-start mb-10">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">@yield('page_title', 'Dashboard')</h1>
                <p class="text-slate-500 font-medium mt-1">@yield('page_subtitle', 'Selamat datang kembali, Admin!')</p>
            </div>
            
            <div class="flex items-center gap-4 bg-white p-2 pr-4 rounded-2xl shadow-sm border border-slate-100">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" alt="Avatar">
                </div>
                <div class="hidden md:block">
                    <p class="text-sm font-bold leading-none">Administrator</p>
                    <p class="text-[11px] text-indigo-500 font-bold uppercase mt-1 tracking-wide">Super Admin</p>
                </div>
            </div>
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

</body>
</html>