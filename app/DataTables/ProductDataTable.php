<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Product> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
         return (new EloquentDataTable($query))
        ->addColumn('image', function ($product) {

            if ($product->images->isEmpty()) {
                return 'No image';
            }

            return $product->images->map(function ($img) {

                return '
                    <div style="display:inline-block;position:relative;margin-right:6px;">
                        <img src="'.asset("storage/".$img->img_path).'"
                            style="width:45px;height:45px;object-fit:cover;border-radius:6px;">

                        <form action="'.route('admin.products.deleteImage', $img->id).'"
                            method="POST"
                            style="position:absolute;top:-6px;right:-6px;">

                            '.csrf_field().'
                            '.method_field('DELETE').'

                            <button type="submit"
                                    style="
                                        width:18px;
                                        height:18px;
                                        border-radius:50%;
                                        font-size:10px;
                                        padding:0;
                                        line-height:18px;
                                    "
                                    class="btn btn-danger">
                                ×
                            </button>
                        </form>
                    </div>
                ';
            })->implode('');
        })
        ->addColumn('category', fn($p) => $p->category?->name)
        ->addColumn('brand', fn($p) => $p->brand?->name)
        ->addColumn('action', function ($product) {

            return '
            <div class="d-flex flex-column gap-2">
                <a href="'.route('admin.products.edit', $product->id).'" 
                class="btn btn-sm btn-primary">
                Edit
                </a>

                <form action="'.route('admin.products.destroy', $product->id).'" 
                    method="POST" 
                    style="display:inline-block"
                    onsubmit="return confirm(\'Are you sure?\')">

                    '.csrf_field().'
                    '.method_field('DELETE').'

                    <button type="submit" class="btn btn-sm btn-danger">
                        Delete
                    </button>
                </form>
            </div>
            ';
        })
        ->rawColumns(['image', 'action'])
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Product>
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery()->with(['category', 'brand', 'images']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('products')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(1)
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
            Column::make('image')->title('Images'),
            Column::make('id'),
            Column::make('name'),
            Column::make('description'),
            Column::make('price'),
            Column::make('stock'),
            Column::make('category'),
            Column::make('brand'),
            Column::make('created_at'),
            Column::make('updated_at'),
            Column::computed('action')
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
        return 'Product_' . date('YmdHis');
    }
}
