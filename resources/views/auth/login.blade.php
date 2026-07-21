<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Efek Animasi Putar Lambat di Background Blob */
        @keyframes rotateBlob {
            0% { transform: rotate(0deg) scale(1); }
            50% { transform: rotate(180deg) scale(1.15); }
            100% { transform: rotate(360deg) scale(1); }
        }
        .animate-blob-1 { animation: rotateBlob 20s infinite linear; }
        .animate-blob-2 { animation: rotateBlob 25s infinite linear reverse; }

        /* Card Wrapper dengan Border Glow 3D */
        .glow-card-wrapper {
            position: relative;
            background: linear-gradient(135deg, rgba(67, 56, 202, 0.2), rgba(147, 51, 234, 0.2));
            padding: 2px; /* Jarak untuk memunculkan border gradasi neon */
            border-radius: 2.5rem;
        }

        .futuristic-card {
            background: rgba(255, 255, 255, 0.82);
            backdrop-blur: 24px;
            box-shadow: 
                0px 30px 60px -15px rgba(67, 56, 202, 0.15),
                inset 0px 1px 0px rgba(255, 255, 255, 0.6);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <canvas id="neuralCanvas" class="absolute inset-0 w-full h-full pointer-events-none z-0"></canvas>

    <div class="animate-blob-1 absolute top-[-25%] right-[-15%] w-[600px] h-[600px] bg-gradient-to-br from-indigo-300/25 to-purple-400/20 rounded-full filter blur-[100px] pointer-events-none z-0"></div>
    <div class="animate-blob-2 absolute bottom-[-25%] left-[-15%] w-[600px] h-[600px] bg-gradient-to-br from-purple-300/15 to-indigo-400/25 rounded-full filter blur-[120px] pointer-events-none z-0"></div>

    <div class="max-w-md w-full glow-card-wrapper z-10 transition-all duration-500 hover:scale-[1.01]">
        
        <div class="futuristic-card rounded-[2.4rem] p-8 md:p-10">
            
            <div class="text-center mb-8">
                <div class="w-14 h-14 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center text-white font-extrabold text-xl mx-auto mb-4 shadow-lg shadow-indigo-600/30 transition-all duration-300 hover:rotate-12">
                    AH
                </div>
                <h1 class="text-2xl font-black tracking-tight text-slate-950">
                    Welcome Back
                </h1>
                <p class="text-[11px] text-indigo-600 font-extrabold uppercase tracking-widest mt-2 bg-indigo-50 inline-block px-3 py-1 rounded-full border border-indigo-100">
                    AmikomEventHub
                </p>
            </div>

            @if(session('error'))
                <div class="bg-rose-50 border border-rose-100 text-rose-600 p-4 rounded-2xl mb-6 text-xs font-semibold flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
                @csrf
                
                <div class="space-y-1.5">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider pl-1">Email Address</label>
                    <input type="email" name="email" 
                        class="w-full px-5 py-3.5 bg-white/50 border border-slate-200/80 rounded-2xl text-slate-900 placeholder-slate-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 focus:bg-white outline-none transition-all duration-300 font-medium text-sm shadow-sm" 
                        placeholder="Masukkan email kamu"
                        required>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider pl-1">Password</label>
                    <input type="password" name="password" 
                        class="w-full px-5 py-3.5 bg-white/50 border border-slate-200/80 rounded-2xl text-slate-900 placeholder-slate-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 focus:bg-white outline-none transition-all duration-300 font-medium text-sm shadow-sm" 
                        placeholder="••••••••"
                        required>
                </div>

                <!-- --- BAGIAN TOMBOL LOGIN SOSIAL (GOOGLE) --- -->
                <div class="pt-2">
                    <a href="{{ route('google.login') }}" 
                       class="w-full py-3.5 px-4 bg-white/80 border border-slate-200/80 rounded-2xl text-slate-700 font-bold text-sm tracking-wide shadow-sm hover:bg-white focus:ring-4 focus:ring-indigo-500/10 outline-none transition-all duration-300 flex items-center justify-center gap-3 transform active:scale-[0.98]">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                        </svg>
                        <span>Continue with Google</span>
                    </a>
                </div>

                <div class="relative flex py-1 items-center">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-4 text-[10px] uppercase tracking-widest font-bold text-slate-400">Atau</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>
                <!-- -------------------------------------------- -->

                <button type="submit" 
                    class="w-full py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-2xl font-bold text-sm tracking-wide shadow-lg shadow-indigo-600/20 hover:from-indigo-700 hover:to-indigo-800 focus:ring-4 focus:ring-indigo-500/20 outline-none transition-all duration-300 transform active:scale-[0.98]">
                    Masuk ke Dashboard
                </button>
            </form>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('neuralCanvas');
        const ctx = canvas.getContext('2d');

        let width = canvas.width = window.innerWidth;
        let height = canvas.height = window.innerHeight;

        window.addEventListener('resize', () => {
            width = canvas.width = window.innerWidth;
            height = canvas.height = window.innerHeight;
        });

        const numParticles = 65; // Kepadatan jaring digital
        const particles = [];
        const maxDistance = 110; // Jarak maksimal antar garis penghubung

        // Melacak koordinat kursor mouse user
        const mouse = { x: null, y: null, radius: 150 };
        window.addEventListener('mousemove', (e) => {
            mouse.x = e.clientX;
            mouse.y = e.clientY;
        });
        window.addEventListener('mouseleave', () => {
            mouse.x = null;
            mouse.y = null;
        });

        // Inisialisasi data partikel
        for (let i = 0; i < numParticles; i++) {
            particles.push({
                x: Math.random() * width,
                y: Math.random() * height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                radius: Math.random() * 2 + 1.5
            });
        }

        function draw() {
            ctx.clearRect(0, 0, width, height);

            // Update & Gambar partikel
            particles.forEach(p => {
                p.x += p.vx;
                p.y += p.vy;

                // Memantul di batas layar browser
                if (p.x < 0 || p.x > width) p.vx *= -1;
                if (p.y < 0 || p.y > height) p.vy *= -1;

                // Interaksi mouse (efek magnetis halus)
                if (mouse.x !== null && mouse.y !== null) {
                    let dx = mouse.x - p.x;
                    let dy = mouse.y - p.y;
                    let dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < mouse.radius) {
                        let force = (mouse.radius - dist) / mouse.radius;
                        p.x -= dx * force * 0.02;
                        p.y -= dy * force * 0.02;
                    }
                }

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(67, 56, 202, 0.35)';
                ctx.fill();
            });

            // Membuat jaring koneksi antar partikel terdekat
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    let p1 = particles[i];
                    let p2 = particles[j];

                    let dx = p1.x - p2.x;
                    let dy = p1.y - p2.y;
                    let dist = Math.sqrt(dx * dx + dy * dy);

                    if (dist < maxDistance) {
                        // Semakin dekat jaraknya, semakin tebal garis penghubungnya
                        let alpha = (1 - dist / maxDistance) * 0.18;
                        ctx.beginPath();
                        ctx.moveTo(p1.x, p1.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.strokeStyle = `rgba(99, 102, 241, ${alpha})`;
                        ctx.lineWidth = 0.8;
                        ctx.stroke();
                    }
                }
            }

            requestAnimationFrame(draw);
        }

        draw();
    </script>
</body>
</html>