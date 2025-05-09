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
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users,username',
            'role' => 'required|in:admin,system_admin',
        ]);
    
        $name = strtoupper($request->first_name . ' ' . $request->last_name);
        $username = strtoupper($request->username);
        $role = $request->role;
        $department = $role === 'admin' ? $request->department : null;
    
        User::create([
            'name' => $name,
            'username' => $username,
            'email' => $username, // optional or use a placeholder
            'password' => bcrypt($username), // password = username
            'role' => $role,
            'department' => $department,
            'is_active' => 1,
        ]);
    
        return redirect()->back()->with('success', "User created. Username & Password: $username");
    }    

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->except('password') + [
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'department' => $request->department,
        ]);

        return redirect()->back()->with('success', 'User updated.');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->back()->with('success', 'User deleted.');
    }
}
