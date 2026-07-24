<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;
use App\Services\WhatsAppService;

class CheckAbandonedCart implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $transaction;

    /**
     * Create a new job instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Reload data dari database untuk mendapatkan status terbaru
        $this->transaction->refresh();

        // Jika status masih pending (belum dibayar), kirim pesan abandoned cart & email pengingat
        if (strtolower($this->transaction->status) === 'pending') {
            WhatsAppService::sendPaymentReminder($this->transaction);
            try {
                \Illuminate\Support\Facades\Mail::to($this->transaction->customer_email)
                    ->send(new \App\Mail\PaymentReminderMail($this->transaction));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal mengirim Payment Reminder email (Job): ' . $e->getMessage());
            }
        }
    }
}
