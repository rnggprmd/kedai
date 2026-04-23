<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'menu_id',
        'nama_menu',
        'harga',
        'jumlah',
        'subtotal',
        'catatan',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // === Boot: auto-calculate subtotal ===

    protected static function booted(): void
    {
        static::creating(function (OrderItem $item) {
            $item->subtotal = $item->harga * $item->jumlah;
        });

        static::updating(function (OrderItem $item) {
            $item->subtotal = $item->harga * $item->jumlah;
        });
    }

    // === Relationships ===

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
