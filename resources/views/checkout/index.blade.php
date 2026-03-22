@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-3">
        <h1 class="fw-bold text-primary mb-1">Checkout</h1>
        <p class="text-muted mb-0">Confirm order details and payment method.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Order summary</h5>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Line total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>
                                    @php $image = $item['product']->images->first(); @endphp
                                    @if($image)
                                        <img
                                            src="{{ asset('storage/'.$image->img_path) }}"
                                            alt="{{ $item['product']->name }}"
                                            style="width:88px;height:88px;object-fit:cover;border-radius:8px;"
                                        >
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center text-muted small"
                                            style="width:88px;height:88px;border-radius:8px;">
                                            No image
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $item['product']->name }}</td>
                                <td>{{ optional($item['product']->brand)->name ?? '-' }}</td>
                                <td>{{ optional($item['product']->category)->name ?? '-' }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>&#8369;{{ number_format((float) $item['product']->price, 2) }}</td>
                                <td>&#8369;{{ number_format((float) $item['line_total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <h5 class="mb-0 text-end">Total: &#8369;{{ number_format((float) $total, 2) }}</h5>
        </div>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" class="card border-0 shadow-sm">
        @csrf
        <div class="card-body">
            <h5 class="fw-semibold mb-3">Payment</h5>
            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment method</label>
                <select id="payment_method" name="payment_method" class="form-select" required>
                    <option value="online" @selected(old('payment_method') === 'online')>Online</option>
                    <option value="cash_on_delivery" @selected(old('payment_method') === 'cash_on_delivery')>Cash on delivery</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">Back to cart</a>
                <button type="submit" class="btn btn-primary">Checkout</button>
            </div>
        </div>
    </form>
</div>
@endsection
