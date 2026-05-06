<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Fitur Pencarian Real-time
        $query = Transaction::query();

        if ($request->has('search')) {
            $query->where('order_id', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_email', 'like', '%' . $request->search . '%');
        }

        // Ambil data terbaru dengan paginasi 10 data
        $transactions = $query->latest()->paginate(10);

        return view('admin.transactions', compact('transactions'));
    }
}