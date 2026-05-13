<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Menampilkan daftar partner (Read Data - Tugas 3)
     */
    public function index()
    {
        // Mengambil semua data partner menggunakan Eloquent [cite: 33]
        $partners = Partner::all();

        // Mengirim data ke view index [cite: 33]
        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Menampilkan form tambah partner (Tugas 4.1)
     */
    public function create()
    {
        return view('admin.partners.create'); // [cite: 38]
    }

    /**
     * Menyimpan data partner baru ke database (Tugas 4.3)
     */
    public function store(Request $request)
    {
        // 1. Validasi input (opsional tapi sangat disarankan)
        $request->validate([
            'name' => 'required',
            'logo_url' => 'required'
        ]);

        // 2. Simpan ke Database menggunakan Eloquent 
        Partner::create([
            'name' => $request->name,
            'logo_url' => $request->logo_url
        ]);

        // 3. Redirect kembali ke daftar utama setelah sukses (Tugas 4.4) 
        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil ditambahkan!');
    }
}