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

// Users (Admin Prototype)
Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');