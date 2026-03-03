<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->string('q')->toString());

        $query = User::query()->orderBy('id');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        return view('admin.staff.index', [
            'users' => $query->paginate(20),
            'q' => $q,
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'staff',
        ]);

        return redirect()->route('admin.staff.create')->with('status', __('messages.admin_staff_created'));
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:customer,staff'],
        ]);

        if ((string) $user->role === 'admin') {
            return redirect()->route('admin.staff.index')->with('status', __('messages.admin_users_admin_protected'));
        }

        $user->update([
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.staff.index')->with('status', __('messages.admin_users_role_updated'));
    }
}
