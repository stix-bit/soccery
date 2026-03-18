<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductSearchController extends Controller
{
    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $categoryId = $request->integer('category_id');
        $brandId = $request->integer('brand_id');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');

        $applyFilters = function ($builder) use ($categoryId, $brandId, $minPrice, $maxPrice) {
            $builder->with(['images', 'category', 'brand']);

            if ($categoryId) {
                $builder->where('category_id', $categoryId);
            }

            if ($brandId) {
                $builder->where('brand_id', $brandId);
            }

            if ($minPrice !== null && $minPrice !== '') {
                $builder->where('price', '>=', (float) $minPrice);
            }

            if ($maxPrice !== null && $maxPrice !== '') {
                $builder->where('price', '<=', (float) $maxPrice);
            }
        };

        if (trim($q) === '') {
            $products = Product::query()
                ->tap($applyFilters)
                ->latest()
                ->paginate(12)
                ->withQueryString();
        } else {
            $products = Product::search($q)
                ->query($applyFilters)
                ->paginate(12)
                ->withQueryString();
        }

        return view('search.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'q' => $q,
            'categoryId' => $categoryId,
            'brandId' => $brandId,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ]);
    }
}

