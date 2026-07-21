<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'rating',
        'comment',
    ];

    // Relasi: 1 Ulasan milik 1 User (Pembeli)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: 1 Ulasan milik 1 Event (Acara)
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}