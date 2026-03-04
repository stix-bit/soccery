@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold text-primary">Products</h1>
            <p class="text-muted mb-0">Manage Premier League merchandise.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Add product</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr @if($product->trashed()) class="table-warning" @endif>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @php
                                        $image = $product->images->first();
                                    @endphp
                                    @if($image)
                                        <img src="{{ asset('storage/'.$image->img_path) }}" alt="{{ $product->name }}" class="rounded" style="width: 48px; height: 48px; object-fit: cover;">
                                    @else
                                        <span class="text-muted small">No image</span>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category?->name }}</td>
                                <td>{{ $product->brand?->name }}</td>
                                <td>£{{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>
                                    @if($product->trashed())
                                        <span class="badge bg-warning text-dark">Archived</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-primary">Edit</a>
                                        @if($product->trashed())
                                            <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success">Restore</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Archive this product?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">Archive</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-0">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

