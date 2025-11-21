<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;

class CategoryController extends Controller
{
    /**
     * Convert timestamp to WIB (Waktu Indonesia Barat)
     * 
     * @param Carbon $dateTime
     * @return Carbon
     */
    private function convertToWIB($dateTime)
    {
        return $dateTime->setTimezone('Asia/Jakarta');
    }

    public function index(Request $request)
    {
        // Kode biar bisa searching
        $search = $request->get('search');

        $categories = Category::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderByRaw('COALESCE(updated_at, created_at) DESC')
            ->paginate(10);
        
        // Ngubah timezone lewat function tadi
        $categories->each(function ($category) {
            $category->created_at = $this->convertToWIB($category->created_at);
            $category->updated_at = $this->convertToWIB($category->updated_at);
        });
        
        return view('categories.index', compact('categories'));
    }   

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.string' => 'Nama kategori harus berupa teks',
            'name.max' => 'Nama kategori maksimal 255 karakter',
            'name.unique' => 'Kategori sudah terdaftar',
        ]);

        // Simpan ke database
        Category::create($validated);

        // Redirect dengan notifikasi sukses
        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'name.string' => 'Nama kategori harus berupa teks',
            'name.max' => 'Nama kategori maksimal 255 karakter',
            'name.unique' => 'Kategori sudah terdaftar',
        ]);

        // Cari kategori berdasarkan ID
        $category = Category::findOrFail($id);

        // Update data kategori
        $category->name = $validated['name'];
        $category->save();

        // Redirect dengan notifikasi sukses
        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Cari kategori berdasarkan ID
        $category = Category::findOrFail($id);

        // Hapus kategori
        $category->delete();

        // Redirect dengan notifikasi sukses
        return redirect()->route('categories.index')
                        ->with('success', 'Kategori berhasil dihapus!');
    }
}
