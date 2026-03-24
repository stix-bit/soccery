@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="p-4 p-md-5 rounded-4" style="background: linear-gradient(90deg, #6b21a8, #a855f7);">
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h1 class="fw-bold text-white mb-1">Soccery Shop</h1>
                <p class="text-white mb-0" style="opacity: 0.9;">Premier League kits, scarves, and more.</p>
            </div>
        </div>

        <div class="row g-3">
            @forelse($products as $product)
                <div class="col-md-3 col-sm-6">
                    <div class="card h-100 rounded-4 shadow-sm" style="border: 1px solid #000; overflow: hidden;">
                        @if($product->images->count())
                            <div id="productCarousel{{ $product->id }}" class="carousel slide" data-bs-ride="false">

                                <div class="carousel-inner" style="height: 180px;">

                                    @foreach($product->images as $index => $image)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <img src="{{ asset('storage/'.$image->img_path) }}"
                                                class="d-block w-100"
                                                style="height: 180px; object-fit: cover;"
                                                alt="{{ $product->name }}">
                                        </div>
                                    @endforeach

                                </div>

                                <!-- Left button -->
                                <button class="carousel-control-prev custom-carousel-btn" type="button"
                                    data-bs-target="#productCarousel{{ $product->id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>

                                <!-- Right button -->
                                <button class="carousel-control-next custom-carousel-btn" type="button"
                                    data-bs-target="#productCarousel{{ $product->id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>

                            </div>
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <span class="text-muted small">No image</span>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title mb-1">{{ $product->name }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit($product->description, 60) }}</p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="fw-semibold text-primary">&#8369;{{ number_format($product->price, 2) }}</span>
                                <a href="{{ route('shop.show', $product) }}" class="btn btn-sm btn-primary">Buy now</a>
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
</div>
@endsection

