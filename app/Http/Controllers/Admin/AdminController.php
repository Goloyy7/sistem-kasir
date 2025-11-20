<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Convert timestamp to WIB (Waktu Indonesia Barat)
     * 
     * @param Carbon $dateTime
     * @return Carbon
     */
    private function convertToWIB($dateTime) // function buat ngubah timezone ke WIB
    {
        return $dateTime->setTimezone('Asia/Jakarta');
    }

    public function index(Request $request)
    {
        // Kode biar bisa searching
        $search = $request->get('search');

        $admins = Admin::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderByRaw('COALESCE(updated_at, created_at) DESC')
            ->paginate(10);
        
        // Ngubah timezone lewat function tadi
        $admins->each(function ($admin) {
            $admin->created_at = $this->convertToWIB($admin->created_at);
            $admin->updated_at = $this->convertToWIB($admin->updated_at);
        });
        
        return view('admin-management.index', compact('admins'));
    }   

    public function create()
    {
        return view('admin-management.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus berupa teks',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        // Simpan ke database
        Admin::create($validated);

        // Redirect dengan notifikasi sukses
        return redirect()->route('admin-management.index')
                        ->with('success', 'Admin berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin-management.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama harus diisi',
            'name.string' => 'Nama harus berupa teks',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        // Cari admin berdasarkan ID
        $admin = Admin::findOrFail($id);

        // Update data admin
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        // Jika password diisi, hash dan update
        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        // Redirect dengan notifikasi sukses
        return redirect()->route('admin-management.index')
                        ->with('success', 'Admin berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Cari admin berdasarkan ID
        $admin = Admin::findOrFail($id);

        // Cek apakah menghapus akun diri sendiri karena tidak boleh
        if(Auth::id() == $admin->id) {
            return redirect()->route('admin-management.index')
                        ->with('error', 'Admin tidak dapat menghapus dirinya sendiri!');
        }

        // Hapus admin
        $admin->delete();

        // Redirect dengan notifikasi sukses
        return redirect()->route('admin-management.index')
                        ->with('success', 'Admin berhasil dihapus!');
    }
}
