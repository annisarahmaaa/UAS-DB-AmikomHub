<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Nantinya di sini ambil data dari database, 
        // untuk sekarang langsung return view saja sesuai instruksi
        return view('admin.categories.index');
    }
}
