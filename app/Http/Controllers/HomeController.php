<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Partner; // 1. Wajib tambahkan ini untuk memanggil model Partner
use App\Models\Team;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama dengan daftar event, kategori, dan partner.
     */
    public function index(Request $request)
    {
        // Ambil semua kategori untuk filter tab button
        $categories = Category::all();

        // Ambil semua data partner untuk ditampilkan di bagian bawah (Soal 4)
        $partners = Partner::all(); // 2. Tambahkan kueri ini

        // Buat kueri dasar untuk event (Eager loading, filter tanggal, dan urutan)
        $query = Event::with('category')
            ->where('date', '>=', now())
            ->orderBy('date', 'asc');

        // Filter berdasarkan slug kategori jika ada parameter ?category=...
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Eksekusi query
        $events = $query->get();

        // Ambil semua data tim
        $teams = Team::all();

        // 3. Tambahkan 'partners' dan 'teams' ke dalam compact agar terkirim ke view
        return view('welcome', compact('events', 'categories', 'partners', 'teams'));
    }
}