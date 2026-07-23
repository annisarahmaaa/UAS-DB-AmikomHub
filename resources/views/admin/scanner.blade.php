@extends('layouts.admin')
@section('title', 'Scanner Tiket')
@section('page_title', 'Validasi Check-in Peserta')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Bagian Scanner Kamera -->
    <div class="lg:col-span-2">
        <div class="bg-white p-8 rounded-[30px] border border-slate-100 shadow-sm relative overflow-hidden">
            <!-- Decorative background -->
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
            
            <div class="relative z-10 flex flex-col items-center">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-black text-slate-800 mb-2">Pindai QR E-Ticket</h2>
                    <p class="text-slate-500 text-sm">Arahkan kamera ke QR Code peserta untuk memvalidasi kehadiran secara otomatis.</p>
                </div>

                <div id="reader" class="w-full max-w-md bg-slate-50 rounded-2xl border-2 border-dashed border-slate-300 overflow-hidden mb-6"></div>

                <button id="resetScannerBtn" class="hidden px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition duration-200 flex items-center gap-2 mb-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Scan Ulang
                </button>

                <div class="w-full max-w-md border-t border-slate-200 pt-6 mt-2">
                    <p class="text-slate-500 text-sm font-semibold mb-3 text-center">Atau masukkan ID manual:</p>
                    <div class="flex gap-2">
                        <input type="text" id="manualOrderId" placeholder="Contoh: TRX-123456789" class="flex-1 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono text-sm">
                        <button id="manualSubmitBtn" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition shadow-md shadow-indigo-200 whitespace-nowrap">
                            Cek ID
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Log & Status -->
    <div class="lg:col-span-1">
        <div class="bg-white p-8 rounded-[30px] border border-slate-100 shadow-sm h-full">
            <h3 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Status Terakhir
            </h3>
            
            <div id="scanResult" class="flex flex-col items-center justify-center text-center p-6 bg-slate-50 rounded-2xl border border-slate-100 min-h-[250px]">
                <div class="w-16 h-16 bg-slate-200 rounded-full flex items-center justify-center text-slate-400 mb-4 animate-pulse">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <p class="text-slate-500 font-medium">Menunggu pindaian...</p>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const html5QrCode = new Html5Qrcode("reader");
        const resultContainer = document.getElementById('scanResult');
        const resetBtn = document.getElementById('resetScannerBtn');
        let isScanning = true;

        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            if (!isScanning) return;
            
            // Hentikan scan sementara saat proses AJAX
            isScanning = false;
            html5QrCode.pause();
            
            // Tampilkan loading state
            resultContainer.innerHTML = `
                <div class="w-16 h-16 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
                <p class="text-slate-600 font-bold">Memvalidasi tiket...</p>
                <p class="text-xs text-slate-400 mt-1">${decodedText}</p>
            `;

            // Kirim ke backend menggunakan relative URL untuk mencegah Mixed Content (HTTP vs HTTPS via ngrok)
            fetch('{{ route("admin.checkin", [], false) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order_id: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Sukses (Hijau)
                    resultContainer.className = "flex flex-col items-center justify-center text-center p-6 bg-green-50 rounded-2xl border border-green-100 min-h-[250px] transform transition duration-300 scale-105";
                    resultContainer.innerHTML = `
                        <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center text-white mb-4 shadow-lg shadow-green-200">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h4 class="text-green-800 font-black text-xl mb-1">Check-in Berhasil!</h4>
                        <p class="text-green-600 font-bold text-lg">${data.data.name}</p>
                        <p class="text-green-700 text-sm mt-2 opacity-80">${data.message}</p>
                    `;
                    playAudio('success');
                } else {
                    // Gagal (Merah)
                    resultContainer.className = "flex flex-col items-center justify-center text-center p-6 bg-rose-50 rounded-2xl border border-rose-100 min-h-[250px] transform transition duration-300 scale-105";
                    resultContainer.innerHTML = `
                        <div class="w-20 h-20 bg-rose-500 rounded-full flex items-center justify-center text-white mb-4 shadow-lg shadow-rose-200">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <h4 class="text-rose-800 font-black text-xl mb-1">Ditolak!</h4>
                        <p class="text-rose-600 font-bold">${data.message}</p>
                        <p class="text-xs font-mono text-rose-400 mt-3 bg-rose-100 px-2 py-1 rounded">ID: ${decodedText}</p>
                    `;
                    playAudio('error');
                }
                
                resetBtn.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                resultContainer.className = "flex flex-col items-center justify-center text-center p-6 bg-orange-50 rounded-2xl border border-orange-100 min-h-[250px]";
                resultContainer.innerHTML = `
                    <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center text-white mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <p class="text-orange-700 font-bold">Terjadi kesalahan jaringan.</p>
                `;
                resetBtn.classList.remove('hidden');
            });
        };

        const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
        
        // Start scanner
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
            .catch(err => {
                console.log(`Error starting scanner: ${err}`);
                resultContainer.innerHTML = `<p class="text-rose-500 font-bold p-4">Gagal mengakses kamera. Pastikan izin diberikan.</p>`;
            });

        // Reset Scanner
        resetBtn.addEventListener('click', () => {
            isScanning = true;
            html5QrCode.resume();
            resetBtn.classList.add('hidden');
            
            resultContainer.className = "flex flex-col items-center justify-center text-center p-6 bg-slate-50 rounded-2xl border border-slate-100 min-h-[250px] transition-all duration-300";
            resultContainer.innerHTML = `
                <div class="w-16 h-16 bg-slate-200 rounded-full flex items-center justify-center text-slate-400 mb-4 animate-pulse">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <p class="text-slate-500 font-medium">Menunggu pindaian...</p>
            `;
        });
        
        // Manual Input Submit
        document.getElementById('manualSubmitBtn').addEventListener('click', () => {
            const manualId = document.getElementById('manualOrderId').value.trim();
            if (manualId) {
                document.getElementById('manualOrderId').value = '';
                qrCodeSuccessCallback(manualId, null);
            }
        });
        
        // Simple Audio Feedback
        function playAudio(type) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gainNode = ctx.createGain();
                
                osc.connect(gainNode);
                gainNode.connect(ctx.destination);
                
                if (type === 'success') {
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(800, ctx.currentTime);
                    osc.frequency.exponentialRampToValueAtTime(1200, ctx.currentTime + 0.1);
                    gainNode.gain.setValueAtTime(0.5, ctx.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.5);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.5);
                } else {
                    osc.type = 'square';
                    osc.frequency.setValueAtTime(200, ctx.currentTime);
                    osc.frequency.exponentialRampToValueAtTime(150, ctx.currentTime + 0.2);
                    gainNode.gain.setValueAtTime(0.5, ctx.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.3);
                }
            } catch (e) {
                console.log('Audio not supported or blocked');
            }
        }
    });
</script>
@endsection
