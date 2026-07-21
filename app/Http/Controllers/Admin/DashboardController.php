<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        $userRole = strtolower(trim($user->role ?? ''));

        // Cek apakah user adalah Superadmin atau Admin utama
        $isSuperAdmin = in_array($userRole, ['superadmin', 'admin']);

        // 1. QUERY EVENT
        $eventQuery = Event::query();
        if (!$isSuperAdmin) {
            // Jika Organizer biasa, batasi hanya event yang dibuat oleh organizer ini (menggunakan organizer_id)
            $eventQuery->where('organizer_id', $user->id); 
        }
        $events = $eventQuery->get();
        $eventIds = $events->pluck('id');

        // 2. QUERY TRANSAKSI
        $transactionQuery = Transaction::query();
        if (!$isSuperAdmin) {
            // Jika Organizer biasa, batasi hanya transaksi yang terikat dengan event milik organizer ini
            $transactionQuery->whereIn('event_id', $eventIds);
        }

        // 3. Kalkulasi data matematis real-time (disesuaikan dengan filter role)
        $totalRevenue = (clone $transactionQuery)
            ->whereIn('status', ['settlement', 'success'])
            ->sum('total_price');

        $ticketsSold = (clone $transactionQuery)
            ->whereIn('status', ['settlement', 'success'])
            ->count();

        // Untuk event aktif, jika organizer ambil dari collection $events yang sudah difilter
        $activeEvents = $isSuperAdmin 
            ? Event::where('date', '>=', now())->count()
            : $events->where('date', '>=', now())->count();

        $pendingOrders = (clone $transactionQuery)
            ->where('status', 'pending')
            ->count();

        $recentTransactions = (clone $transactionQuery)
            ->with('event')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'recentTransactions'
        ));
    }
}