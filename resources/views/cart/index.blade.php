@extends('layouts.app')

@section('content')
<div class="container">
    <div class="p-4 p-md-5 rounded-4" style="background: linear-gradient(90deg, #6b21a8, #a855f7);">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="fw-bold text-white mb-1">Your Cart</h1>
            <p class="text-white mb-0" style="opacity: 0.9;">Review your selected items before checkout.</p>
        </div>
        <a href="{{ route('shop.index') }}" class="btn btn-outline-light">Continue shopping</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(empty($items))
        <div class="alert alert-info">
            Your cart is empty.
        </div>
    @else
        <div class="card rounded-4 shadow-sm mb-3" style="border: 1px solid #000; overflow: hidden;">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th style="width: 170px;">Quantity</th>
                            <th>Price</th>
                            <th>Line total</th>
                            <th>Actions</th>
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
                                <td>
                                    <div class="fw-semibold">{{ $item['product']->name }}</div>
                                    <div class="small text-muted">Stock: {{ $item['product']->stock }}</div>
                                </td>
                                <td>{{ optional($item['product']->brand)->name ?? '-' }}</td>
                                <td>{{ optional($item['product']->category)->name ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="d-flex gap-2 js-auto-update-cart-form">
                                        @csrf
                                        @method('PATCH')
                                        <input
                                            type="number"
                                            name="quantity"
                                            class="form-control form-control-sm js-cart-quantity-input"
                                            min="1"
                                            max="{{ max(1, (int) $item['product']->stock) }}"
                                            value="{{ $item['quantity'] }}"
                                            required
                                        >
                                    </form>
                                </td>
                                <td>&#8369;{{ number_format((float) $item['product']->price, 2) }}</td>
                                <td>&#8369;{{ number_format((float) $item['line_total'], 2) }}</td>
                                <td>
                                    <form action="{{ route('cart.remove', $item['product']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white">Total: &#8369;{{ number_format((float) $total, 2) }}</h4>
            <a href="{{ route('checkout.index') }}" class="btn btn-light">Proceed to checkout</a>
        </div>
    @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.js-auto-update-cart-form');

    forms.forEach(function (form) {
        const input = form.querySelector('.js-cart-quantity-input');
        if (!input) {
            return;
        }

        const initialValue = input.value;
        let submitTimer = null;

        const submitIfChanged = function () {
            if (input.value === initialValue) {
                return;
            }

            form.submit();
        };

        input.addEventListener('change', submitIfChanged);

        input.addEventListener('input', function () {
            if (submitTimer) {
                clearTimeout(submitTimer);
            }

            submitTimer = setTimeout(submitIfChanged, 700);
        });
    });
});
</script>
@endpush
