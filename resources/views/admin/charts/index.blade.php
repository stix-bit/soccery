@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold text-primary mb-1">Sales Charts</h1>
            <p class="text-muted mb-0">Review sales trends and product contribution by date range.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to dashboard</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.charts.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start date and time</label>
                    <input
                        type="datetime-local"
                        id="start_date"
                        name="start_date"
                        class="form-control"
                        value="{{ $startDate->format('Y-m-d\\TH:i') }}"
                        required
                    >
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End date and time</label>
                    <input
                        type="datetime-local"
                        id="end_date"
                        name="end_date"
                        class="form-control"
                        value="{{ $endDate->format('Y-m-d\\TH:i') }}"
                        required
                    >
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Apply range</button>
                    <a href="{{ route('admin.charts.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-semibold text-primary">Sales Bar Chart</h5>
                    <p class="text-muted small mb-3">Daily sales amount in selected range.</p>
                    <div style="height: 380px;">
                        {!! $salesBarChart->container() !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-semibold text-primary mb-0">Product Sales Share</h5>
                        <form method="GET" action="{{ route('admin.charts.index') }}" class="d-flex align-items-center gap-2">
                            <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d\\TH:i') }}">
                            <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d\\TH:i') }}">
                            <label for="pie_scope" class="small text-muted mb-0">Scope</label>
                            <select
                                id="pie_scope"
                                name="pie_scope"
                                class="form-select form-select-sm"
                                onchange="this.form.submit()"
                            >
                                <option value="all" @selected(($pieScope ?? 'all') === 'all')>All</option>
                                <option value="top10" @selected(($pieScope ?? 'all') === 'top10')>Top 10</option>
                            </select>
                        </form>
                    </div>
                    <p class="text-muted small mb-3">
                        Percentage of total sales by product
                        @if(($pieScope ?? 'all') === 'top10')
                            (Top 10).
                        @else
                            (All products).
                        @endif
                    </p>
                    <div style="height: 380px;">
                        {!! $productPieChart->container() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {!! $salesBarChart->script() !!}
    {!! $productPieChart->script() !!}
@endpush
