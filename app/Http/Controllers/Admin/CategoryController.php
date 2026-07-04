<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller {
    public function index() {
        $categories = Category::withCount('products')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create() {
        return view('admin.categories.create');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'slug'    => 'nullable|string|max:255|unique:categories,slug',
            'icon'    => 'nullable|string|max:100',
        ]);

        $data['name'] = $data['name_ar'];
        $data['slug'] = $this->uniqueSlug(($data['slug'] ?? null) ?: (($data['name_en'] ?? null) ?: $data['name_ar']));
        $data['icon'] = ($data['icon'] ?? null) ?: 'bi-grid';

        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'تم إضافة القسم بنجاح');
    }

    public function edit(Category $category) {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category) {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'slug'    => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'icon'    => 'nullable|string|max:100',
        ]);

        $data['name'] = $data['name_ar'];
        $data['icon'] = ($data['icon'] ?? null) ?: 'bi-grid';

        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'تم تحديث القسم');
    }

    public function destroy(Category $category) {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'تم حذف القسم (سيتم إلغاء ربط منتجاته)');
    }

    private function uniqueSlug(string $base): string {
        $slug = Str::slug($base) ?: 'category-' . Str::random(6);
        $original = $slug;
        $i = 2;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }
}
