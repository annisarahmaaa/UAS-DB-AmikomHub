<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Diimport untuk membuat slug otomatis

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori & Fitur Pencarian (Soal 3)
     */
    public function index(Request $request)
    {
        $query = Category::query();

        // Fitur Pencarian Eloquent LIKE sesuai instruksi UTS Soal 3
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // Mengambil data kategori sesuai query pencarian
        $categories = $query->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Menampilkan form tambah kategori (Soal 1)
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Menyimpan data kategori baru ke database (Soal 1)
     */
    public function store(Request $request)
    {
        // Validasi input nama kategori
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Menyimpan data ke database
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Otomatis generate slug dari nama
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Menampilkan form / modal edit kategori (Soal 1)
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Menyimpan perubahan data kategori (Soal 1)
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Mengupdate nama dan memperbarui slug-nya
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diupdate!');
    }

    /**
     * Menghapus data kategori (Soal 1)
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}