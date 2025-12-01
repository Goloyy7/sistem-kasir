<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // Hitung Total Data
        $totalAdmin = Admin::count();
        $totalKasir = User::count();
        $totalCategory = Category::count();
        $totalProduct = Product::count();
        
        // Statistik Transaksi dan Pendapatan
        $totalTransactionAllTime = Transaction::count();
        $totalRevenueAllTime = Transaction::sum('total_price');
        
        $transactionToday = Transaction::whereDate('created_at', today())->count();
        $revenueToday = Transaction::whereDate('created_at', today())->sum('total_price');
        
        // Data transaksi dan pendapatan 7 hari terakhir
        $last7DaysData = [];
        for ($i = 6; $i >= 0; $i--) { // $i = 6, jika $i = 0 atau lebih dari 0 maka dikurangi 1 (hari)
            $date = Carbon::now()->subDays($i);
            $count = Transaction::whereDate('created_at', $date)->count();
            $revenue = Transaction::whereDate('created_at', $date)->sum('total_price');
            
            $last7DaysData[] = [
                'date' => $date->format('d M'),
                'count' => $count,
                'revenue' => $revenue,
            ];
        }
        
        // Statistik Payment method
        $paymentMethodStats = Transaction::selectRaw('payment_method, COUNT(*) as total, SUM(total_price) as revenue') // Memakai selectRaw karena saya butuh mengambil kolom biasa sekaligus kolom hasil perhitungan seperti COUNT(*) dan SUM(...) dalam satu query, Kalau select() biasanya dipakai untuk ambil kolom apa adanya.
            ->groupBy('payment_method')
            ->get();
        
        // Best selling products
        $topProducts = DB::table('transaction_details')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->selectRaw('products.id, products.name, COUNT(*) as qty_sold, SUM(transaction_details.subtotal) as revenue')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('qty_sold')
            ->limit(5)
            ->get();
        
        // Recent transactions
        $recentTransactions = Transaction::with('user')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        
        return view('dashboardAdmin', compact(
            'totalAdmin',
            'totalKasir',
            'totalCategory',
            'totalProduct',
            'totalTransactionAllTime',
            'totalRevenueAllTime',
            'transactionToday',
            'revenueToday',
            'last7DaysData',
            'paymentMethodStats',
            'topProducts',
            'recentTransactions'
        ));
    }
}
