<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        Order::create(
            ['name' => 'Som Tam', 'description' => 'A spicy green papaya salad with shredded papaya, tomatoes, and a tangy dressing.', 'price' => 70000, 'category' => 'makanan'],
        );
        Order::create(
            ['name' => 'Pad Thai', 'description' => 'Stir-fried rice noodles with shrimp or chicken, eggs, and peanuts in a savory sauce.', 'price' => 70000, 'category' => 'makanan'],
        );
        Order::create(
            ['name' => 'Khao Pad', 'description' => 'Thai fried rice with jasmine rice, vegetables, and protein, seasoned with soy sauce.', 'price' => 70000, 'category' => 'makanan'],
        );
        Order::create(
            ['name' => 'Khao Soi', 'description' => 'Coconut curry noodle soup topped with crispy noodles and garnished with lime.', 'price' => 80000, 'category' => 'makanan'],
        );
        Order::create(
            ['name' => 'Thai Iced Tea', 'description' => 'A sweet, creamy minuman made from black tea and sweetened condensed milk.', 'price' => 30000, 'category' => 'minuman'],
        );
        Order::create(
            ['name' => 'Tamarind Juice', 'description' => 'Tangy minuman made from tamarind pulp, often sweetened and chilled.', 'price' => 20000, 'category' => 'minuman'],
        );
        Order::create(
            ['name' => 'Coconut Water', 'description' => 'Refreshing, naturally sweet beverage from young green coconuts.', 'price' => 15000, 'category' => 'minuman'],
        );
        Order::create(
            ['name' => 'Yam Nua', 'description' => 'Spicy Thai beef salad with grilled beef, fresh herbs, and a zesty lime dressing.', 'price' => 75000, 'category' => 'makanan'],
        );
        Order::create(
            ['name' => 'Gaeng Som', 'description' => 'Tangy sour curry with fish or shrimp and vegetables, flavored with tamarind.', 'price' => 80000, 'category' => 'makanan'],
        );
        Order::create(
            ['name' => 'Khanom Jeen Nam Ya', 'description' => 'Rice noodles served with a rich fish curry sauce and fresh veggies.', 'price' => 50000, 'category' => 'makanan'], 
        );
        Order::create(
            ['name' => 'Mango Sticky Rice', 'description' => 'Sweet rice topped with ripe mango and drizzled with coconut milk.', 'price' => 25000, 'category' => 'Dessert'],
        );
        Order::create(
            ['name' => 'Tako', 'description' => 'Steamed coconut milk dessert in small cups, topped with a creamy layer.', 'price' => 20000, 'category' => 'Dessert'],
        );
        Order::create(
            ['name' => 'Lod Chong', 'description' => 'Pandan-flavored rice flour noodles served in sweet coconut milk.', 'price' => 20000, 'category' => 'Dessert'],
        );
        Order::create(
            ['name' => 'Tom Kha Kai', 'description' => 'Chicken in a rich coconut milk broth with lemongrass and galangal.', 'price' => 70000, 'category' => 'makanan'],
        );
        Order::create(
            ['name' => 'Larb', 'description' => 'Spicy salad of minced meat with fresh herbs and lime juice.', 'price' => 70000, 'category' => 'makanan'],
        );
        Order::create(
            ['name' => 'P Aor', 'description' => 'Flavorful Thai noodle soup with various toppings in a savory broth.', 'price' => 80000, 'category' => 'makanan'],
        );
        Order::create(
            
            ['name' => 'Pranakorn', 'description' => 'Grilled marinated meat served with a spicy dipping sauce.', 'price' => 75000, 'category' => 'makanan'],
        );
        Order::create(
            ['name' => 'Savoey', 'description' => 'Grilled marinated meat served with a spicy dipping sauce.', 'price' => 80000, 'category' => 'makanan'],
        );
    }}
