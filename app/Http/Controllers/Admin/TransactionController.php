<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Inisialisasi query dengan eager loading relasi 'event' untuk optimasi database
        $query = Transaction::with('event');

        // 2. Fitur Pencarian dengan pencakupan query (Query Scoping) agar aman
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            
            $query->where(function ($q) use ($searchTerm) {
                $q->where('order_id', 'like', '%' . $searchTerm . '%')
                  ->orWhere('customer_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('customer_email', 'like', '%' . $searchTerm . '%');
            });
        }

        // 3. Ambil data terbaru dengan paginasi 10 data
        $transactions = $query->latest()->paginate(10);

        // 4. Pastikan path mengarah ke file index di dalam folder admin/transactions/
        return view('admin.transactions.index', compact('transactions'));
    }
}