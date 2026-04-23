<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }



    public function listUsers(): View
    {
        $users = $this->userService->getAllUsers();
        return view('app.user.list-user', compact('users'));
    }

    public function listAdmins(): View
    {
        return view('app.user.list-admin');
    }

    public function create(): View
    {
        return view('app.user.new-user');
    }

    public function change_password(Request $request){
        return $this->userService->changePassword($request->userid, $request->pass);
    }
}

