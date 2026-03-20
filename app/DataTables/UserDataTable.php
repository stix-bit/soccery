<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<User> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('photo', function (User $user): string {
                if ($user->img_path) {
                    return '<img src="' . asset('storage/' . $user->img_path) . '" alt="' . e(trim($user->first_name . ' ' . $user->last_name)) . '" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">';
                }

                return '<span class="badge rounded-pill bg-secondary">No photo</span>';
            })
            ->addColumn('name', fn (User $user): string => e(trim($user->last_name . ' ' . $user->first_name)))
            ->addColumn('role_action', function (User $user): string {
                $options = [
                    'customer' => 'Customer',
                    'admin' => 'Admin',
                ];

                $html = '<form action="' . route('admin.users.role', $user->id) . '" method="POST" class="d-flex align-items-center gap-2">';
                $html .= csrf_field();
                $html .= '<select name="role" class="form-select form-select-sm w-auto">';

                foreach ($options as $value => $label) {
                    $selected = $user->role === $value ? ' selected' : '';
                    $html .= '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
                }

                $html .= '</select>';
                $html .= '<button type="submit" class="btn btn-outline-primary btn-sm">Update</button>';
                $html .= '</form>';

                return $html;
            })
            ->addColumn('status_action', function (User $user): string {
                $options = [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'suspended' => 'Suspended',
                ];

                $html = '<form action="' . route('admin.users.status', $user->id) . '" method="POST" class="d-flex align-items-center gap-2">';
                $html .= csrf_field();
                $html .= '<select name="status" class="form-select form-select-sm w-auto">';

                foreach ($options as $value => $label) {
                    $selected = $user->status === $value ? ' selected' : '';
                    $html .= '<option value="' . $value . '"' . $selected . '>' . $label . '</option>';
                }

                $html .= '</select>';
                $html .= '<button type="submit" class="btn btn-outline-primary btn-sm">Update</button>';
                $html .= '</form>';

                return $html;
            })
            ->addColumn('verification_status', function (User $user): string {
                if ($user->hasVerifiedEmail()) {
                    $verifiedAt = $user->email_verified_at?->format('M d, Y h:i A') ?? 'Verified';

                    return '<span class="badge bg-success">Verified</span><br><small class="text-muted">' . e($verifiedAt) . '</small>';
                }

                return '<span class="badge bg-warning text-dark">Not verified</span>';
            })
            ->editColumn('created_at', fn (User $user): string => $user->created_at?->format('M d, Y h:i A') ?? '-')
            ->editColumn('updated_at', fn (User $user): string => $user->updated_at?->format('M d, Y h:i A') ?? '-')
            ->rawColumns(['photo', 'role_action', 'status_action', 'verification_status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<User>
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('users-table')
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
            Column::computed('photo')
                ->title('Photo')
                ->exportable(false)
                ->printable(false),
            Column::computed('name')->title('Name'),
            Column::make('email'),
            Column::computed('verification_status')
                ->title('Verification')
                ->exportable(false)
                ->printable(false),
            Column::computed('role_action')
                ->title('Role')
                ->exportable(false)
                ->printable(false),
            Column::computed('status_action')
                ->title('Status')
                  ->exportable(false)
                  ->printable(false),
            Column::make('created_at'),
            Column::make('updated_at'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
