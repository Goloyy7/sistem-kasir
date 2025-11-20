<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $totalAdmin = \App\Models\Admin::count();
        $totalKasir = \App\Models\User::count();
        return view('dashboardAdmin', compact('totalAdmin', 'totalKasir'));
    }
}
