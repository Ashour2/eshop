<?php
namespace App\Http\Controllers;

use App\Models\{Product, Category, Setting};
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('active', true)->with('category');

        // ── بحث بالاسم (في اللغتين) ────────────────────
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%$search%")
                  ->orWhere('name_en', 'like', "%$search%");
            });
        }

        // ── فلتر بالتصنيف ────────────────────────────
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // ── الترتيب ───────────────────────────────────
        match($request->sort ?? 'latest') {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name'       => $query->orderBy('name', 'asc'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(9)->withQueryString();
        $categories = Category::all();

        // إحصائيات قسم "من نحن"
        $stats = [
            'products' => Product::where('active', true)->count(),
            'clients'  => 1200,
            'years'    => 5,
        ];

        // السلايدر
        $locale    = app()->getLocale();
        $rawSlides = json_decode(Setting::get('sliders', '[]'), true) ?? [];
        $slides    = array_map(function ($s) use ($locale) {
            return [
                'title'    => $locale === 'ar' ? ($s['title_ar'] ?? '') : ($s['title_en'] ?? ''),
                'subtitle' => $locale === 'ar' ? ($s['subtitle_ar'] ?? '') : ($s['subtitle_en'] ?? ''),
                'bg'       => $s['bg'] ?? '',
                'btn'      => __('shop.shop_now'),
            ];
        }, $rawSlides);

        return view('shop.index', compact('products', 'categories', 'stats', 'slides'));
    }

    public function show(Product $product)
    {
        return view('shop.show', compact('product'));
    }
}
