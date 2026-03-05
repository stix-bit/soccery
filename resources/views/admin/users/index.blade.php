@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <h1 class="fw-bold text-primary">Users</h1>
        <p class="text-muted mb-0">List of registered users with roles and statuses.</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    @if($user->img_path)
                                        <img src="{{ asset('storage/'.$user->img_path) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <span class="badge rounded-pill bg-secondary">No photo</span>
                                    @endif
                                </td>
                                <td>{{ $user->last_name }} {{ $user->first_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <form action="{{ route('admin.users.role', $user) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf
                                        <select name="role" class="form-select form-select-sm w-auto">
                                            <option value="customer" @selected($user->role === 'customer')>Customer</option>
                                            <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                        </select>
                                        <button type="submit" class="btn btn-outline-primary btn-sm">Update</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('admin.users.status', $user) }}" method="POST" class="d-flex align-items-center gap-2">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm w-auto">
                                            <option value="active" @selected($user->status === 'active')>Active</option>
                                            <option value="inactive" @selected($user->status === 'inactive')>Inactive</option>
                                            <option value="suspended" @selected($user->status === 'suspended')>Suspended</option>
                                        </select>
                                        <button type="submit" class="btn btn-outline-primary btn-sm">Update</button>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <span class="text-muted small">CRUD hooks can be added here.</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-0">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

