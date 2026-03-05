@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <h1 class="fw-bold text-primary">Orders</h1>
        <p class="text-muted mb-0">List of orders.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user->last_name }} {{ $order->user->first_name }}</td>
                               <td>
                                    @foreach ($order->items as $item)
                                        {{ $item->product->name }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($order->items as $item)
                                        {{ $item->quantity }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="d-flex gap-2">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm w-auto">
                                            <option value="pending" @selected($order->status === 'pending')>Pending</option>
                                            <option value="processing" @selected($order->status === 'processing')>Processing</option>
                                            <option value="shipped" @selected($order->status === 'shipped')>Shipped</option>
                                            <option value="delivered" @selected($order->status === 'delivered')>Delivered</option>
                                            <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-outline-primary btn-sm">Update</button>
                                    </form>
                                </td>
                                 <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-0">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

