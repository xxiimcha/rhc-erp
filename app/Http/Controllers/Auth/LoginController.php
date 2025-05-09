<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Check if a system_admin exists
        if (!\App\Models\User::where('role', 'system_admin')->exists()) {
            \App\Models\User::create([
                'name' => 'System Administrator',
                'username' => 'SYSADMIN',
                'email' => 'sysadmin@localhost.com', // <- required field
                'password' => bcrypt('SYSADMIN'),
                'role' => 'system_admin',
                'is_active' => 1,
            ]);
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
