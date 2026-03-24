@extends('layouts.app')

@section('content')
<div class="container">
    <div class="p-4 p-md-5 rounded-4" style="background: linear-gradient(90deg, #6b21a8, #a855f7);">
        <div class="mb-4">
            <h1 class="fw-bold text-white">Orders</h1>
            <p class="text-white-50 mb-0">List of orders.</p>
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
    </div>

    <div class="card rounded-4 shadow-sm mt-4" style="border: 1px solid #000; overflow: hidden;">
        <div class="card-body">
            {!! $dataTable->table(['class' => 'table table-bordered table-striped']) !!}
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush

