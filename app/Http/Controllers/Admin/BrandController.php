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

class BrandController extends Controller
{
    public function index(): View
    {
        $brands = Brand::withTrashed()
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.brands.index', compact('brands'));
    }

    public function create(): View
    {

        $brands = Brand::orderBy('name')->pluck('name', 'id');

        return view('admin.brands.create', compact('brands'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $brand = Brand::create($validated);
       
        return redirect()->route('admin.brands.index')
            ->with('status', 'Brand created successfully.');
    }

    public function edit(Brand $brand): View
    {
        $allBrands = Brand::orderBy('name')->pluck('name', 'id'); // optional, for dropdowns etc.

            return view('admin.brands.edit', [
                'brand' => $brand,     // single brand for editing
                'brands' => $allBrands // collection for dropdowns if needed
            ]);
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $brand->update($validated);

        return redirect()->route('admin.brands.index')
            ->with('status', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $brand->delete();

        return redirect()->route('admin.brands.index')
            ->with('status', 'Brand archived successfully.');
    }

    public function restore(int $brand): RedirectResponse
    {
        $model = Brand::withTrashed()->findOrFail($brand);
        $model->restore();

        return redirect()->route('admin.brands.index')
            ->with('status', 'Brand restored successfully.');
    }
}
