<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRolePermissionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $users = User::query()
            ->when($search, fn($query) => $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%"))
            ->when($role, fn($query) => $query->whereHas('roles', fn($q) => $q->where('name', $role)))
            ->when($status, function ($query) use ($status) {
                if ($status === 'inactive') {
                    return $query->onlyTrashed();
                }
                return $query->whereNull('deleted_at');
            })
            ->with('roles')
            ->paginate(10);

        $roles = Role::all();
        return view('users.index', compact('users', 'roles'));
    }

    public function edit(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('id'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|confirmed|min:3',
            'role_id' => 'required|exists:roles,id',
        ]);

        $password = $request->password ?? Str::random(3);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($password),
        ]);

        $user->roles()->sync($validated['role_id']);

        return response()->json(['success' => "User  created! Password: $password"]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:3',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        $user->roles()->sync($validated['role_id']);

      return response()->json(['success' => 'User  updated!']);
        // return view('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => 'User deleted!']);
        // return view('users.index')->with('success', 'User deleted successfully!');
    }
    
    public function restore($id)
{
    $user = User::withTrashed()->findOrFail($id);
    $user->restore();

    return response()->json(['success' => 'User restored!']);
}

}