<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Table;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === Users ===
        User::create([
            'name' => 'Admin Kedai',
            'email' => 'admin@kedai.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@kedai.com',
            'password' => 'password',
            'role' => 'kasir',
        ]);

        // === Tables (Meja) ===
        $mejas = [
            ['kode_meja' => 'MEJA-01', 'nama_meja' => 'Meja 1', 'kapasitas' => 4],
            ['kode_meja' => 'MEJA-02', 'nama_meja' => 'Meja 2', 'kapasitas' => 4],
            ['kode_meja' => 'MEJA-03', 'nama_meja' => 'Meja 3', 'kapasitas' => 2],
            ['kode_meja' => 'MEJA-04', 'nama_meja' => 'Meja 4', 'kapasitas' => 6],
            ['kode_meja' => 'MEJA-05', 'nama_meja' => 'Meja 5', 'kapasitas' => 4],
            ['kode_meja' => 'MEJA-06', 'nama_meja' => 'Meja 6', 'kapasitas' => 2],
            ['kode_meja' => 'MEJA-07', 'nama_meja' => 'Meja 7', 'kapasitas' => 8],
            ['kode_meja' => 'MEJA-08', 'nama_meja' => 'Meja 8', 'kapasitas' => 4],
        ];

        foreach ($mejas as $meja) {
            Table::create($meja);
        }

        // === Categories ===
        $categories = [
            ['nama' => 'Makanan', 'urutan' => 1],
            ['nama' => 'Minuman', 'urutan' => 2],
            ['nama' => 'Snack', 'urutan' => 3],
            ['nama' => 'Dessert', 'urutan' => 4],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // === Menus ===
        $menus = [
            // Makanan (category_id: 1)
            [
                'category_id' => 1, 
                'nama' => 'Nasi Goreng Spesial', 
                'deskripsi' => 'Nasi goreng dengan telur, ayam, dan sayuran segar', 
                'harga' => 25000,
                'gambar' => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?q=80&w=1000&auto=format&fit=crop'
            ],
            [
                'category_id' => 1, 
                'nama' => 'Mie Goreng', 
                'deskripsi' => 'Mie goreng dengan bumbu khas kedai', 
                'harga' => 22000,
                'gambar' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?q=80&w=1000&auto=format&fit=crop'
            ],
            [
                'category_id' => 1, 
                'nama' => 'Ayam Geprek', 
                'deskripsi' => 'Ayam crispy dengan sambal geprek level 1-5', 
                'harga' => 20000,
                'gambar' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?q=80&w=1000&auto=format&fit=crop'
            ],
            [
                'category_id' => 1, 
                'nama' => 'Ayam Bakar Madu', 
                'deskripsi' => 'Ayam bakar dengan olesan madu spesial', 
                'harga' => 28000,
                'gambar' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?q=80&w=1000&auto=format&fit=crop'
            ],
            [
                'category_id' => 1, 
                'nama' => 'Nasi Campur', 
                'deskripsi' => 'Nasi putih dengan lauk campur pilihan', 
                'harga' => 23000,
                'gambar' => 'https://images.unsplash.com/photo-1541518763669-27fef04b14ea?q=80&w=1000&auto=format&fit=crop'
            ],

            // Minuman (category_id: 2)
            [
                'category_id' => 2, 
                'nama' => 'Es Teh Manis', 
                'deskripsi' => 'Teh manis dingin segar', 
                'harga' => 5000,
                'gambar' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?q=80&w=1000&auto=format&fit=crop'
            ],
            [
                'category_id' => 2, 
                'nama' => 'Es Jeruk', 
                'deskripsi' => 'Jeruk peras segar dengan es', 
                'harga' => 8000,
                'gambar' => 'https://images.unsplash.com/photo-1613478223719-2ab802602423?q=80&w=1000&auto=format&fit=crop'
            ],
            [
                'category_id' => 2, 
                'nama' => 'Kopi Susu', 
                'deskripsi' => 'Kopi robusta dengan susu segar', 
                'harga' => 15000,
                'gambar' => 'https://images.unsplash.com/photo-1541167760496-162955ed8a9f?q=80&w=1000&auto=format&fit=crop'
            ],
            [
                'category_id' => 2, 
                'nama' => 'Jus Alpukat', 
                'deskripsi' => 'Jus alpukat segar dengan susu coklat', 
                'harga' => 18000,
                'gambar' => 'https://images.unsplash.com/photo-1589733901241-5e391270dd91?q=80&w=1000&auto=format&fit=crop'
            ],
            [
                'category_id' => 2, 
                'nama' => 'Air Mineral', 
                'deskripsi' => 'Air mineral 600ml', 
                'harga' => 4000,
                'gambar' => 'https://images.unsplash.com/photo-1548839140-29a749e1cf4d?q=80&w=1000&auto=format&fit=crop'
            ],

            // Snack (category_id: 3)
            [
                'category_id' => 3, 
                'nama' => 'Kentang Goreng', 
                'deskripsi' => 'Kentang goreng crispy dengan saus', 
                'harga' => 15000,
                'gambar' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?q=80&w=1000&auto=format&fit=crop'
            ],
            [
                'category_id' => 3, 
                'nama' => 'Tahu Crispy', 
                'deskripsi' => 'Tahu goreng tepung dengan sambal kecap', 
                'harga' => 10000,
                'gambar' => 'https://images.unsplash.com/photo-1541519227354-08fa5d50c44d?q=80&w=1000&auto=format&fit=crop'
            ],
            [
                'category_id' => 3, 
                'nama' => 'Pisang Goreng', 
                'deskripsi' => 'Pisang goreng dengan topping keju/coklat', 
                'harga' => 12000,
                'gambar' => 'https://images.unsplash.com/photo-1590595906931-81f04f0ccebb?q=80&w=1000&auto=format&fit=crop'
            ],

            // Dessert (category_id: 4)
            [
                'category_id' => 4, 
                'nama' => 'Es Krim Vanilla', 
                'deskripsi' => 'Es krim vanilla 2 scoop', 
                'harga' => 15000,
                'gambar' => 'https://images.unsplash.com/photo-1501443762994-82bd5dabb892?q=80&w=1000&auto=format&fit=crop'
            ],
            [
                'category_id' => 4, 
                'nama' => 'Pudding Coklat', 
                'deskripsi' => 'Pudding coklat lembut dengan vla', 
                'harga' => 12000,
                'gambar' => 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?q=80&w=1000&auto=format&fit=crop'
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
