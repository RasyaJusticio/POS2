<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                'uuid' => Str::uuid(),
                'name' => 'Khao Pad',
                'category'=> 'makanan',
                'price' => 70000,
                'description' => 'Thai fried rice with jasmine rice, vegetables, and protein, seasoned with soy sauce.',
                'image_url' => '/storage/produk/khao.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Pad Thai',
                'category'=> 'makanan',
                'price' => 70000,
                'description' => 'Stir-fried rice noodles with shrimp or chicken, eggs, and peanuts in a savory sauce',
                'image_url' => '/storage/produk/pad.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Som Tam',
                'category'=> 'makanan',
                'price' => 70000,
                'description' => 'Spicy green papaya salad with shredded papaya, tomatoes, and a tangy dressing.',
                'image_url' => '/storage/produk/somtam.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Gaeng Som',
                'category'=> 'makanan',
                'price' => 80000,
                'description' => 'Tangy sour curry with fish or shrimp and vegetables, flavored with tamarind',
                'image_url' => '/storage/produk/gaengsom.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Yam Nua',
                'category'=> 'makanan',
                'price' => 75000,
                'description' => 'Spicy Thai beef salad with grilled beef',
                'image_url' => '/storage/produk/yamnua.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Khao Soi',
                'category'=> 'makanan',
                'price' => 80000,
                'description' => 'Coconut curry noodle soup topped with crispy noodles and garnished with lime',
                'image_url' => '/storage/produk/soi.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Larb',
                'category'=> 'makanan',
                'price' => 70000,
                'description' => 'Spicy salad of minced meat with fresh herbs and lime juice.',
                'image_url' => '/storage/produk/larb.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Tom Kha Kai',
                'category'=> 'makanan',
                'price' => 70000,
                'description' => 'Chicken in a rich coconut milk broth with lemongrass and galangal.',
                'image_url' => '/storage/produk/tomkhakai.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Khanom Jeen Nam Ya',
                'category'=> 'makanan',
                'price' => 50000,
                'description' => 'Rice noodles served with a rich fish curry sauce and fresh veggies.',
                'image_url' => '/storage/produk/khanom.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Savoey',
                'category'=> 'makanan',
                'price' => 80000,
                'description' => 'Grilled marinated meat served with a spicy dipping sauce',
                'image_url' => '/storage/produk/savoey.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Phranakorn',
                'category'=> 'makanan',
                'price' => 75000,
                'description' => 'Tender noodles in a fragrant, herb-infused broth with protein, spicy, sour, and savory flavors.',
                'image_url' => '/storage/produk/phranakorn.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'P Aor',
                'category'=> 'makanan',
                'price' => 800000,
                'description' => '',
                'image_url' => '/storage/produk/paor.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Coconut Water',
                'category'=> 'minuman',
                'price' => 15000,
                'description' => 'Refreshing, naturally sweet beverage from young green coconuts.',
                'image_url' => '/storage/produk/degan.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Tamarind Juice',
                'category'=> 'minuman',
                'price' => 20000,
                'description' => 'Tangy drink made from tamarind pulp, often sweetened and chilled.',
                'image_url' => '/storage/produk/tamarind.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Thai Iced Tea',
                'category'=> 'minuman',
                'price' => 30000,
                'description' => 'Sweet nd creamy drink made from black tea and sweetened.',
                'image_url' => '/storage/produk/thai.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Lod Chong',
                'category'=> 'dessert',
                'price' => 20000,
                'description' => 'Pandan-flavored rice flour noodles served in sweet coconut milk.',
                'image_url' => '/storage/produk/lodchong.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Tako',
                'category'=> 'dessert',
                'price' => 20000,
                'description' => 'steamed coconut milk dessert in small cups, topped with a creamy layer.',
                'image_url' => '/storage/produk/tako.jpg', // Ganti dengan link gambar yang sesuai
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Mango Sticky Rice',
                'category'=> 'dessert',
                'price' => 25000,
                'description' => 'Sweet sticky rice topped with ripe mango and drizzled with coconut milk.',
                'image_url' => '/storage/produk/mangostickyrice.jpg', // Ganti dengan link gambar yang sesuai
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
