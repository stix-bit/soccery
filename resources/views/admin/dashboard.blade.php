@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1 class="fw-bold text-primary">Admin Dashboard</h1>
            <p class="text-muted">Manage products, users, and monitor Soccery performance.</p>
        </div>
    </div>

    <div class="row g-3">
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
                    <h5 class="card-title text-primary">Sales Charts</h5>
                    <p class="card-text text-muted">Placeholder</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

