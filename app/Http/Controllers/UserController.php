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

    public function update_user(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'userid' => ['required', 'integer', 'exists:users,id'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$request->input('userid')],
            'phone' => ['nullable', 'string', 'max:255'],
            'is_admin' => ['required', 'boolean'],
            'pass' => ['required', 'string'],
        ], [
            'userid.required' => 'Kullanici secimi zorunludur.',
            'userid.exists' => 'Kullanici bulunamadi.',
            'firstname.required' => 'Ad zorunludur.',
            'lastname.required' => 'Soyad zorunludur.',
            'email.required' => 'E-posta zorunludur.',
            'email.email' => 'Gecerli bir e-posta adresi girin.',
            'email.unique' => 'Bu e-posta adresi baska bir kullaniciya ait.',
            'pass.required' => 'Yonetici sifresi zorunludur.',
        ]);

        return response()->json(
            $this->userService->updateUser((int) $payload['userid'], $payload, $payload['pass'])
        );
    }

    public function set_status(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'userid' => ['required', 'integer', 'exists:users,id'],
            'status' => ['required', 'in:active,passive'],
            'pass' => ['required', 'string'],
        ], [
            'userid.required' => 'Kullanici secimi zorunludur.',
            'userid.exists' => 'Kullanici bulunamadi.',
            'status.required' => 'Durum zorunludur.',
            'pass.required' => 'Yonetici sifresi zorunludur.',
        ]);

        return response()->json(
            $this->userService->setStatus((int) $payload['userid'], $payload['status'], $payload['pass'])
        );
    }

    public function delete_user(Request $request): JsonResponse
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
            $this->userService->deleteUser((int) $payload['userid'], $payload['pass'])
        );
    }
}
