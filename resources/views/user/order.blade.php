@extends('layouts.app')

@section('content')
<div class="container">
    <div class="p-4 p-md-5 rounded-4" style="background: linear-gradient(90deg, #6b21a8, #a855f7);">
        <div class="mb-4">
            <h1 class="fw-bold text-white">My Orders</h1>
            <p class="text-white-50 mb-0">List of your orders.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success mb-0">
                {{ session('status') }}
            </div>
        @endif

        <div class="card rounded-4 shadow-sm mt-4" style="border: 1px solid #000; overflow: hidden;">
            <div class="card-body">
                @if ($orders->isEmpty())
                    <p class="mb-0 text-muted">You have no orders yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Products</th>
                                    <th>Quantities</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment Method</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    @php
                                        $total = $order->items->sum(function ($item) {
                                            return ((float) ($item->product?->price ?? 0)) * (int) $item->quantity;
                                        });
                                    @endphp
                                    <tr>
                                        <td>
                                            @foreach ($order->items as $item)
                                                @php $image = $item->product?->images?->first(); @endphp
                                                @if ($image)
                                                    <div class="mb-2">
                                                        <img
                                                            src="{{ asset('storage/' . $image->img_path) }}"
                                                            alt="{{ $item->product?->name ?? 'Product image' }}"
                                                            style="width:88px;height:88px;object-fit:cover;border-radius:8px;"
                                                        >
                                                    </div>
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center text-muted small mb-2"
                                                        style="width:88px;height:88px;border-radius:8px;">
                                                        No image
                                                    </div>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($order->items as $item)
                                                <div>{{ $item->product?->name ?? 'Deleted product' }}</div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($order->items as $item)
                                                <div>{{ $item->quantity }}</div>
                                            @endforeach
                                        </td>
                                        <td>PHP {{ number_format($total, 2) }}</td>
                                        <td>{{ ucfirst($order->status) }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</td>
                                        <td>{{ $order->created_at?->format('M d, Y h:i A') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
