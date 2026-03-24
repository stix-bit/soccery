@extends('layouts.app')

@section('content')
<div class="container">
    <div class="p-4 p-md-5 rounded-4" style="background: linear-gradient(90deg, #6b21a8, #a855f7);">
        <div class="row mb-4">
            <div class="col">
                <h1 class="fw-bold text-white">Admin Dashboard</h1>
                <p class="text-white-50">Manage products, users, and monitor Soccery performance.</p>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">Products</h5>
                    <p class="card-text text-muted">Create, update, archive and restore products.</p>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-sm">Manage products</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">Users</h5>
                    <p class="card-text text-muted">View users, update roles and statuses.</p>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">Manage users</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">Orders</h5>
                    <p class="card-text text-muted">View and manage orders.</p>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm">Manage orders</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">Brands</h5>
                    <p class="card-text text-muted">View and manage brands.</p>
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-primary btn-sm">Manage brands</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">Categories</h5>
                    <p class="card-text text-muted">View and manage categories.</p>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary btn-sm">Manage categories</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">Reviews</h5>
                    <p class="card-text text-muted">View and manage customer reviews.</p>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-primary btn-sm">Manage reviews</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary">Sales Charts</h5>
                    <p class="card-text text-muted">View sales trends and product contribution.</p>
                    <a href="{{ route('admin.charts.index') }}" class="btn btn-primary btn-sm">View charts</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

