<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;

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
});
