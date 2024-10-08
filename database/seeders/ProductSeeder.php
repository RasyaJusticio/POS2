<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Import Str untuk UUID

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
        $products = [
            [
                'name' => 'Khao Soi',
                'category'=> 'makanan',
                'price' => 30000,
                'description' => 'Mie kari berbasis santan',
                'image_url' => '/storage/produk/soi.jpeg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'name' => 'Som Tam',
                'category'=> 'makanan',
                'price' => 22000,
                'description' => 'Salad pepaya hijau pedas',
                'image_url' => '/storage/produk/somtam.jpeg', // Ganti dengan link gambar yang sesuai
            ],
        ];

        // Menambahkan UUID secara dinamis
        foreach ($products as &$product) {
            $product['uuid'] = Str::uuid()->toString();
        }

        // Mengisi tabel produk
        DB::table('products')->insert($products);
    }
}
