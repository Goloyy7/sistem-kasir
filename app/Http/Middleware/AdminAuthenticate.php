<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       // Kalau belum login
        if (! Auth::check()) {
            // Redirect ke halaman login admin
            return redirect()
                ->route('loginAdmin') // ganti kalau nama route login kamu beda
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Kalau sudah login, lanjut ke halaman yang diminta
        return $next($request);
    }
}
