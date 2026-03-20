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

    @if ($errors->has('import_file'))
        <div class="alert alert-danger">
            {{ $errors->first('import_file') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-8">
                    <label for="products-import-file" class="form-label mb-1">Import products</label>
                    <input id="products-import-file" type="file" name="import_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    <small class="text-muted">
                        Columns: name, description, price, stock, and either category_id or category_name plus brand_id or brand_name.
                    </small>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-outline-primary w-100">Import file</button>
                </div>
            </form>
        </div>
    </div>

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

