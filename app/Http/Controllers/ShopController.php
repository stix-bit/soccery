<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
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
        $product->load(['images', 'brand', 'category', 'reviews.user']);

        $canReview = false;
        $userReview = null;

        if (Auth::check() && Auth::user()->role === 'customer') {
            $user = Auth::user();
            $canReview = $user->hasPurchasedProduct($product);
            $userReview = $product->reviews->firstWhere('users_id', $user->id);
        }

        return view('shop.show', compact('product', 'canReview', 'userReview'));
    }
}

