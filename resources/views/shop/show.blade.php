@extends('layouts.app')

@section('content')
<div class="container">
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

    <div class="mb-3">
        <a href="{{ auth()->check() ? route('shop.index') : route('landing') }}" class="btn btn-outline-secondary btn-sm">Back to shop</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="row g-0">
            <div class="col-md-5">
                @if($product->images->count())
                    <div id="productShowCarousel{{ $product->id }}" class="carousel slide" data-bs-ride="false">
                        <div class="carousel-inner" style="height: 360px;">
                            @foreach($product->images as $index => $image)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <img
                                        src="{{ asset('storage/'.$image->img_path) }}"
                                        class="d-block w-100"
                                        alt="{{ $product->name }}"
                                        style="height: 360px; object-fit: cover;"
                                    >
                                </div>
                            @endforeach
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#productShowCarousel{{ $product->id }}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productShowCarousel{{ $product->id }}" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 360px;">
                        <span class="text-muted small">No image</span>
                    </div>
                @endif
            </div>

            <div class="col-md-7">
                <div class="card-body p-4">
                    <h2 class="fw-bold text-primary mb-2">{{ $product->name }}</h2>

                    <p class="text-muted mb-1"><strong>Brand:</strong> {{ optional($product->brand)->name ?? '-' }}</p>
                    <p class="text-muted mb-3"><strong>Category:</strong> {{ optional($product->category)->name ?? '-' }}</p>

                    <h4 class="fw-semibold mb-3">£{{ number_format((float) $product->price, 2) }}</h4>

                    <p class="mb-3" style="white-space: pre-line;">{{ $product->description }}</p>

                    <p class="mb-3">
                        <strong>Stock:</strong>
                        @if((int) $product->stock > 0)
                            <span class="text-success">{{ $product->stock }} available</span>
                        @else
                            <span class="text-danger">Out of stock</span>
                        @endif
                    </p>

                    @auth
                        @if(auth()->user()->role === 'customer')
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="row g-2 align-items-end">
                                @csrf
                                <div class="col-sm-4">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input
                                        id="quantity"
                                        type="number"
                                        name="quantity"
                                        class="form-control"
                                        min="1"
                                        max="{{ max(1, (int) $product->stock) }}"
                                        value="1"
                                        required
                                        {{ (int) $product->stock < 1 ? 'disabled' : '' }}
                                    >
                                </div>
                                <div class="col-sm-8 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary" {{ (int) $product->stock < 1 ? 'disabled' : '' }}>
                                        Add to cart
                                    </button>
                                    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary">View cart</a>
                                </div>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">Login to add to cart</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
