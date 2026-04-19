<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){
   return redirect('/app');
});

Route::controller(AuthController::class)->prefix('auth')->group(function(){
    Route::get('/login', 'login');
    Route::get('/reset-password', 'reset_password');
    Route::get('/screen-lock', 'screen_lock');

    Route::post('/login', 'confirm_login');
    Route::post('/reset-password', 'confirm_reset_password');
    Route::post('/screen-lock', 'confirm_screen_lock');
});

Route::controller(AppController::class)->prefix('app')->middleware('auth')->group(function(){
    Route::get('/', 'app');
});