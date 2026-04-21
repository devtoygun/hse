<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    public function login(): View
    {
        return view('auth.login', $this->authService->getLoginViewData());
    }

    public function storeLogin(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        return $this->authService->login($request, $payload);
    }

    public function logout(Request $request): RedirectResponse
    {
        return $this->authService->logout($request);
    }

    public function register(): View
    {
        return view('auth.register', $this->authService->getRegisterViewData());
    }

    public function storeRegister(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        return $this->authService->register($request, $payload);
    }

    public function resetPassword(): View
    {
        return view('auth.reset-password', $this->authService->getResetPasswordViewData());
    }

    public function storeResetPassword(Request $request): RedirectResponse
    {
        $payload = $request->validate([
            'email' => ['required', 'email'],
        ]);

        return $this->authService->sendResetLink($payload);
    }
}
