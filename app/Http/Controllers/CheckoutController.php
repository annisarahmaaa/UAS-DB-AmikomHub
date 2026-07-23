<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    public function create(Event $event): View
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = Category::all();

        return view('checkout.create', compact('event', 'categories'));
    }

    public function store(Request $request, Event $event): RedirectResponse
    {
        // 1. Validasi Input Kredensial Pelanggan
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // 2. TAHAN STOK TIKET (Reserved Ticket) - Mencegah Race Condition
        $updated = Event::where('id', $event->id)->where('stock', '>', 0)->decrement('stock');
        if (!$updated) {
            return back()->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis atau sedang dipesan orang lain.');
        }

        // 3. Generate Kode TRX (Unik)
        $orderId = 'TRX-' . time() . '-' . Str::random(5);
        
        // --- DYNAMIC PRICING & COUPON LOGIC ---
        $activePriceData = $event->getActivePrice();
        $basePrice = $activePriceData['price'];
        $tierName = $activePriceData['tier_name'] !== 'Regular' ? $activePriceData['tier_name'] : null;
        
        $discountAmount = 0;
        $appliedCoupon = null;

        if ($request->filled('applied_coupon')) {
            $coupon = Coupon::where('code', strtoupper($request->applied_coupon))
                            ->where(function($q) use ($event) {
                                $q->whereNull('event_id')
                                  ->orWhere('event_id', $event->id);
                            })
                            ->first();
            
            if ($coupon && $coupon->isValid()) {
                $discountAmount = $coupon->calculateDiscount($basePrice);
                $appliedCoupon = $coupon->code;
                
                // Tambah usage count
                $coupon->increment('used_count');
            }
        }

        $adminFee = 5000;
        $totalPrice = $basePrice - $discountAmount + $adminFee;
        // --- END DYNAMIC PRICING ---

        // 4. Merekam Transaksi ke Database
        $transaction = Transaction::create([
            'event_id' => $event->id,
            'order_id' => $orderId,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price' => $totalPrice,
            'status' => 'Pending', // Status Awal
            'coupon_code' => $appliedCoupon,
            'discount_amount' => $discountAmount,
            'ticket_tier_name' => $tierName,
        ]);

        // 5. Dispatch Job untuk Mengirim Link Pembayaran via WhatsApp (Instan)
        \App\Jobs\CheckAbandonedCart::dispatch($transaction);

        // --- INTEGRASI SNAP MIDTRANS ---
        
        // Konfigurasi Kredensial Environment Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false; // Mode Sandbox!
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Susun Paket Array Data Transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
            ],
            'custom_expiry' => [
                'order_time' => date('Y-m-d H:i:s O'),
                'expiry_duration' => 15,
                'unit' => 'minute'
            ],
        ];

        try {
            // Perintah Tembak Generate Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Update rekaman kita bahwa transaksi terkait sudah memiliki id token pelunasan
            $transaction->update(['snap_token' => $snapToken]);

            // Redirect ke halaman antarmuka pembayaran final pelanggan
            return redirect()->route('checkout.payment', $transaction->order_id);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran jaringan: ' . $e->getMessage());
        }
    }

    public function payment($order_id): View
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = Category::all();

        // Cari data transaksi berdasarkan order_id, sekaligus load relasi 'event' sesuai gambar modul
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        // Mengembalikan view checkout.payment sambil membawa data transaksi dan kategori
        return view('checkout.payment', compact('transaction', 'categories'));
    }

    // --- METHOD DIPERBARUI: HALAMAN SUKSES DENGAN FALLBACK CHECK ---
    public function success($order_id)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = Category::all();

        // PENTING: Tambahkan ->with('event') agar relasi event terbawa untuk pengurangan stok
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        // Konfigurasi Midtrans untuk mengecek status transaksi langsung ke API
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            // Mengecek status pesanan secara mandiri (Bypass)
            $status = \Midtrans\Transaction::status($order_id);

            if ($status) {
                // Mengambil nilai status transaksi
                $trx_status = is_array($status) ? ($status['transaction_status'] ?? '') : ($status->transaction_status ?? '');

                // Jika API Midtrans mengonfirmasi bahwa transaksi telah berhasil (settlement / capture)
                if (in_array($trx_status, ['settlement', 'capture'])) {
                    
                    // Hanya lakukan update jika status di database lokal masih 'pending' (indikasi Webhook tidak masuk)
                    if (strtolower($transaction->status) === 'pending' || strtolower($transaction->status) === 'challenge') {
                        $transaction->update(['status' => 'success']);

                        // Kirim Email secara manual
                        try {
                            \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                                ->send(new \App\Mail\EventTicketMail($transaction));
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Gagal mengirim email E-Ticket secara manual (Bypass): ' . $e->getMessage());
                        }

                        // Kirim E-Ticket via WhatsApp
                        \App\Services\WhatsAppService::sendTicket($transaction);
                    }
                }
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Midtrans API Error: ' . $e->getMessage());
            // Jika terjadi error dari API Midtrans (transaksi tidak valid), kembalikan ke beranda
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran: ' . $e->getMessage());
        }

        return view('checkout.success', compact('transaction', 'categories'));
    }

    public function ticket($order_id)
    {
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        return view('emails.ticket', compact('transaction'));
    }
}