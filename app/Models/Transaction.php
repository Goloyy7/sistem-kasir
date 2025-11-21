<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $fillable = [
        'user_id',
        'invoice_code',
        'total_price',
        'pay_amount',
        'change_amount'
    ];

    protected $guarded = ['id'];
    
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
