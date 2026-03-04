<?php

namespace App\Http\Controllers;

use App\Mail\OrderReceiptMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function purchase(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:online,cash_on_delivery'],
        ]);

        $user = Auth::user();

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'payment_method' => $validated['payment_method'],
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
        ]);

        Mail::to($user->email)->send(new OrderReceiptMail($order));

        return redirect()->route('home')->with('status', 'Transaction completed! A receipt has been emailed to you.');
    }
}

