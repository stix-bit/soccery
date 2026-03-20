@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <h1 class="fw-bold text-primary">Users</h1>
        <p class="text-muted mb-0">List of registered users with roles and statuses.</p>
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
</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush

