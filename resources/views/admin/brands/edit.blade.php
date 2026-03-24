@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="p-4 p-md-5 rounded-4" style="background: linear-gradient(90deg, #6b21a8, #a855f7);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="fw-bold text-white">Edit brand</h1>
                        <p class="text-white-50 mb-0">{{ $brand->name }}</p>
                    </div>
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-light btn-sm">Back to list</a>
                </div>
            </div>

            <div class="card rounded-4 shadow-sm mt-4" style="border: 1px solid #000; overflow: hidden;">
                <div class="card-body">
                    <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ old('name', $brand->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $brand->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update brand</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

