<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\OrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\OrderStatusUpdatedMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(OrderDataTable $dataTable)
    {
        return $dataTable->render('admin.orders.index');
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
