<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder {
    public function run(): void {
        $categories = [
            ['name_ar' => 'تطوير الواجهات الأمامية', 'name_en' => 'Frontend Development',       'slug' => 'frontend',    'icon' => 'bi-window-stack'],
            ['name_ar' => 'تطوير الخوادم',           'name_en' => 'Backend Development',        'slug' => 'backend',     'icon' => 'bi-server'],
            ['name_ar' => 'تصميم مواقع إلكترونية',   'name_en' => 'Web Design',                 'slug' => 'web-design',  'icon' => 'bi-palette'],
            ['name_ar' => 'تطبيقات موبايل',          'name_en' => 'Mobile Apps',                'slug' => 'mobile-apps', 'icon' => 'bi-phone'],
            ['name_ar' => 'تصميم UI/UX',             'name_en' => 'UI/UX Design',               'slug' => 'ui-ux',       'icon' => 'bi-brush'],
            ['name_ar' => 'تهيئة محركات البحث',      'name_en' => 'SEO',                        'slug' => 'seo',         'icon' => 'bi-search'],
            ['name_ar' => 'استضافة ودعم تقني',       'name_en' => 'Hosting & Technical Support', 'slug' => 'hosting',     'icon' => 'bi-cloud-check'],
        ];
        foreach ($categories as $c) {
            Category::create(array_merge($c, ['name' => $c['name_ar']]));
        }
    }
}
