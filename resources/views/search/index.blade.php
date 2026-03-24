@extends('layouts.app')

@section('content')
<div class="container">
    <div class="p-4 p-md-5 rounded-4" style="background: linear-gradient(90deg, #6b21a8, #a855f7);">
        <div class="row align-items-center mb-3">
            <div class="col-md-8">
                <h1 class="fw-bold text-white mb-1">Search</h1>
                <p class="text-white mb-0" style="opacity: 0.9;">
                    @if($q)
                        Results for <span class="fw-semibold">“{{ $q }}”</span>
                    @else
                        Browse products with filters.
                    @endif
                </p>
            </div>
            <!-- <div class="col-md-4 mt-3 mt-md-0">
                <form method="GET" action="{{ route('search.index') }}" class="d-flex gap-2">
                    <input
                        type="text"
                        name="q"
                        value="{{ $q }}"
                        class="form-control"
                        placeholder="Search products…"
                    >
                    <button class="btn btn-primary">Search</button>
                </form>
            </div> -->
        </div>

        <div class="row g-3">
            <div class="col-md-3">
                <div class="card rounded-4 shadow-sm" style="border: 1px solid #000; overflow: hidden;">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Filters</h6>
                        <form method="GET" action="{{ route('search.index') }}" class="vstack gap-3">
                            <input type="hidden" name="q" value="{{ $q }}">

                            <div>
                                <label for="category_id" class="form-label small text-muted mb-1">Category</label>
                                <select id="category_id" name="category_id" class="form-select">
                                    <option value="">All categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected((string)$categoryId === (string)$category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="brand_id" class="form-label small text-muted mb-1">Brand</label>
                                <select id="brand_id" name="brand_id" class="form-select">
                                    <option value="">All brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" @selected((string)$brandId === (string)$brand->id)>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label small text-muted mb-1">Price</label>
                                <div class="d-flex gap-2">
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="min_price"
                                        value="{{ $minPrice }}"
                                        class="form-control"
                                        placeholder="Min"
                                    >
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="max_price"
                                        value="{{ $maxPrice }}"
                                        class="form-control"
                                        placeholder="Max"
                                    >
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary">Apply</button>
                                <a class="btn btn-outline-secondary" href="{{ route('search.index', ['q' => $q]) }}">Reset</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row g-3">
                    @forelse($products as $product)
                        <div class="col-md-4 col-sm-6">
                            <div class="card h-100 rounded-4 shadow-sm" style="border: 1px solid #000; overflow: hidden;">
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
                                    <div class="text-muted small mb-2">
                                        {{ optional($product->brand)->name }}@if($product->brand && $product->category) · @endif{{ optional($product->category)->name }}
                                    </div>
                                    <p class="text-muted small mb-2">{{ Str::limit($product->description, 70) }}</p>

                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <span class="fw-semibold text-primary">&#8369;{{ number_format($product->price, 2) }}</span>
                                        <a href="{{ route('shop.show', $product) }}" class="btn btn-sm btn-primary">Buy now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                No products matched your search / filters.
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

