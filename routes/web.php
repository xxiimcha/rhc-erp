<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Employee\ProfileController;
use App\Http\Controllers\Employee\EmployeeClockingController;
use App\Http\Controllers\Employee\EmployeeLeaveController;
use App\Http\Controllers\Employee\EmployeePayrollController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PayrollSettingController;
use App\Http\Controllers\Admin\TimekeepingController;

use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\HR\PayrollController;
use App\Http\Controllers\HR\LeaveController;
use App\Http\Controllers\HR\WorkdayController;

use App\Http\Controllers\ClockingController;


use App\Http\Controllers\Api\GeneralController;

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
    Route::post('/users/{user}/assign-card', [UserController::class, 'assignCard'])->name('users.assignCard');

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
        Route::patch('/salaries/{salary}/toggle', [EmployeeController::class, 'toggle'])->name('salaries.toggle');

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

        Route::post('/{id}/approve', [LeaveController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [LeaveController::class, 'reject'])->name('reject');

    });
    
    Route::prefix('hr/workdays')->name('hr.workdays.')->group(function () {
        Route::get('/', [WorkdayController::class, 'index'])->name('index'); // shows all assigned workdays
        Route::post('/store', [WorkdayController::class, 'store'])->name('store'); // assign new workday
        Route::delete('/{id}', [WorkdayController::class, 'destroy'])->name('destroy'); // remove assigned workday
    });
    
    //Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        // Payroll Settings
        Route::get('/payroll', [PayrollSettingController::class, 'edit'])->name('payroll');
        Route::post('/payroll', [PayrollSettingController::class, 'update'])->name('payroll.update');
    
        // Timekeeping Settings
        Route::get('/timekeeping', [TimekeepingController::class, 'index'])->name('timekeeping');
        Route::post('/timekeeping/store', [TimekeepingController::class, 'store'])->name('timekeeping.store');
        Route::delete('/timekeeping/{id}/delete', [TimekeepingController::class, 'destroy'])->name('timekeeping.delete');
        Route::patch('/timekeeping/{id}/move-date', [TimekeepingController::class, 'updateDate'])->name('timekeeping.updateDate');
    
        // Optional: Trigger holiday fetch manually (if needed)
        Route::get('/timekeeping/fetch-holidays/{year?}', [TimekeepingController::class, 'fetchPHHolidays'])->name('timekeeping.fetch');
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
    Route::delete('/leaves/{id}/cancel', [EmployeeLeaveController::class, 'cancel'])->name('leaves.cancel');

    Route::get('/leaves/form', [EmployeeLeaveController::class, 'form'])->name('leaves.form');
    Route::get('/check-balance', [EmployeeLeaveController::class, 'checkBalance']);

});

// Clocking System
Route::view('/clocking', 'clocking.index')->name('clocking.index');
Route::post('/clocking', [ClockingController::class, 'submit'])->name('clocking.submit');


Route::get('/franchises', [GeneralController::class, 'index']);
Route::get('/franchises/{id}', [GeneralController::class, 'show']);
Route::get('/service-pricelists', [GeneralController::class, 'getServicePricelists']);

Route::get('/session/ping', function () {
    Session::put('last_activity', now());
    return response()->json(['status' => 'active']);
})->name('session.ping');