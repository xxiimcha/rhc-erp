<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

// Show login form
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// Handle login POST
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Handle logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard (protected)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
