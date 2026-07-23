<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\TicketTier;
use Carbon\Carbon;

class PricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat Kupon Diskon
        Coupon::create([
            'code' => 'MAHASISWA50',
            'discount_type' => 'percent',
            'discount_value' => 50,
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'HEMAT10K',
            'discount_type' => 'fixed',
            'discount_value' => 10000,
            'is_active' => true,
        ]);

        // 2. Buat Dynamic Pricing untuk Event pertama (jika ada)
        $event = Event::first();
        
        if ($event) {
            // Early Bird (1 hari lalu sampai 2 hari lagi) - Harga Lebih Murah
            TicketTier::create([
                'event_id' => $event->id,
                'name' => 'Early Bird',
                'price' => $event->price * 0.5, // 50% dari harga asli
                'start_date' => Carbon::now()->subDays(1),
                'end_date' => Carbon::now()->addDays(2),
            ]);

            // Presale 1 (3 hari lagi sampai 5 hari lagi) - Harga Sedang
            TicketTier::create([
                'event_id' => $event->id,
                'name' => 'Presale 1',
                'price' => $event->price * 0.75, // 75% dari harga asli
                'start_date' => Carbon::now()->addDays(2)->addMinute(),
                'end_date' => Carbon::now()->addDays(5),
            ]);
        }
    }
}
