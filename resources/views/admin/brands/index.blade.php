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

    @if ($errors->has('import_file'))
        <div class="alert alert-danger">
            {{ $errors->first('import_file') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.brands.import') }}" method="POST" enctype="multipart/form-data" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-8">
                    <label for="brands-import-file" class="form-label mb-1">Import brands</label>
                    <input id="brands-import-file" type="file" name="import_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    <small class="text-muted">Columns: name, description (optional).</small>
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

