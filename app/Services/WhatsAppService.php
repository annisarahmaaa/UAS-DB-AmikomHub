<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Kirim E-Ticket via WhatsApp (Fonnte API)
     */
    public static function sendTicket(Transaction $transaction)
    {
        $token = env('FONNTE_TOKEN');
        if (!$token) return false;

        $target = self::formatPhoneNumber($transaction->customer_phone);
        $eventTitle = $transaction->event->title ?? 'Event';
        $date = $transaction->event ? $transaction->event->date->format('d M Y H:i') : '-';
        $location = $transaction->event->location ?? '-';
        
        // Dapatkan URL gambar poster (jika ada)
        $imageUrl = null;
        if ($transaction->event && $transaction->event->poster_path) {
            $imageUrl = asset('storage/' . $transaction->event->poster_path);
        }

        $message = "*E-TICKET RESMI AMIKOMHUB*\n\n";
        $message .= "Halo {$transaction->customer_name},\n";
        $message .= "Terima kasih telah melakukan pembelian tiket acara kami.\n\n";
        $message .= "🎫 *Detail Acara:*\n";
        $message .= "Nama: {$eventTitle}\n";
        $message .= "Waktu: {$date}\n";
        $message .= "Lokasi: {$location}\n\n";
        $message .= "📦 *Detail Pesanan:*\n";
        $ticketLink = route('checkout.ticket', $transaction->order_id);

        $message .= "Order ID: {$transaction->order_id}\n";
        $message .= "Status: LUNAS\n\n";
        $message .= "Klik tautan berikut untuk melihat E-Ticket Anda:\n";
        $message .= "🔗 " . $ticketLink . "\n\n";
        $message .= "Silakan tunjukkan E-Ticket pada tautan di atas saat memasuki venue acara. Sampai jumpa!";

        return self::sendRequest($target, $message, $imageUrl);
    }

    /**
     * Kirim Pengingat Pembayaran (Abandoned Cart)
     */
    public static function sendPaymentReminder(Transaction $transaction)
    {
        $token = env('FONNTE_TOKEN');
        if (!$token) return false;

        $target = self::formatPhoneNumber($transaction->customer_phone);
        $eventTitle = $transaction->event->title ?? 'Event';
        $paymentLink = route('checkout.payment', $transaction->order_id);

        $message = "*PENGINGAT PEMBAYARAN - AMIKOMHUB*\n\n";
        $message .= "Halo {$transaction->customer_name},\n";
        $message .= "Anda belum menyelesaikan pembayaran tiket untuk acara *{$eventTitle}*.\n\n";
        $message .= "Silakan klik link berikut untuk melanjutkan pembayaran (tersedia Midtrans):\n";
        $message .= $paymentLink . "\n\n";
        $message .= "Abaikan pesan ini jika Anda sudah membayar atau ingin membatalkan pesanan. (Stok tiket Anda masih kami amankan selama 15 menit sebelum otomatis batal).";

        return self::sendRequest($target, $message);
    }

    /**
     * Kirim request ke API Fonnte
     */
    private static function sendRequest($target, $message, $url = null)
    {
        try {
            $payload = [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Indonesia
            ];

            // Tambahkan URL gambar jika disediakan
            if ($url) {
                $payload['url'] = $url;
            }

            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', $payload);

            $result = $response->json();
            
            if (isset($result['status']) && $result['status'] === false) {
                Log::error('Fonnte API Error: ' . json_encode($result));
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('WhatsAppService Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Format nomor telepon ke format yang diterima WhatsApp
     */
    private static function formatPhoneNumber($phone)
    {
        // Hilangkan karakter selain angka
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Ubah awalan 0 menjadi 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        
        return $phone;
    }
}
