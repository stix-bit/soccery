@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="p-4 p-md-5 rounded-4" style="background: linear-gradient(90deg, #6b21a8, #a855f7);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="fw-bold text-white">Edit product</h1>
                        <p class="text-white-50 mb-0">{{ $product->name }}</p>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light btn-sm">Back to list</a>
                </div>
            </div>

            <div class="card rounded-4 shadow-sm mt-4" style="border: 1px solid #000; overflow: hidden;">
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" @selected(old('category_id', $product->category_id) == $id)>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                                @foreach($brands as $id => $name)
                                    <option value="{{ $id }}" @selected(old('brand_id', $product->brand_id) == $id)>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" class="form-control @error('price') is-invalid @enderror" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock) }}" class="form-control @error('stock') is-invalid @enderror" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label d-block">Photos</label>
                            @php
                                $images = $product->images;
                            @endphp
                            @if($images->isNotEmpty())
                                @foreach($images as $image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/'.$image->img_path) }}" alt="{{ $product->name }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                @endforeach
                            @endif
                            <input type="file" name="images[]" class="form-control @error('images') is-invalid @enderror" accept="image/*" multiple>
                            <small class="text-muted">Uploading new photos will add to the existing ones.</small>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

