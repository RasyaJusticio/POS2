<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    public function run()
    {
        Item::create([
            'name' => 'Item 1',
            'price' => 10000,
            'quantity' => 50,
            'description' => 'Deskripsi item 1',
            'image_url' => 'images/default.jpg', // Ganti dengan path gambar jika ada
        ]);

        // Tambahkan item lain sesuai kebutuhan
    }
}
