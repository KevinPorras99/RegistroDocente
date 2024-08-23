<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HomeController;

// Vista por defecto redirigida a login
Route::get('/', function () {
    return redirect()->route('login.index');
})->middleware('guest');

Route::get('/login', [SessionController::class, 'create'])->middleware('guest')->name('login.index');
Route::post('/login', [SessionController::class, 'store'])->name('login.store');
Route::get('/logout', [SessionController::class, 'destroy'])->middleware('auth')->name('login.destroy');
Route::get('/forgot-password', [SessionController::class, 'forgotPasswordView'])->name('password.request');
Route::post('/forgot-password', [SessionController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password/{token}', [SessionController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [SessionController::class, 'reset'])->name('password.update');
Route::get('/register', [RegisterController::class, 'create'])->middleware('guest')->name('register.index');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/home', [SessionController::class, 'home'])->middleware('auth')->name('home');

Route::post('/profile/upload', [SessionController::class, 'uploadProfileImage'])->name('profile.upload');
Route::delete('/profile/delete', [SessionController::class, 'deleteProfileImage'])->name('profile.delete');

// Página de inicio (home) después de autenticarse
//Route::get('/home', function () {
//    return view('home');
//})->middleware('auth')->name('home');
