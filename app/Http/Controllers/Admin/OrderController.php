<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Mail\OrderStatusUpdatedMail;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::orderByDesc('created_at')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
        'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
    ]);

    $order->update([
        'status' => $validated['status'],
    ]);

    // Send email to the user
    Mail::to($order->user->email)->send(new OrderStatusUpdatedMail($order));

    return back()->with('status', 'Order status updated and customer notified.');
    }
}
