<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Menampilkan daftar partner & Fitur Pencarian (Soal 3)
     */
    public function index(Request $request)
    {
        $query = Partner::query();

        // Fitur Pencarian Eloquent LIKE sesuai instruksi UTS
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // Mengambil data sesuai query
        $partners = $query->get();

        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Menampilkan form tambah partner
     */
    public function create()
    {
        return view('admin.partners.create');
    }

    /**
     * Menyimpan data partner baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'logo_url' => 'required'
        ]);

        Partner::create([
            'name' => $request->name,
            'logo_url' => $request->logo_url
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit partner (Kelengkapan CRUD - Soal 2)
     */
    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    /**
     * Menyimpan perubahan data partner ke database (Kelengkapan CRUD - Soal 2)
     */
    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'name' => 'required',
            'logo_url' => 'required'
        ]);

        $partner->update([
            'name' => $request->name,
            'logo_url' => $request->logo_url
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil diupdate!');
    }

    /**
     * Menghapus data partner (Kelengkapan CRUD - Soal 2)
     */
    public function destroy(Partner $partner)
    {
        $partner->delete();
        
        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus!');
    }
}