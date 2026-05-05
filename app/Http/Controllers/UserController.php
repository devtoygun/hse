<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

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

    public function change_password(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'userid' => ['required', 'integer', 'exists:users,id'],
            'pass' => ['required', 'string'],
        ], [
            'userid.required' => 'Kullanici secimi zorunludur.',
            'userid.exists' => 'Kullanici bulunamadi.',
            'pass.required' => 'Yonetici sifresi zorunludur.',
        ]);

        return response()->json(
            $this->userService->changePassword((int) $payload['userid'], $payload['pass'])
        );
    }
}
