<?php

use App\Http\Controllers\Auth\NewLoginController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Public routes
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

//Override the login route from Breeze
Route::post('/login', [NewLoginController::class, 'store'])
    ->middleware('guest')
    ->name('login'); // Changed from 'login.store' to 'login' to match the form's expected route

//Two-Factor authentication routes
Route::middleware(['auth'])->group(function () {
    Route::get('/two-factor', [TwoFactorController::class, 'index'])->name('two-factor.index');
    Route::post('/two-factor', [TwoFactorController::class, 'store'])->name('two-factor.store');
    Route::post('/two-factor/resend', [TwoFactorController::class, 'resend'])->name('two-factor.resend');
});

//Protected routes
Route::middleware(['auth', 'two-factor'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // CRUD routes for countries, cities, and currencies
    Route::resource('countries', CountryController::class);
    Route::resource('cities', CityController::class);
    Route::resource('currencies', CurrencyController::class);

    //Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
