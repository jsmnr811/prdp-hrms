<?php

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Login;
use Illuminate\Support\Facades\Route;

// Landing page = Login
Route::get('/', Login::class)->name('login')->middleware('guest');
Route::get('/login', Login::class)->name('login')->middleware('guest');

// Password reset routes
Route::get('/forgot-password', function () {
    return redirect('/')->with('status', 'Please use the login page.');
})->middleware('guest')->name('password.request');

// Protected routes
Route::middleware(['auth'])->group(function () {
    
    // Admin dashboard - accessible to any authenticated user for testing
    Route::get('/admin', AdminDashboard::class)->name('admin.dashboard');
    
    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
