<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KasirManagement;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TransactionController;

// Route Landing Page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Authentikasi Routes
Route::middleware('admin.guest')->group(function ()
{
    Route::get('/admin/login', [AuthController::class, 'loginFormAdmin'])->name('loginAdmin');
    Route::post('/admin/login', [AuthController::class, 'loginAdmin']);
});

Route::middleware('admin.auth')->group(function ()
{
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboardAdmin');
    Route::post('/admin/logout', [AuthController::class, 'logoutAdmin'])->name('logoutAdmin');

    // User Management Routes
    Route::resource('/admin/admin-management', AdminController::class);
    Route::resource('/admin/kasir-management', KasirManagement::class);

    // Product & Category Management Routes
    Route::resource('/admin/categories', CategoryController::class);
    Route::resource('/admin/products', ProductController::class);
    Route::post('/admin/kasir-management/{id}/toggle-status', [KasirManagement::class, 'toggleStatus']);

    // Transaction Management Routes
    Route::get('/admin/transactions', [TransactionController::class, 'adminIndex'])
        ->name('admin.transactions.index');

    Route::get('/admin/transactions/{invoiceCode}/pdf', [TransactionController::class, 'adminStrukPdf'])
        ->name('admin.transactions.pdf');

});

// ------------------------------------------------------------------
// Route Kasir (Frontend Kasir)
// ------------------------------------------------------------------

Route::middleware('user.guest')->group(function () 
{
    Route::get('/kasir/login', [AuthController::class, 'loginFormKasir'])->name('loginKasir');
    Route::post('/kasir/login', [AuthController::class, 'loginKasir']);
});

Route::middleware(['user.auth', 'user.is_active'])->prefix('kasir')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logoutKasir'])->name('logoutKasir');

    // Halaman POS
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('kasir.transaksi.index');

    Route::post('/transaksi/add', [TransactionController::class, 'addToCart'])->name('kasir.transaksi.add');

    Route::post('/transaksi/update/{productId}', [TransactionController::class, 'updateCart'])->name('kasir.transaksi.update');

    Route::post('/transaksi/remove/{productId}', [TransactionController::class, 'removeFromCart'])->name('kasir.transaksi.remove');

    Route::post('/transaksi/clear', [TransactionController::class, 'clearCart'])->name('kasir.transaksi.clear');

    Route::post('/transaksi/checkout', [TransactionController::class, 'checkout'])->name('kasir.transaksi.checkout');

    Route::get('/transaksi/struk/{invoiceCode}', [TransactionController::class, 'struk'])->name('kasir.transaksi.struk');

    Route::get('/transaksi/struk/{invoiceCode}/pdf', [TransactionController::class, 'strukPdf'])->name('kasir.transaksi.struk.pdf');

    // Debug Clear Cart Transaksi
    Route::get('/debug/clear-cart', function () {
    session()->forget('cart');
    return 'Cart cleared';
    });
});






