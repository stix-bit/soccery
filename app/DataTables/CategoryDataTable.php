<?php

namespace App\DataTables;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Category> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('description', fn (Category $category) => Str::limit((string) $category->description, 60))
            ->addColumn('status', function (Category $category): string {
                if ($category->trashed()) {
                    return '<span class="badge bg-warning text-dark">Archived</span>';
                }

                return '<span class="badge bg-success">Active</span>';
            })
            ->addColumn('action', function (Category $category): string {
                $html = '<div class="btn-group btn-group-sm" role="group">';

                $html .= '<a href="' . route('admin.categories.edit', $category->id) . '" class="btn btn-outline-primary">Edit</a>';

                if ($category->trashed()) {
                    $html .= '<form action="' . route('admin.categories.restore', $category->id) . '" method="POST" class="d-inline">';
                    $html .= csrf_field();
                    $html .= '<button type="submit" class="btn btn-outline-success">Restore</button>';
                    $html .= '</form>';
                } else {
                    $html .= '<form action="' . route('admin.categories.destroy', $category->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Archive this category?\')">';
                    $html .= csrf_field();
                    $html .= method_field('DELETE');
                    $html .= '<button type="submit" class="btn btn-outline-danger">Archive</button>';
                    $html .= '</form>';
                }

                $html .= '</div>';

                return $html;
            })
            ->editColumn('created_at', fn (Category $category): string => $category->created_at?->format('M d, Y h:i A') ?? '-')
            ->editColumn('updated_at', fn (Category $category): string => $category->updated_at?->format('M d, Y h:i A') ?? '-')
            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Category>
     */
    public function query(Category $model): QueryBuilder
    {
        return $model->newQuery()->withTrashed();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('categories-table')
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
        return 'Category_' . date('YmdHis');
    }
}
