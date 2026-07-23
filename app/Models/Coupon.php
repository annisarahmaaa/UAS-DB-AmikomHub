<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['event_id', 'code', 'discount_type', 'discount_value', 'is_active', 'usage_limit', 'used_count', 'valid_until'];

    protected $casts = [
        'is_active' => 'boolean',
        'valid_until' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Check if coupon is still valid based on active status, usage limit, and expiration date
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->valid_until && $this->valid_until->isPast()) {
            return false;
        }

        if (!is_null($this->usage_limit) && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount for a given total price
     */
    public function calculateDiscount($totalPrice)
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->discount_type === 'percent') {
            return ($totalPrice * $this->discount_value) / 100;
        }

        return min($this->discount_value, $totalPrice); // Don't discount more than total
    }
}
