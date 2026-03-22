<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $user = Auth::user();

        if ($user->role !== 'customer') {
            abort(403, 'Only customers can leave reviews.');
        }

        if (! $user->hasPurchasedProduct($product)) {
            abort(403, 'You must have purchased this product to leave a review.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        Review::updateOrCreate(
            [
                'product_id' => $product->id,
                'users_id' => $user->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        return redirect()
            ->route('shop.show', $product)
            ->with('status', 'Your review has been saved.');
    }
}
