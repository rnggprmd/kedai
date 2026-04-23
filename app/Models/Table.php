<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Table extends Model
{
    protected $fillable = [
        'kode_meja',
        'nama_meja',
        'kapasitas',
        'qr_token',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot: auto-generate qr_token saat create.
     */
    protected static function booted(): void
    {
        static::creating(function (Table $table) {
            if (empty($table->qr_token)) {
                $table->qr_token = Str::uuid()->toString();
            }
        });
    }

    // === Relationships ===

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
