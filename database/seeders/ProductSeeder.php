<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Product, Category};

class ProductSeeder extends Seeder {
    public function run(): void {
        $laptops     = Category::where('slug', 'laptops')->first()->id;
        $headphones  = Category::where('slug', 'headphones')->first()->id;
        $accessories = Category::where('slug', 'accessories')->first()->id;

        $products = [
            ['name' => 'لابتوب Dell XPS 15',       'description' => 'لابتوب احترافي بمعالج Intel i7 وشاشة 4K',     'price' => 1299.99, 'stock' => 10, 'category_id' => $laptops],
            ['name' => 'لابتوب MacBook Pro M3',     'description' => 'أقوى لابتوب من Apple بشريحة M3',              'price' => 1999.99, 'stock' => 5,  'category_id' => $laptops],
            ['name' => 'سماعة Sony WH-1000XM5',     'description' => 'سماعة لاسلكية بخاصية إلغاء الضوضاء',          'price' => 349.99,  'stock' => 25, 'category_id' => $headphones],
            ['name' => 'سماعة Bose QuietComfort 45','description' => 'صوت نقي وراحة استثنائية طوال اليوم',           'price' => 279.99,  'stock' => 15, 'category_id' => $headphones],
            ['name' => 'ماوس Logitech MX Master 3', 'description' => 'ماوس لاسلكي للمحترفين بدقة 8000 DPI',         'price' => 99.99,   'stock' => 50, 'category_id' => $accessories],
            ['name' => 'كيبورد Keychron K2',        'description' => 'كيبورد ميكانيكي لاسلكي مدمج',                 'price' => 89.99,   'stock' => 30, 'category_id' => $accessories],
        ];

        foreach ($products as $p) Product::create($p);
    }
}
