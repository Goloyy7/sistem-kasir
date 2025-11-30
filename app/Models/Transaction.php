<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    //
    protected $fillable = [
        'user_id',
        'invoice_code',
        'total_price',
        'pay_amount',
        'change_amount',
        'payment_method',
    ];
    
    protected static function booted()
    {
        // Event ini jalan otomatis sebelum data "Transaction" disimpan (creating)
        static::creating(function ($transaction) {
            
            // Kalau invoice_code belum diisi, kita buat otomatis
            if (!$transaction->invoice_code) {
                $prefix = 'INV-';                      
                $date   = now()->format('Ymd');        
                $random = strtoupper(Str::random(4)); 
                
                // Hasil akhirnya: INV-20251128-AB3F
                $transaction->invoice_code = $prefix . $date . '-' . $random;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details() 
    {
        return $this->hasMany(TransactionDetail::class);
    }

}
