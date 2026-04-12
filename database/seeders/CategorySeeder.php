<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder {
    public function run(): void {
        $categories = [
            ['name' => 'لابتوبات',    'slug' => 'laptops',      'icon' => 'bi-laptop'],
            ['name' => 'سماعات',      'slug' => 'headphones',   'icon' => 'bi-headphones'],
            ['name' => 'ماوس وكيبورد','slug' => 'accessories',  'icon' => 'bi-mouse'],
            ['name' => 'شاشات',       'slug' => 'monitors',     'icon' => 'bi-display'],
            ['name' => 'هواتف',       'slug' => 'phones',       'icon' => 'bi-phone'],
        ];
        foreach ($categories as $c) Category::create($c);
    }
}
