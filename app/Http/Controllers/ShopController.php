<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['images', 'brand', 'category'])
            ->latest()
            ->orderByDesc('id')
            ->paginate(12);
            

        return view('shop.index', compact('products'));
    }

    public function show(Product $product): View
    {
        $product->load(['images', 'brand', 'category']);

        return view('shop.show', compact('product'));
    }
}

