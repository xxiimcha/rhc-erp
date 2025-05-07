<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HR\EmployeeController;

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

// Admin Routes (can wrap with middleware when needed)
// Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
Route::prefix('admin')->name('admin.')->group(function () {

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Roles & Permissions (UI only for now)
    Route::view('/roles', 'admin.users.roles')->name('roles.index');

    // Activity Logs (UI only for now)
    Route::view('/activity-logs', 'admin.users.activity-logs')->name('activity-logs');

    // HR → Employee Records (with controller)
    Route::prefix('hr/employees')->name('hr.employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::get('/{id}', [EmployeeController::class, 'show'])->name('show'); // ← NEW
        Route::post('/{id}/rfid', [EmployeeController::class, 'storeRfid'])->name('rfid.store');
    });
    
});
