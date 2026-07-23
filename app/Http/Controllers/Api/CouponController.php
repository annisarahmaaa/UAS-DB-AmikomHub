<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'total_price' => 'required|numeric|min:0',
            'event_id' => 'required|integer'
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))
                        ->where(function($q) use ($request) {
                            $q->whereNull('event_id')
                              ->orWhere('event_id', $request->event_id);
                        })
                        ->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Kupon tidak valid atau tidak ditemukan untuk event ini.'], 404);
        }

        if (!$coupon->is_active) {
            return response()->json(['success' => false, 'message' => 'Kupon tidak aktif.'], 400);
        }

        if ($coupon->valid_until && $coupon->valid_until->isPast()) {
            return response()->json(['success' => false, 'message' => 'Kupon telah kedaluwarsa.'], 400);
        }

        if (!is_null($coupon->usage_limit) && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json(['success' => false, 'message' => 'Kuota penggunaan kupon telah habis.'], 400);
        }

        $discount = $coupon->calculateDiscount($request->total_price);

        return response()->json([
            'success' => true,
            'message' => 'Kupon berhasil diterapkan!',
            'code' => $coupon->code,
            'discount' => $discount,
            'discount_type' => $coupon->discount_type,
            'discount_value' => $coupon->discount_value
        ]);
    }
}
