<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'metode',
        'jumlah_bayar',
        'jumlah_kembali',
        'status',
        'kasir_id',
        'paid_at',
    ];

    protected $casts = [
        'jumlah_bayar' => 'decimal:2',
        'jumlah_kembali' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // === Relationships ===

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
}
