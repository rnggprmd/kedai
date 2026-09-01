<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === 1. Users untuk Login ===
        User::firstOrCreate(
            ['email' => 'admin@kedai.com'],
            [
                'name' => 'Admin Kedai',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasir@kedai.com'],
            [
                'name' => 'Kasir 1',
                'password' => bcrypt('password'),
                'role' => 'kasir',
                'is_active' => true,
            ]
        );

        // === 2. Master Denah Meja ===
        $tables = [
            ['kode_meja' => 'M01', 'nama_meja' => 'Meja 01', 'kapasitas' => 4],
            ['kode_meja' => 'M02', 'nama_meja' => 'Meja 02', 'kapasitas' => 4],
            ['kode_meja' => 'M03', 'nama_meja' => 'Meja 03', 'kapasitas' => 2],
            ['kode_meja' => 'M04', 'nama_meja' => 'Meja 04', 'kapasitas' => 6],
            ['kode_meja' => 'VIP-1', 'nama_meja' => 'Meja VIP 1', 'kapasitas' => 8],
        ];

        foreach ($tables as $tbl) {
            Table::firstOrCreate(
                ['kode_meja' => $tbl['kode_meja']],
                [
                    'nama_meja' => $tbl['nama_meja'],
                    'kapasitas' => $tbl['kapasitas'],
                    'is_active' => true,
                ]
            );
        }

        // === 3. Kategori Menu ===
        $catMakanan = Category::firstOrCreate(
            ['nama' => 'Makanan Utama'],
            ['urutan' => 1, 'is_active' => true]
        );

        $catMinuman = Category::firstOrCreate(
            ['nama' => 'Kopi & Minuman'],
            ['urutan' => 2, 'is_active' => true]
        );

        $catSnack = Category::firstOrCreate(
            ['nama' => 'Camilan & Snack'],
            ['urutan' => 3, 'is_active' => true]
        );

        // === 4. Katalog Menu Awal ===
        $menus = [
            [
                'category_id' => $catMakanan->id,
                'nama' => 'Nasi Goreng Spesial Kedai',
                'deskripsi' => 'Nasi goreng racikan bumbu khas kedai dengan suwiran ayam, telur mata sapi, dan kerupuk renyah.',
                'harga' => 28000,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $catMakanan->id,
                'nama' => 'Ayam Bakar Madu Wasis',
                'deskripsi' => 'Paha/dada ayam bakar bumbu madu karamel manis gurih disajikan dengan sambal terasi dan lalapan segar.',
                'harga' => 32000,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $catMakanan->id,
                'nama' => 'Mie Goreng Jawa Klasik',
                'deskripsi' => 'Mie telur kenyal dimasak dengan bumbu kemiri wangi, sayuran segar, bakso, dan telur orak-arik.',
                'harga' => 25000,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $catMinuman->id,
                'nama' => 'Kopi Susu Gula Aren',
                'deskripsi' => 'Espresso house blend robusta-arabika dipadu susu segar creamy dan manis legit gula aren asli.',
                'harga' => 18000,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $catMinuman->id,
                'nama' => 'Matcha Latte Frost',
                'deskripsi' => 'Matcha premium Jepang diseduh dengan susu segar dan disajikan dingin menyegarkan.',
                'harga' => 22000,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $catMinuman->id,
                'nama' => 'Es Lemon Tea Segar',
                'deskripsi' => 'Teh hitam aromatik berpadu kesegaran perasan buah lemon asli dan es kristal.',
                'harga' => 14000,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $catSnack->id,
                'nama' => 'Kentang Goreng Crispy',
                'deskripsi' => 'Kentang goreng renyah bumbu sea salt gurih disajikan dengan saus tomat dan mayones.',
                'harga' => 16000,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $catSnack->id,
                'nama' => 'Roti Bakar Cokelat Keju',
                'deskripsi' => 'Roti panggang mentega harum dengan isian pasta cokelat lumer dan taburan keju cheddar melimpah.',
                'harga' => 20000,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'category_id' => $catSnack->id,
                'nama' => 'Pisang Goreng Wijen Madu',
                'deskripsi' => 'Pisang raja manis berbalut tepung renyah beraroma wijen disiram madu murni.',
                'harga' => 16000,
                'is_available' => true,
                'is_active' => true,
            ],
        ];

        foreach ($menus as $m) {
            Menu::firstOrCreate(
                ['nama' => $m['nama']],
                $m
            );
        }
    }
}
