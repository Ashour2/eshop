<?php
namespace App\Http\Controllers;

use App\Models\{Product, Category};
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('active', true)->with('category');

        // ── بحث بالاسم ──────────────────────────────
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // ── فلتر بالتصنيف ────────────────────────────
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // ── فلتر بالسعر ──────────────────────────────
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
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
        $slides = [
            ['title' => 'أحدث التقنيات بين يديك',    'subtitle' => 'اكتشف أفضل المنتجات الإلكترونية بأسعار لا تُقاوم',       'bg' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=1400&q=80', 'btn' => 'تسوق الآن'],
            ['title' => 'عروض حصرية لفترة محدودة',   'subtitle' => 'خصومات تصل إلى 40% على أحدث اللابتوبات والإكسسوارات',   'bg' => 'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=1400&q=80', 'btn' => 'اكتشف العروض'],
            ['title' => 'سماعات تجربة صوت مختلفة',   'subtitle' => 'أفضل سماعات العالم بجودة استثنائية وراحة لا مثيل لها',   'bg' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=1400&q=80', 'btn' => 'تسوق الآن'],
            ['title' => 'إكسسوارات تُكمل إنتاجيتك', 'subtitle' => 'ماوس، كيبورد، وكل ما تحتاجه لمكتبك المثالي',             'bg' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=1400&q=80', 'btn' => 'تصفح المنتجات'],
        ];

        return view('shop.index', compact('products', 'categories', 'stats', 'slides'));
    }

    public function show(Product $product)
    {
        return view('shop.show', compact('product'));
    }
}
