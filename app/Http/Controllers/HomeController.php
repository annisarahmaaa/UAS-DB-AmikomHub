<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama dengan daftar event dan kategori.
     */
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk filter tab button
        $categories = Category::all();

        // 2. Buat kueri dasar untuk event (Eager loading, filter tanggal, dan urutan)
        $query = Event::with('category')
            ->where('date', '>=', now())
            ->orderBy('date', 'asc');

        // 3. Filter berdasarkan slug kategori jika ada parameter ?category=...
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // 4. Eksekusi query dan kirim ke view 'welcome'
        $events = $query->get();

        return view('welcome', compact('events', 'categories'));
    }
}