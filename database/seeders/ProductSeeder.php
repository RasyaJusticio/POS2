<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data contoh untuk produk
        $product = [
            [
                'name' => 'Ferrari 458 LBWK',
                'category'=> 'Car',
                'price' => 3000000000,
                'quantity' => 3,
                'description' => 'Ferrari 458 GTB dengan Liberty Walk Body Kits',
                'image_url' => '/storage/produk/ferrari-lbwk.jpg', // Ganti dengan link gambar yang sesuai
            ],
        ];

        // Mengisi tabel produk
        DB::table('products')->insert($product);
    }
}
