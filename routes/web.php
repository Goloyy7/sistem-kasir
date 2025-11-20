<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirManagement;

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


// User Management Routes

Route::resource('/admin/admin-management', AdminController::class);
Route::resource('/admin/kasir-management', KasirManagement::class);
Route::post('/admin/kasir-management/{id}/toggle-status', [KasirManagement::class, 'toggleStatus']);


