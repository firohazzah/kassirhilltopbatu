<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Statistik hari ini
        $todayTransactions = Transaction::whereDate('created_at', $today)
            ->count();

        $todayRevenue = Transaction::whereDate('created_at', $today)
            ->where('status', 'paid')
            ->sum('total');

        $productCount = Product::count();

        // Transaksi terbaru
        $recentTransactions = Transaction::latest()
            ->take(8)
            ->get();

        // Grafik 7 hari terakhir
        $chartLabels = [];
        $chartRevenue = [];
        $chartTransactions = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $chartLabels[] = $date->format('d M');

            $chartRevenue[] = (float) Transaction::whereDate('created_at', $date)
                ->where('status', 'paid')
                ->sum('total');

            $chartTransactions[] = Transaction::whereDate('created_at', $date)
                ->count();
        }

        return view('dashboard', compact(
            'todayTransactions',
            'todayRevenue',
            'productCount',
            'recentTransactions',
            'chartLabels',
            'chartRevenue',
            'chartTransactions'
        ));
    }
}