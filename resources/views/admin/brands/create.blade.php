@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="p-4 p-md-5 rounded-4" style="background: linear-gradient(90deg, #6b21a8, #a855f7);">
                <h1 class="fw-bold text-white">Add brands</h1>
                <p class="text-white-50 mb-0">Add a new brand</p>
            </div>

            <div class="card rounded-4 shadow-sm mt-4" style="border: 1px solid #000; overflow: hidden;">
                <div class="card-body">
                    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
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

                        

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save brand</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

