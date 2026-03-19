<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderByDesc('id')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,inactive,suspended'],
        ]);

        $user->update([
            'status' => $validated['status'],
        ]);

        return back()->with('status', 'User status updated.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:admin,customer'],
        ]);

        $user->update([
            'role' => $validated['role'],
        ]);

        return back()->with('status', 'User role updated.');
    }
}

