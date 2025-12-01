<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KasirManagement extends Controller
{

    public function index(Request $request)
    {
        // Kode biar bisa searching
        $search = $request->get('search');
        $is_active = $request->get('is_active');

        $kasirs = User::query()
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->when($is_active !== null && $is_active !== '', function ($query) use ($is_active) {
                return $query->where('is_active', (int)$is_active);
            })
            ->orderByRaw('COALESCE(updated_at, created_at) DESC')
            ->paginate(10);

        return view('kasir-management.index', compact('kasirs'));
    }

    public function create()
    {
        return view('kasir-management.create');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('kasir-management.show', compact('user'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|regex:/^08[0-9]{8,11}$/|unique:users,phone_number',
            'address' => 'required|string|max:500',
            'password' => 'required|string|min:8',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'phone_number.required' => 'Nomor telepon harus diisi',
            'phone_number.regex' => 'Nomor telepon harus diawali 08 dan 10-13 digit',
            'phone_number.unique' => 'Nomor telepon sudah terdaftar',
            'address.required' => 'Alamat harus diisi',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'foto.image' => 'File harus berupa gambar',
            'foto.max' => 'Ukuran gambar maksimal 2MB',
            'is_active.required' => 'Status harus diisi',
        ]);

        // Proses upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoPath = $foto->store('kasirs', 'public');
        }

        // Buat user baru
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'password' => Hash::make($validated['password']),
            'foto' => $fotoPath,
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('kasir-management.index')->with('success', 'Kasir berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('kasir-management.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|regex:/^08[0-9]{8,11}$/|unique:users,phone_number,' . $user->id,
            'address' => 'required|string|max:500',
            'password' => 'nullable|string|min:8',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'required|boolean',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'phone_number.required' => 'Nomor telepon harus diisi',
            'phone_number.regex' => 'Nomor telepon harus diawali 08 dan 10-13 digit',
            'phone_number.unique' => 'Nomor telepon sudah terdaftar',
            'address.required' => 'Alamat harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'foto.image' => 'File harus berupa gambar',
            'foto.max' => 'Ukuran gambar maksimal 2MB',
            'is_active.required' => 'Status harus diisi',
        ]);

        // Proses upload foto jika ada file baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            // Upload foto baru
            $foto = $request->file('foto');
            $validated['foto'] = $foto->store('kasirs', 'public');
        }

        // Hash password hanya jika diubah
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Update user
        $user->update($validated);

        return redirect()->route('kasir-management.index')->with('success', 'Kasir berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Hapus foto jika ada
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        // Hapus user
        $user->delete();

        return redirect()->route('kasir-management.index')->with('success', 'Kasir berhasil dihapus!');
    }

    public function toggleStatus(Request $request, $id)
    {
        // function untuk langsung mengubah status langsung di index tanpa masuk ke edit
        $user = User::findOrFail($id);
        $user->is_active = $request->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah',
            'is_active' => $user->is_active
        ]);
    }

    
}
