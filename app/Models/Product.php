<?php

namespace App\Models;

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function details() 
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
