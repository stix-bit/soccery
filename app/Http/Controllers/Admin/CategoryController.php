<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withTrashed()
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {

        $categories = Category::orderBy('id')->pluck('name', 'id');

        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::create($validated);
       
        return redirect()->route('admin.categories.index')
            ->with('status', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        $allCategories = Category::orderBy('id')->pluck('name', 'id'); // optional, for dropdowns etc.

            return view('admin.categories.edit', [
                'category' => $category,     // single category for editing
                'categories' => $allCategories // collection for dropdowns if needed
            ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('status', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('status', 'Category archived successfully.');
    }

    public function restore(int $category): RedirectResponse
    {
        $model = Category::withTrashed()->findOrFail($category);
        $model->restore();

        return redirect()->route('admin.categories.index')
            ->with('status', 'Category restored successfully.');
    }
}
