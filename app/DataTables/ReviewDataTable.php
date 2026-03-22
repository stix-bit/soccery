<?php

namespace App\DataTables;

use App\Models\Review;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReviewDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Review> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('product', fn (Review $review): string => $review->product?->name ?? '-')
            ->addColumn('customer', function (Review $review): string {
                $firstName = $review->user?->first_name ?? '';
                $lastName = $review->user?->last_name ?? '';
                $fullName = trim($firstName . ' ' . $lastName);

                return $fullName !== '' ? $fullName : 'Unknown user';
            })
            ->editColumn('comment', fn (Review $review): string => Str::limit((string) $review->comment, 90))
            ->addColumn('status', function (Review $review): string {
                if ($review->trashed()) {
                    return '<span class="badge bg-warning text-dark">Archived</span>';
                }

                return '<span class="badge bg-success">Active</span>';
            })
            ->addColumn('action', function (Review $review): string {
                $html = '<div class="d-flex flex-column gap-2">';

                if (! $review->trashed()) {
                    $html .= '<form action="' . route('admin.reviews.destroy', $review->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Archive this review?\')">';
                    $html .= csrf_field();
                    $html .= method_field('DELETE');
                    $html .= '<button type="submit" class="btn btn-sm btn-outline-warning">Archive</button>';
                    $html .= '</form>';
                } else {
                    $html .= '<form action="' . route('admin.reviews.restore', $review->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Restore this review?\')">';
                    $html .= csrf_field();
                    $html .= '<button type="submit" class="btn btn-sm btn-outline-success">Restore</button>';
                    $html .= '</form>';
                }

                $html .= '<form action="' . route('admin.reviews.forceDestroy', $review->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Permanently delete this review? This cannot be undone.\')">';
                $html .= csrf_field();
                $html .= method_field('DELETE');
                $html .= '<button type="submit" class="btn btn-sm btn-outline-danger">Delete permanently</button>';
                $html .= '</form>';

                $html .= '</div>';

                return $html;
            })
            ->editColumn('created_at', fn (Review $review): string => $review->created_at?->format('M d, Y h:i A') ?? '-')
            ->editColumn('updated_at', fn (Review $review): string => $review->updated_at?->format('M d, Y h:i A') ?? '-')
            ->rawColumns(['status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Review>
     */
    public function query(Review $model): QueryBuilder
    {
        return $model->newQuery()
            ->withTrashed()
            ->with(['product', 'user']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('reviews-table')
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
            Column::make('product')->title('Product'),
            Column::make('customer')->title('Customer'),
            Column::make('rating'),
            Column::make('comment'),
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
        return 'Review_' . date('YmdHis');
    }
}
