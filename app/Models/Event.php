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
}