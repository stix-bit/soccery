@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold text-primary mb-1">Soccery Shop</h1>
            <p class="text-muted mb-0">Premier League kits, scarves, and more.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            @auth
                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">Go to dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm me-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">Register</a>
            @endauth
        </div>
    </div>

    <div class="row g-3">
        @forelse($products as $product)
            <div class="col-md-3 col-sm-6">
                <div class="card h-100 border-0 shadow-sm">
                    @php $image = $product->images->first(); @endphp
                    @if($image)
                        <img src="{{ asset('storage/'.$image->img_path) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 180px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <span class="text-muted small">No image</span>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-1">{{ $product->name }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit($product->description, 60) }}</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span class="fw-semibold text-primary">£{{ number_format($product->price, 2) }}</span>
                            @auth
                                @if(Auth::user()->role === 'customer')
                                    <form action="{{ route('purchase.product', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="payment_method" value="online">
                                        <button type="submit" class="btn btn-sm btn-primary">Buy now</button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Login to buy</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No products available yet.
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
@endsection

