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
            Route::get('/archive/{id}', 'archive_detail')->name('archive-detail');
            Route::get('/new-form', 'create')->name('create');
            Route::get('/new-subform', 'createSubform')->name('create-subform');
            Route::get('/subforms', 'subforms')->name('subforms');
            Route::get('/subform/{id}', 'subform_detail')->name('subform-detail');
            Route::get('/form-attachement', 'attach')->name('attach');
            Route::get('/list', 'list')->name('list');
            Route::get('/{id}', 'form_start');

            Route::post('/create-form', 'create_form')->name('create-form');
            Route::post('/create-subform', 'create_subform')->name('create-subform-store');
            Route::post('/save', 'save')->name('save');

            Route::post('/set-status', 'set_status')->name('set_status');
            Route::post('/delete-form', 'delete_form')->name('delete-form');

            Route::get('/detail/{id}', 'form_detail')->name('form-detail');

            Route::post('/save-question', 'save_question')->name('save-question');
            Route::post('/edit-question', 'edit_question')->name('edit-question');
            Route::post('/save-subform-question', 'save_subform_question')->name('save-subform-question');
            Route::post('/delete-subform-question', 'delete_subform_question')->name('delete-subform-question');
            Route::post('/send-approval-code', 'send_approval_code')->name('send-approval-code');
            Route::post('/verify-approval-code', 'verify_approval_code')->name('verify-approval-code');
        });

    Route::controller(UserController::class)
        ->prefix('user')
        ->name('user.')
        ->group(function () {
            Route::get('/list-user', 'listUsers')->name('list-user');
            Route::get('/list-admin', 'listAdmins')->name('list-admin');
            Route::get('/new-user', 'create')->name('create');

            Route::post('/change-password', 'change_password');
            Route::post('/update-user', 'update_user');
            Route::post('/set-status', 'set_status');
            Route::post('/delete-user', 'delete_user');
        });

    Route::controller(FacilityController::class)
        ->prefix('facility')
        ->name('facility.')
        ->group(function () {
            Route::get('/', 'listFacilities')->name('list-facility');
            Route::get('/new', 'new')->name('new-facility');
            Route::post('/new-facility', 'new_facility');
            Route::post('/new-unit', 'new_unit');

            Route::post('/delete-facility', 'delete_facility');
            Route::post('/delete-unit', 'delete_unit');

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
