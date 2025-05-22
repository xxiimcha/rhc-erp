<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Employee\ProfileController;
use App\Http\Controllers\Employee\EmployeeClockingController;
use App\Http\Controllers\Employee\EmployeeLeaveController;
use App\Http\Controllers\Employee\EmployeePayrollController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PayrollSettingController;

use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\HR\PayrollController;
use App\Http\Controllers\HR\LeaveController;
use App\Http\Controllers\ClockingController;

// Show login form
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// Handle login
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Handle logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard (no auth middleware for prototyping)
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Roles & Permissions
    Route::view('/roles', 'admin.users.roles')->name('roles.index');

    // Activity Logs
    Route::view('/activity-logs', 'admin.users.activity-logs')->name('activity-logs');

    // HR → Employee Records
    Route::prefix('hr/employees')->name('hr.employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::get('/{id}', [EmployeeController::class, 'show'])->name('show');
        Route::post('/import', [EmployeeController::class, 'import'])->name('import');
        Route::post('/{id}/photo', [EmployeeController::class, 'uploadPhoto'])->name('photo.upload');

        //rfid & salary declaration
        Route::post('/{id}/rfid', [EmployeeController::class, 'storeRfid'])->name('rfid.store');
        Route::post('{id}/salary', [EmployeeController::class, 'storeSalary'])->name('salary.store');

        Route::get('{id}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('{id}', [EmployeeController::class, 'update'])->name('update');
    });

    // HR → Attendance Tracking
    Route::prefix('hr/attendance')->name('hr.attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::post('/manual', [AttendanceController::class, 'storeManual'])->name('manual.store');
        Route::get('/{id}/edit', [AttendanceController::class, 'edit'])->name('edit');
    });

    //HR → Payroll
    Route::prefix('hr/payroll')->name('hr.payroll.')->group(function () {
        Route::get('/', [PayrollController::class, 'index'])->name('index'); // shows cutoff options
        Route::get('/view', [PayrollController::class, 'view'])->name('view'); // shows per-employee payroll
        Route::get('/compute/{id}', [PayrollController::class, 'compute'])->name('compute');
        Route::get('/payslip/{id}', [PayrollController::class, 'payslip'])->name('payslip');
        Route::post('/import', [PayrollController::class, 'import'])->name('import');
    });

    Route::prefix('hr/leaves')->name('hr.leave.')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('index');
        Route::post('/', [LeaveController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [LeaveController::class, 'edit'])->name('edit');
        Route::put('/{id}', [LeaveController::class, 'update'])->name('update');
        Route::delete('/{id}', [LeaveController::class, 'destroy'])->name('destroy');
    });
    
    //Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/payroll', [PayrollSettingController::class, 'edit'])->name('payroll');
        Route::post('/payroll', [PayrollSettingController::class, 'update'])->name('payroll.update');
    });
});


Route::prefix('employee')->name('employee.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update-emergency', [ProfileController::class, 'updateEmergency'])->name('profile.updateEmergency');

    Route::get('/clocking', [EmployeeClockingController::class, 'index'])->name('clocking');
    Route::post('/clocking', [EmployeeClockingController::class, 'store'])->name('clocking.store');

    Route::get('/payroll', [EmployeePayrollController::class, 'index'])->name('payroll');
    
    Route::get('/leaves', [EmployeeLeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves', [EmployeeLeaveController::class, 'store'])->name('leaves.store');
    Route::get('/leaves/form', [EmployeeLeaveController::class, 'form'])->name('leaves.form');

});

// Clocking System
Route::view('/clocking', 'clocking.index')->name('clocking.index');
Route::post('/clocking', [ClockingController::class, 'submit'])->name('clocking.submit');
