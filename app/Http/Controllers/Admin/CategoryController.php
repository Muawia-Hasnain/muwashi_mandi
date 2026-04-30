<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('ads')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'image_icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $category = new Category($validated);
        $category->slug = Str::slug($request->name);
        $category->is_active = $request->has('is_active');
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image_icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $category->fill($validated);
        $category->slug = Str::slug($request->name);
        $category->is_active = $request->has('is_active');
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->ads()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Cannot delete category with associated ads.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    public function toggle(Category $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();

        return response()->json(['success' => true, 'is_active' => $category->is_active]);
    }
}
