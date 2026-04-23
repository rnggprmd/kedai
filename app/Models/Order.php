<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'kode_order',
        'table_id',
        'nama_pelanggan',
        'catatan',
        'status',
        'total_harga',
        'kasir_id',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
    ];

    // === Boot: auto-generate kode_order ===

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->kode_order)) {
                $order->kode_order = 'ORD-' . now()->format('Ymd') . '-' . str_pad(
                    static::whereDate('created_at', today())->count() + 1,
                    3,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }

    // === Relationships ===

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // === Scopes ===

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    // === Helpers ===

    public function hitungTotal(): void
    {
        $this->total_harga = $this->items()->sum('subtotal');
        $this->save();
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'preparing' => 'primary',
            'ready' => 'success',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'preparing' => 'Diproses',
            'ready' => 'Siap Saji',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }
}
