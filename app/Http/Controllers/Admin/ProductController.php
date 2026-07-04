<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    public function index() {
        $products = Product::with('category')->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create() {
        $categories = Category::orderBy('name_ar')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name_ar'        => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'category_id'    => 'nullable|exists:categories,id',
            'active'         => 'boolean',
        ]);

        $data['active']      = $request->has('active');
        $data['name']        = $data['name_ar'];
        $data['description'] = $data['description_ar'] ?? null;

        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'تم إضافة الخدمة بنجاح');
    }

    public function edit(Product $product) {
        $categories = Category::orderBy('name_ar')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product) {
        $data = $request->validate([
            'name_ar'        => 'required|string|max:255',
            'name_en'        => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'category_id'    => 'nullable|exists:categories,id',
        ]);

        $data['active']      = $request->has('active');
        $data['name']        = $data['name_ar'];
        $data['description'] = $data['description_ar'] ?? null;

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'تم تحديث الخدمة');
    }

    public function destroy(Product $product) {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'تم حذف الخدمة');
    }
}
