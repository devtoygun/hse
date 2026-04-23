<?php

use App\Http\Middleware\TrackActiveSessionActivity;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AppController::class)
    ->middleware(['auth', TrackActiveSessionActivity::class])
    ->group(function () {
        Route::get('/', 'index')->name('app.index');
    });

Route::controller(AuthController::class)
    ->prefix('auth')
    ->name('auth.')
    ->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'storeLogin')->name('store-login');
        Route::post('/logout', 'logout')->middleware('auth')->name('logout');
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'storeRegister')->name('store-register');
        Route::get('/reset-password', 'resetPassword')->name('reset-password');
        Route::post('/reset-password', 'storeResetPassword')->name('store-reset-password');
        Route::post('/send-reset-code', 'sendResetCode')->name('send-reset-code');
        Route::post('/verify-reset-code', 'verifyResetCode')->name('verify-reset-code');
        Route::post('/set-new-password', 'setNewPassword')->name('set-new-password');
    });
