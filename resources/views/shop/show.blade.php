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

    <div class="p-4 p-md-5 rounded-4" style="background: linear-gradient(90deg, #6b21a8, #a855f7);">
        <div class="bg-white rounded-4 p-3 p-md-4">
            <div class="mb-3">
                <a href="{{ auth()->check() ? route('shop.index') : route('landing') }}" class="btn btn-outline-secondary btn-sm">Back to shop</a>
            </div>

        <div class="card rounded-4 shadow-sm" style="border: 1px solid #000; overflow: hidden;">
            <div class="row g-0">
                <div class="col-md-5 p-3">
                @if($product->images->count())
                    <div class="rounded-3 overflow-hidden" style="border: 1px solid #000;">
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
                    </div>
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded-3" style="height: 360px; border: 1px solid #000;">
                        <span class="text-muted small">No image</span>
                    </div>
                @endif
                </div>

                <div class="col-md-7">
                    <div class="card-body p-4">
                    <h2 class="fw-bold text-primary mb-2">{{ $product->name }}</h2>

                    <p class="text-muted mb-1"><strong>Brand:</strong> {{ optional($product->brand)->name ?? '-' }}</p>
                    <p class="text-muted mb-3"><strong>Category:</strong> {{ optional($product->category)->name ?? '-' }}</p>

                    <h4 class="fw-semibold mb-3">&#8369;{{ number_format((float) $product->price, 2) }}</h4>

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
    

        {{-- Reviews section --}}
        <div class="card rounded-4 shadow-sm mt-4" style="border: 1px solid #000; overflow: hidden;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Reviews</h5>

                @if($canReview)
                    <div class="border rounded p-3 mb-4 bg-light">
                        <h6 class="mb-2">{{ $userReview ? 'Update your review' : 'Write a review' }}</h6>
                        <p class="text-muted small mb-2">Only customers who purchased this product can leave a review.</p>
                        <form action="{{ route('reviews.store', $product) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small">Rating</label>
                                <div class="d-flex flex-row-reverse justify-content-end gap-1">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input
                                            class="btn-check"
                                            type="radio"
                                            name="rating"
                                            id="rating{{ $i }}"
                                            value="{{ $i }}"
                                            {{ old('rating', $userReview?->rating ?? 0) == $i ? 'checked' : '' }}
                                            required
                                        >
                                        <label class="btn btn-outline-warning px-2" for="rating{{ $i }}" title="{{ $i }} star{{ $i > 1 ? 's' : '' }}">★</label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label for="comment" class="form-label small">Comment (optional)</label>
                                <textarea
                                    id="comment"
                                    name="comment"
                                    class="form-control @error('comment') is-invalid @enderror"
                                    rows="3"
                                    maxlength="2000"
                                    placeholder="Share your thoughts..."
                                >{{ old('comment', $userReview?->comment ?? '') }}</textarea>
                                @error('comment')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">{{ $userReview ? 'Update review' : 'Post review' }}</button>
                        </form>
                    </div>
                @elseif(auth()->check() && auth()->user()->role === 'customer')
                    <p class="text-muted small mb-3">You must purchase this product to leave a review.</p>
                @endif

                @php $reviews = $product->reviews; @endphp
                @if($reviews->count() > 0)
                    <div class="d-flex align-items-center gap-2 mb-3">
                        @php
                            $avgRating = round($reviews->avg('rating'), 1);
                            $fullStars = (int) floor($avgRating);
                            $halfStar = $avgRating - $fullStars >= 0.5;
                        @endphp
                        <span class="text-warning" title="Average: {{ $avgRating }}/5">
                            @for($i = 0; $i < $fullStars; $i++) ★ @endfor
                            @if($halfStar) ½ @endif
                        </span>
                        <span class="text-muted small">{{ $avgRating }}/5 ({{ $reviews->count() }} review{{ $reviews->count() > 1 ? 's' : '' }})</span>
                    </div>
                    <ul class="list-unstyled mb-0">
                        @foreach($reviews as $review)
                            <li class="border-bottom py-3 {{ !$loop->last ? '' : 'border-bottom-0' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <span class="text-warning">
                                            @for($i = 0; $i < (int) $review->rating; $i++) ★ @endfor
                                        </span>
                                        <span class="fw-semibold ms-1">{{ optional($review->user)->first_name ?? 'User' }} {{ optional($review->user)->last_name ?? '' }}</span>
                                    </div>
                                    <span class="text-muted small">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                @if($review->comment)
                                    <p class="mb-0 mt-1 text-muted small">{{ $review->comment }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">No reviews yet. Be the first to review after purchasing!</p>
                @endif
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
