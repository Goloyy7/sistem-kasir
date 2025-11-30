<?php

namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'kode_barang',
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image'
    ];

    protected static function booted()
    {
        // Event untuk generate kode_barang otomatis setelah produk dibuat
       static::created(function (Product $product) {
           if (empty($product->kode_barang)) {
               $product->kode_barang = 'PRD-' . str_pad($product->id, 5, '0', STR_PAD_LEFT);

               // Simpan tanpa memicu event lagi
               $product->saveQuietly();
           }
       });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function details() 
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
