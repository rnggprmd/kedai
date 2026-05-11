<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'category_id',
        'nama',
        'deskripsi',
        'harga',
        'gambar',
        'is_available',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_available' => 'boolean',
        'is_active' => 'boolean',
    ];

    // === Relationships ===

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // === Scopes ===

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // === Accessors ===

    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getGambarUrlAttribute(): string
    {
        if (empty($this->gambar)) {
            // High-quality food placeholders from Unsplash
            $placeholders = [
                'photo-1546069901-ba9599a7e63c', // Salad
                'photo-1567620905732-2d1ec7bb7445', // Pancakes
                'photo-1565299624946-b28f40a0ae38', // Pizza
                'photo-1482049016688-2d3e1b311543', // Sandwich
                'photo-1484723091739-30a097e8f929', // Toast
                'photo-1473093226795-af9932fe5856', // Pasta
                'photo-1512621776951-a57141f2eefd', // Salad/Veggie
                'photo-1540189549336-e6e99c3679fe', // Meat/Steak
            ];
            $id = $this->id ?? 0;
            $photo = $placeholders[$id % count($placeholders)];
            return "https://images.unsplash.com/{$photo}?q=80&w=800&auto=format&fit=crop";
        }

        if (filter_var($this->gambar, FILTER_VALIDATE_URL)) {
            return $this->gambar;
        }

        return asset('storage/' . $this->gambar);
    }
}
