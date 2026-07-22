<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Pastikan Log di-import jika belum
use Illuminate\Support\Facades\Mail; // Pastikan Mail di-import

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // Mencari ID transaksi tersebut di database lokal kita
        $transaction = Transaction::with('event')->where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 200);
        }

        // Cegah proses berulang jika status sudah lunas/sukses
        if ($transaction->status === 'settlement' || $transaction->status === 'success') {
            return response()->json(['message' => 'Already processed']);
        }

        // Logika Penerjemahan Status Midtrans API
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'challenge') {
                $transaction->status = 'challenge';
            } else if ($fraudStatus == 'accept') {
                $transaction->status = 'success';
                $this->processSuccess($transaction);
            }
        } else if ($transactionStatus == 'settlement') {
            $transaction->status = 'settlement';
            $this->processSuccess($transaction);
        } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            // Jika sebelumnya belum berstatus failed, maka kembalikan stok tiket (Release Reserved Ticket)
            if ($transaction->status !== 'failed') {
                $transaction->status = 'failed';
                if ($transaction->event) {
                    $transaction->event->increment('stock');
                }
            }
        } else if ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
        }

        $transaction->save();
        return response()->json(['message' => 'OK']);
    }

    private function processSuccess(Transaction $transaction)
    {
        // Mengirimkan email E-Ticket ke pelanggan
        try {
            Mail::to($transaction->customer_email)->send(new \App\Mail\EventTicketMail($transaction));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email E-Ticket: ' . $e->getMessage());
        }

        // Kirim E-Ticket via WhatsApp
        \App\Services\WhatsAppService::sendTicket($transaction);
    }
}