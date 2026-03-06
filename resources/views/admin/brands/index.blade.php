@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold text-primary">Brands</h1>
            <p class="text-muted mb-0">Manage Premier League brands.</p>
        </div>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">Add brand</a>
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
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $brand)
                            <tr @if($brand->trashed()) class="table-warning" @endif>
                                <td>{{ $brand->id }}</td>
                                
                                <td>{{ $brand->name }}</td>
                                <td>{{ Str::limit($brand->description, 60) }}</td>
                                <td>
                                    @if($brand->trashed())
                                        <span class="badge bg-warning text-dark">Archived</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-outline-primary">Edit</a>
                                        @if($brand->trashed())
                                            <form action="{{ route('admin.brands.restore', $brand->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success">Restore</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline" onsubmit="return confirm('Archive this product?')">
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
                                    No brands found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-0">
            {{ $brands->links() }}
        </div>
    </div>
</div>
@endsection

