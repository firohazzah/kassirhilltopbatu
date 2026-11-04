<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Pengguna Default
        User::firstOrCreate(
            ['email' => 'admin@kafe.com'],
            [
                'name' => 'Admin Kafe',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'kasir@kafe.com'],
            [
                'name' => 'Kasir 1',
                'password' => Hash::make('kasir123'),
                'role' => 'user',
            ]
        );

        // 2. Kategori Menu
        $coffee = Category::firstOrCreate(['name' => 'Kopi']);
        $nonCoffee = Category::firstOrCreate(['name' => 'Non-Kopi']);
        $food = Category::firstOrCreate(['name' => 'Makanan']);

        // 3. Produk Menu
        $products = [
            [$coffee->id, 'Americano', 18000, 30],
            [$coffee->id, 'Cappuccino', 25000, 25],
            [$coffee->id, 'Cafe Latte', 26000, 25],
            [$nonCoffee->id, 'Matcha Latte', 27000, 20],
            [$nonCoffee->id, 'Chocolate', 23000, 20],
            [$food->id, 'French Fries', 20000, 15],
            [$food->id, 'Croissant', 22000, 12],
        ];

        foreach ($products as [$categoryId, $name, $price, $stock]) {
            Product::firstOrCreate(
                ['name' => $name],
                ['category_id' => $categoryId, 'price' => $price, 'stock' => $stock, 'is_available' => true]
            );
        }
    }
}
