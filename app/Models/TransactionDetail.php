<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    //
    protected $guarded = ['id'];

    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'subtotal',
        'price'
    ];

    
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
