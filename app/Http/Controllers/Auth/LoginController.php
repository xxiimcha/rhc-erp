<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Employee;
use App\Models\EmployeeWorkday;
use App\Models\LeaveBalance;

class LoginController extends Controller{

    public function showLoginForm()
    {
        // Create default system_admin if not exists
        if (!User::where('role', 'system_admin')->exists()) {
            User::create([
                'name' => 'System Administrator',
                'username' => 'SYSADMIN',
                'email' => 'sysadmin@localhost.com',
                'password' => bcrypt('SYSADMIN'),
                'role' => 'system_admin',
                'is_active' => 1,
            ]);
        }

        // Auto-create workdays for employees without any assigned
        $employees = Employee::all();
        $defaultShift = '08:00 AM - 05:00 PM';
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($employees as $employee) {
            $hasWorkdays = EmployeeWorkday::where('employee_id', $employee->id)->exists();

            if (!$hasWorkdays) {
                foreach ($daysOfWeek as $day) {
                    EmployeeWorkday::create([
                        'employee_id' => $employee->id,
                        'day_of_week' => $day,
                        'shift_time' => $defaultShift,
                        'updated_by' => null,
                    ]);
                }
            }
        }

        // Auto-create leave balances per work anniversary
        $currentYear = now()->year;

        foreach ($employees as $employee) {
            if (!$employee->date_hired) {
                continue;
            }

            // Skip newly hired employees for the current year
            if (\Carbon\Carbon::parse($employee->date_hired)->year >= $currentYear) {
                continue;
            }

            $hasLeaveBalance = LeaveBalance::where('employee_id', $employee->employee_id)
                ->where('year', $currentYear)
                ->exists();

            if (!$hasLeaveBalance) {
                LeaveBalance::create([
                    'employee_id' => $employee->employee_id,
                    'year' => $currentYear,
                    'vacation_leave' => 5,
                    'sick_leave' => 5,
                    'emergency_leave' => 5,
                    'birthday_leave' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
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
