<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;       
use App\Models\Transaction; 
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan kalkulasi data matematis real-time.
     */
    public function index(): View
    {
        // 1. Menjumlahkan semua nominal total_price dari transaksi yang sudah Lunas
        $totalRevenue = Transaction::whereIn('status', ['settlement', 'success'])
            ->sum('total_price');

        // 2. Menghitung berapa orang tamu yang tiketnya sudah Lunas
        $ticketsSold = Transaction::whereIn('status', ['settlement', 'success'])
            ->count();

        // 3. Menghitung jumlah Acara Mendatang yang aktif diselenggarakan
        $activeEvents = Event::where('date', '>=', now())
            ->count();

        // 4. Menghitung Transaksi Ngadat (Status belum dibayar pelanggan / Pending)
        $pendingOrders = Transaction::where('status', 'pending')
            ->count();

        // 5. Menyertakan 5 daftar riwayat pesanan (History) paling mutakhir di panel
        $recentTransactions = Transaction::with('event')
            ->latest()
            ->take(5)
            ->get();

        // 6. Mengirimkan semua variabel ke komponen view dashboard admin
        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'recentTransactions'
        ));
    }
}