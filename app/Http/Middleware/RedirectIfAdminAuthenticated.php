<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdminAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kalau user sudah login
        if (Auth::check()) {
            // Redirect ke dashboard admin (atau route yang kamu mau)
            return redirect()->route('dashboardAdmin')
            ->with('error', 'Silakan logout terlebih dahulu.'); // ganti sesuai nama route dashboard kamu
        }

        // Kalau belum login, boleh akses halaman login
        return $next($request);
    }
}
