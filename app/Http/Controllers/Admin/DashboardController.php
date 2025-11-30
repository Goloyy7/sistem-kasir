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
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // Count data
        $totalAdmin = Admin::count();
        $totalKasir = User::count();
        $totalCategory = Category::count();
        $totalProduct = Product::count();
        
        // Transaction statistics
        $totalTransactionAllTime = Transaction::count();
        $totalRevenueAllTime = Transaction::sum('total_price');
        
        $transactionToday = Transaction::whereDate('created_at', today())->count();
        $revenueToday = Transaction::whereDate('created_at', today())->sum('total_price');
        
        // Last 7 days transaction data for chart
        $last7DaysData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $count = Transaction::whereDate('created_at', $date)->count();
            $revenue = Transaction::whereDate('created_at', $date)->sum('total_price');
            
            $last7DaysData[] = [
                'date' => $date->format('d M'),
                'count' => $count,
                'revenue' => $revenue,
            ];
        }
        
        // Payment method statistics
        $paymentMethodStats = Transaction::selectRaw('payment_method, COUNT(*) as total, SUM(total_price) as revenue')
            ->groupBy('payment_method')
            ->get();
        
        // Top products by transaction
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
