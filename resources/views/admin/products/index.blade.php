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
        <div class="card-body">
            {!! $dataTable->table(['class' => 'table table-bordered table-striped']) !!}
        </div>
    </div>



@endsection
@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush

