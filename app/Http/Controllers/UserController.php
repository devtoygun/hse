<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class UserController extends Controller
{
    public function listUsers(): View
    {
        return view('app.user.list-user');
    }

    public function listAdmins(): View
    {
        return view('app.user.list-admin');
    }

    public function create(): View
    {
        return view('app.user.new-user');
    }
}

