<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
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
        $category_id = $request->get('category_id');
        $stock_filter = $request->get('stock_filter'); // 'all', 'available', 'habis'

        $products = Product::query()
            ->with('category')
            ->when($category_id, function ($query, $category_id) {
                return $query->where('category_id', $category_id);
            })
            ->when($stock_filter === 'habis', function ($query) {
                return $query->where('stock', '<=', 0);
            })
            ->when($stock_filter === 'available', function ($query) {
                return $query->where('stock', '>', 0);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('kode_barang', 'like', "%{$search}%")
                      ->orWhere('price', 'like', "%{$search}%")
                      ->orWhere('stock', 'like', "%{$search}%");
                });
            })
            ->orderByRaw('COALESCE(updated_at, created_at) DESC')
            ->paginate(10);
        
        // Ngubah timezone lewat function tadi
        $products->each(function ($product) {
            $product->created_at = $this->convertToWIB($product->created_at);
            $product->updated_at = $this->convertToWIB($product->updated_at);
        });

        // Ambil semua kategori untuk filter
        $categories = Category::all();
        
        return view('products.index', compact('products', 'categories'));
    }   

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'category_id.required' => 'Kategori harus dipilih',
            'category_id.exists' => 'Kategori tidak valid',
            'name.required' => 'Nama produk harus diisi',
            'name.string' => 'Nama produk harus berupa teks',
            'name.max' => 'Nama produk maksimal 255 karakter',
            'name.unique' => 'Nama produk sudah terdaftar',
            'description.string' => 'Deskripsi harus berupa teks',
            'price.required' => 'Harga harus diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'price.min' => 'Harga tidak boleh negatif',
            'stock.required' => 'Stok harus diisi',
            'stock.integer' => 'Stok harus berupa angka bulat',
            'stock.min' => 'Stok tidak boleh negatif',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Upload image jika ada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Simpan ke database
        $product = Product::create($validated);

        $product->kode_barang = 'PRD-' . str_pad($product->id, 5, '0', STR_PAD_LEFT);
        $product->save();

        // Redirect dengan notifikasi sukses
        return redirect()->route('products.index')
                        ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'category_id.required' => 'Kategori harus dipilih',
            'category_id.exists' => 'Kategori tidak valid',
            'name.required' => 'Nama produk harus diisi',
            'name.string' => 'Nama produk harus berupa teks',
            'name.max' => 'Nama produk maksimal 255 karakter',
            'name.unique' => 'Nama produk sudah terdaftar',
            'description.string' => 'Deskripsi harus berupa teks',
            'price.required' => 'Harga harus diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'price.min' => 'Harga tidak boleh negatif',
            'stock.required' => 'Stok harus diisi',
            'stock.integer' => 'Stok harus berupa angka bulat',
            'stock.min' => 'Stok tidak boleh negatif',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Cari produk berdasarkan ID
        $product = Product::findOrFail($id);

        // Upload image jika ada
        if ($request->hasFile('image')) {
            // Hapus image lama jika ada
            if ($product->image && Storage::exists('public/' . $product->image)) {
                Storage::delete('public/' . $product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Update data produk
        $product->update($validated);

        // Redirect dengan notifikasi sukses
        return redirect()->route('products.index')
                        ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Cari produk berdasarkan ID
        $product = Product::findOrFail($id);

        // Hapus image jika ada
        if ($product->image && Storage::exists('public/' . $product->image)) {
            Storage::delete('public/' . $product->image);
        }

        // Hapus produk
        $product->delete();

        // Redirect dengan notifikasi sukses
        return redirect()->route('products.index')
                        ->with('success', 'Produk berhasil dihapus!');
    }
}
