<?php

use App\Http\Middleware\EnsureActiveSessionExists;
use App\Http\Middleware\TrackActiveSessionActivity;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', [AppController::class, 'setLocale'])->name('locale.switch');

Route::controller(AppController::class)
    ->middleware(['auth', EnsureActiveSessionExists::class, TrackActiveSessionActivity::class])
    ->group(function () {
        Route::get('/', 'index')->name('app.index');
    });

Route::middleware(['auth', EnsureActiveSessionExists::class, TrackActiveSessionActivity::class])->group(function () {
    Route::controller(FormController::class)
        ->prefix('form')
        ->name('form.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/archive', 'archive')->name('archive');
            Route::get('/new-form', 'create')->name('create');
            Route::get('/new-subform', 'createSubform')->name('create-subform');
            Route::get('/form-attachement', 'attach')->name('attach');
            Route::get('/list', 'list')->name('list');
        });

    Route::controller(UserController::class)
        ->prefix('user')
        ->name('user.')
        ->group(function () {
            Route::get('/list-user', 'listUsers')->name('list-user');
            Route::get('/list-admin', 'listAdmins')->name('list-admin');
            Route::get('/new-user', 'create')->name('create');
        });

    Route::controller(FacilityController::class)
        ->prefix('facility')
        ->name('facility.')
        ->group(function () {
            Route::get('/list-facility', 'listFacilities')->name('list-facility');
            Route::get('/new-unit', 'createUnit')->name('create-unit');
            Route::get('/new-facility', 'createFacility')->name('create-facility');
        });
});

Route::controller(AuthController::class)
    ->prefix('auth')
    ->name('auth.')
    ->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'storeLogin')->name('store-login');
        Route::get('/logout', 'logout')->middleware('auth')->name('logout-get');
        Route::post('/logout', 'logout')->middleware('auth')->name('logout');
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'storeRegister')->name('store-register');
        Route::get('/reset-password', 'resetPassword')->name('reset-password');
        Route::post('/reset-password', 'storeResetPassword')->name('store-reset-password');
        Route::post('/send-reset-code', 'sendResetCode')->name('send-reset-code');
        Route::post('/verify-reset-code', 'verifyResetCode')->name('verify-reset-code');
        Route::post('/set-new-password', 'setNewPassword')->name('set-new-password');
    });
