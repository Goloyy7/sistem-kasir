<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function loginFormAdmin()
    {
        return view('auth.admin-login');
    }

    public function loginAdmin(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Cari admin berdasarkan email yang diinput
        $admin = Admin::where('email', $validated['email'])->first();

        // Check Admin ada / Password salah
        if(! $admin || ! Hash::check($validated['password'], $admin->password)) {
            return back()->withErrors([
                'email' => 'Email atau password yang kamu masukkan salah.',
            ])->onlyInput('email');
        }

        // Remember me
        // Auth::login($admin, $request->boolean('remember'));

        Auth::login($admin);

        // Regenerate Session
        $request->session()->regenerate();

        // Redirect ke ke dashboard jika berhasil
        return redirect()->intended('/admin/dashboard')->with('success', 'Anda berhasil login!');

    }

    public function logoutAdmin(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login')->with('success', 'Anda berhasil logout!');
    }
}
