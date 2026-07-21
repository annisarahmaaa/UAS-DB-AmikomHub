<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi input dari pembeli
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'rating'   => 'required|integer|min:1|max:5',
            'comment'  => 'required|string|max:500',
        ]);

        // 2. Cek supaya user tidak bisa spam review berkali-kali di 1 event
        $existingReview = Review::where('user_id', Auth::id())
                                ->where('event_id', $request->event_id)
                                ->first();

        if ($existingReview) {
            return back()->with('error', 'Kamu sudah pernah memberikan ulasan untuk acara ini!');
        }

        // 3. Simpan ulasan ke database
        Review::create([
            'user_id'  => Auth::id(),
            'event_id' => $request->event_id,
            'rating'   => $request->rating,
            'comment'  => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan & penilaianmu berhasil dikirim.');
    }
}