<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category; // Tambahkan ini agar bisa memanggil Model Category langsung
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
     * Menampilkan detail satu event saat diklik oleh user (Sesuai instruksi soal 9.4.6)
     */
    public function show(Event $event)
    {
        // Mengambil daftar kategori untuk keperluan menu footer / navigasi
        $categories = Category::all();
        
        // Me-render view dengan membawa data kategori dan data spesifik acara tersebut
        return view('event-detail', compact('categories', 'event'));
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