<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(){
        return view('layout.auth.login');
    }
    public function reset_password(){
        return view('layout.auth.reset-password');
    }
    public function screen_lock(){
        return view('layout.auth.screen-lock');
    }
}
