<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'role' => 'required|in:admin,system_admin',
            'department' => 'required_if:role,admin|array',
            'department.*' => 'string'
        ]);

        $name = strtoupper($request->first_name . ' ' . $request->last_name);
        $username = strtoupper($request->username);
        $role = $request->role;

        // Store departments as comma-separated if admin; null otherwise
        $department = $role === 'admin' ? implode(',', $request->department) : null;

        User::create([
            'name' => $name,
            'username' => $username,
            'email' => $username, // optional or replace with real email
            'password' => bcrypt($username), // default password is username
            'role' => $role,
            'department' => $department,
            'is_active' => 1,
        ]);

        return redirect()->back()->with('success', "User created. Username & Password: $username");
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'required|array',
            'department.*' => 'string',
            'is_active' => 'required|in:0,1'
        ]);
    
        $user->update([
            'name' => strtoupper($request->name),
            'department' => implode(',', $request->department),
            'is_active' => $request->is_active,
        ]);
    
        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->back()->with('success', 'User deleted.');
    }
}
