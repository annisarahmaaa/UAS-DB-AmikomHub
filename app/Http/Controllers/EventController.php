<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Menampilkan semua event untuk pengunjung (Halaman Katalog)
     */
    public function index()
    {
        // Kita cuma ambil event yang tanggalnya belum lewat
        $events = Event::where('date', '>=', now())->latest()->get();
        
        return view('katalog', compact('events'));
    }

    /**
     * Menampilkan detail satu event saat diklik oleh user
     */
    public function show($id)
    {
        $event = Event::with('category')->findOrFail($id);
        
        return view('event-detail', compact('event'));
    }

    /**
     * Menuju halaman pembayaran/checkout
     */
    public function checkout($id)
    {
        $event = Event::findOrFail($id);
        
        return view('checkout', compact('event'));
    }
}