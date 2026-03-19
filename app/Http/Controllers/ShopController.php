<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $products = Product::with('images')
            ->latest()
            ->orderByDesc('id')
            ->paginate(12);
            

        return view('shop.index', compact('products'));
    }
}

