<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // TAMBAHAN: Menambahkan 'organizer_id' ke dalam fillable
    protected $fillable = [
        'category_id', 'organizer_id', 'title', 'description', 'date',
        'location', 'price', 'stock', 'poster_path'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Accessor untuk mendapatkan URL lengkap gambar poster (Supabase S3 / Local / URL Direct)
     */
    public function getPosterUrlAttribute()
    {
        if (!$this->poster_path) {
            return 'https://placehold.co/400x600?text=No+Poster';
        }

        if (str_starts_with($this->poster_path, 'http://') || str_starts_with($this->poster_path, 'https://')) {
            return $this->poster_path;
        }

        $disk = config('filesystems.default', 'public');

        if ($disk === 's3' || env('FILESYSTEM_DISK') === 's3' || isset($_SERVER['VERCEL']) || getenv('VERCEL')) {
            return "https://bbzlfbrcsryqtajywivj.supabase.co/storage/v1/object/public/events/" . ltrim($this->poster_path, '/');
        }

        return asset('storage/' . $this->poster_path);
    }

    /**
     * Menandakan atribut: 1 Event harus terpaut pada satu wujud Kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * TAMBAHAN MULTI-TENANT: 1 Event dimiliki oleh 1 Organizer (User)
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Relasi ke TicketTier untuk Dynamic Pricing
     */
    public function ticketTiers()
    {
        return $this->hasMany(TicketTier::class);
    }

    /**
     * Mengambil harga aktif berdasarkan tanggal sekarang
     * Mengembalikan array ['price' => int, 'tier_name' => string|null]
     */
    public function getActivePrice()
    {
        $now = now();
        $activeTier = $this->ticketTiers()
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();

        if ($activeTier) {
            return [
                'price' => $activeTier->price,
                'tier_name' => $activeTier->name
            ];
        }

        return [
            'price' => $this->price,
            'tier_name' => 'Regular'
        ];
    }
}