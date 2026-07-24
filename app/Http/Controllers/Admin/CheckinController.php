<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ECertificateMail;

class CheckinController extends Controller
{
    /**
     * Tampilkan antarmuka Scanner QR
     */
    public function index()
    {
        return view('admin.scanner');
    }

    /**
     * Proses hasil scan QR (Order ID)
     */
    public function process(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
        ]);

        $orderId = $request->order_id;
        
        // Cari transaksi berdasarkan order_id
        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan di sistem.'
            ], 404);
        }

        // Cek status tiket
        if (strtolower($transaction->status) === 'used') {
            return response()->json([
                'success' => false,
                'message' => 'Double Entry Terdeteksi! Tiket ini sudah digunakan.'
            ], 400);
        }

        if (!in_array(strtolower($transaction->status), ['success', 'settlement'])) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Check-in! Status tiket saat ini: ' . $transaction->status
            ], 400);
        }

        // Ubah status menjadi used
        $transaction->update(['status' => 'used']);

        // Kirim E-Certificate ke email peserta secara langsung (synchronous) untuk kompatibilitas Vercel (tanpa antrean)
        try {
            Mail::to($transaction->customer_email)->send(new ECertificateMail($transaction));
        } catch (\Exception $e) {
            // Log error namun tetap lanjutkan check-in
            \Illuminate\Support\Facades\Log::error('Gagal mengirim E-Certificate: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil! E-Certificate telah dikirim ke ' . $transaction->customer_email,
            'data' => [
                'name' => $transaction->customer_name,
                'event' => $transaction->event->title,
            ]
        ]);
    }
}
