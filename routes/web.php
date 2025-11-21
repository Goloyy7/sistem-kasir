<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\KasirManagement;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;


// Authentikasi Routes
Route::middleware('admin.guest')->group(function () {

    Route::get('/admin/login', [AuthController::class, 'loginFormAdmin'])->name('loginAdmin');
    Route::post('/admin/login', [AuthController::class, 'loginAdmin']);

});

Route::middleware('admin.auth')->group(function (){

    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboardAdmin');
    Route::post('/admin/logout', [AuthController::class, 'logoutAdmin'])->name('logoutAdmin');

    // User Management Routes
    Route::resource('/admin/admin-management', AdminController::class);
    Route::resource('/admin/kasir-management', KasirManagement::class);
    Route::resource('/admin/categories', CategoryController::class);
    Route::resource('/admin/products', ProductController::class);
    Route::post('/admin/kasir-management/{id}/toggle-status', [KasirManagement::class, 'toggleStatus']);

});


