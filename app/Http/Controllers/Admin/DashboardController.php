<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;        
use App\Models\Transaction; 
use App\Models\User;
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

        // 4. Kalkulasi data untuk Grafik Pertumbuhan (Chart.js)
        $currentYear = date('Y');

        // Pertumbuhan User (Semua User di platform, biasanya hanya relevan untuk Superadmin, tapi kita tampilkan saja jika diminta)
        $userGrowth = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
        
        $userGrowthData = [];
        for ($i = 1; $i <= 12; $i++) {
            $userGrowthData[] = $userGrowth[$i] ?? 0;
        }

        // Pertumbuhan Event (jika superadmin tampilkan semua, jika organizer tampilkan miliknya)
        $eventGrowthQuery = Event::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month');
            
        if (!$isSuperAdmin) {
            $eventGrowthQuery->where('organizer_id', $user->id);
        }
        
        $eventGrowth = $eventGrowthQuery->pluck('count', 'month')->toArray();
        $eventGrowthData = [];
        for ($i = 1; $i <= 12; $i++) {
            $eventGrowthData[] = $eventGrowth[$i] ?? 0;
        }

        return view('admin.dashboard', compact(
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders',
            'recentTransactions',
            'userGrowthData',
            'eventGrowthData'
        ));
    }
}