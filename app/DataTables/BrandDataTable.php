<?php

namespace App\DataTables;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BrandDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Brand> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('description', fn (Brand $brand) => Str::limit((string) $brand->description, 60))
            ->addColumn('status', function (Brand $brand): string {
                if ($brand->trashed()) {
                    return '<span class="badge bg-warning text-dark">Archived</span>';
                }

                return '<span class="badge bg-success">Active</span>';
            })
            ->addColumn('action', function (Brand $brand): string {
                $html = '<div class="btn-group btn-group-sm" role="group">';

                $html .= '<a href="' . route('admin.brands.edit', $brand->id) . '" class="btn btn-outline-primary">Edit</a>';

                if ($brand->trashed()) {
                    $html .= '<form action="' . route('admin.brands.restore', $brand->id) . '" method="POST" class="d-inline">';
                    $html .= csrf_field();
                    $html .= '<button type="submit" class="btn btn-outline-success">Restore</button>';
                    $html .= '</form>';
                } else {
                    $html .= '<form action="' . route('admin.brands.destroy', $brand->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Archive this brand?\')">';
                    $html .= csrf_field();
                    $html .= method_field('DELETE');
                    $html .= '<button type="submit" class="btn btn-outline-danger">Archive</button>';
                    $html .= '</form>';
                }

                $html .= '</div>';

                return $html;
            })
            ->editColumn('created_at', fn (Brand $brand): string => $brand->created_at?->format('M d, Y h:i A') ?? '-')
            ->editColumn('updated_at', fn (Brand $brand): string => $brand->updated_at?->format('M d, Y h:i A') ?? '-')
            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Brand>
     */
    public function query(Brand $model): QueryBuilder
    {
        return $model->newQuery()->withTrashed();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('brands-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0, 'desc')
                    ->selectStyleSingle()
                    ->dom('Bfrtip')
                    ->buttons(['pdf', 'excel', 'csv', 'reload', 'print']);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('description'),
            Column::computed('status')
                ->title('Status')
                ->exportable(false)
                ->printable(false),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::computed('action')
                ->title('Action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Brand_' . date('YmdHis');
    }
}
