@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="p-4 p-md-5 rounded-4" style="background: linear-gradient(90deg, #6b21a8, #a855f7);">
                <h1 class="fw-bold text-white">Add product</h1>
                <p class="text-white-50 mb-0">Create a new Premier League item.</p>
            </div>

            <div class="card rounded-4 shadow-sm mt-4" style="border: 1px solid #000; overflow: hidden;">
                <div class="card-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Select category</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" @selected(old('category_id') == $id)>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                                <option value="" disabled selected>Select brand</option>
                                @foreach($brands as $id => $name)
                                    <option value="{{ $id }}" @selected(old('brand_id') == $id)>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" min="0" value="{{ old('stock') }}" class="form-control @error('stock') is-invalid @enderror" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Photos</label>
                            <input id="images" type="file" name="images[]" multiple class="form-control @error('image') is-invalid @enderror" accept="image/*">

                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div id="image-preview" class="d-flex flex-wrap gap-2 mt-3"></div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('images');
    const preview = document.getElementById('image-preview');

    if (!input || !preview) {
        return;
    }

    input.addEventListener('change', function () {
        preview.innerHTML = '';

        Array.from(input.files || []).forEach(function (file) {
            if (!file.type.startsWith('image/')) {
                return;
            }

            const url = URL.createObjectURL(file);
            const img = document.createElement('img');
            img.src = url;
            img.alt = file.name;
            img.style.width = '88px';
            img.style.height = '88px';
            img.style.objectFit = 'cover';
            img.style.borderRadius = '8px';
            img.style.border = '1px solid #000';

            img.addEventListener('load', function () {
                URL.revokeObjectURL(url);
            });

            preview.appendChild(img);
        });
    });
});
</script>
@endpush

