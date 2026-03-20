<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Order> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('customer', function (Order $order): string {
                $name = trim(($order->user?->last_name ?? '') . ' ' . ($order->user?->first_name ?? ''));

                return $name !== '' ? e($name) : 'Unknown user';
            })
            ->addColumn('products', function (Order $order): string {
                return $order->items
                    ->map(fn ($item) => e($item->product?->name ?? 'Deleted product'))
                    ->implode('<br>');
            })
            ->addColumn('quantities', function (Order $order): string {
                return $order->items
                    ->map(fn ($item) => (string) $item->quantity)
                    ->implode('<br>');
            })
            ->addColumn('status_action', function (Order $order): string {
                $options = [
                    'pending' => 'Pending',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                ];

                $html = '<form action="' . route('admin.orders.status', $order->id) . '" method="POST" class="d-flex gap-2">';
                $html .= csrf_field();
                $html .= '<select name="status" class="form-select form-select-sm w-auto">';

                foreach ($options as $value => $label) {
                    $selected = $order->status === $value ? ' selected' : '';
                    $html .= '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
                }

                $html .= '</select>';
                $html .= '<button type="submit" class="btn btn-outline-primary btn-sm">Update</button>';
                $html .= '</form>';

                return $html;
            })
            ->editColumn('payment_method', fn (Order $order): string => ucfirst(str_replace('_', ' ', (string) $order->payment_method)))
            ->editColumn('created_at', fn (Order $order): string => $order->created_at?->format('M d, Y h:i A') ?? '-')
            ->rawColumns(['products', 'quantities', 'status_action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Order>
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery()->with(['user', 'items.product']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('orders-table')
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
            Column::computed('customer')->title('Customer'),
            Column::computed('products')->title('Product'),
            Column::computed('quantities')->title('Quantity'),
            Column::computed('status_action')
                  ->title('Status')
                  ->exportable(false)
                  ->printable(false),
            Column::make('payment_method')->title('Payment Method'),
            Column::make('created_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Order_' . date('YmdHis');
    }
}
