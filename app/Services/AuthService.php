<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthService
{
    public function __construct(
        private readonly ActiveSessionService $activeSessionService
    ) {
    }

    public function getLoginViewData(): array
    {
        return [
            'title' => 'Giris Yap',
            'description' => 'Uygulamaya devam etmek icin hesabiniza giris yapin.',
        ];
    }

    public function getRegisterViewData(): array
    {
        return [
            'title' => 'Register',
            'description' => 'Create a new account to access the application.',
        ];
    }

    public function getResetPasswordViewData(): array
    {
        return [
            'title' => 'Reset Password',
            'description' => 'We will send a password reset link to your email address.',
        ];
    }

    public function login(Request $request, array $credentials): RedirectResponse|JsonResponse
    {
        $user = User::query()
            ->where('email', $credentials['email'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            $this->activeSessionService->recordAuditLog(
                $user,
                'Login denied because the provided credentials did not match.',
                'auth.login.invalid_credentials'
            );

            $message = 'E-posta veya sifre hatali.';

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => $message,
                ], 422);
            }

            return back()
                ->withErrors(['email' => $message])
                ->onlyInput('email');
        }

        if (strtolower((string) $user->status) !== 'active') {
            $this->activeSessionService->recordAuditLog(
                $user,
                'Login denied because the account status is not active.',
                'auth.login.inactive_user'
            );

            $message = 'Hesabiniz su anda aktif degil.';

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => $message,
                ], 403);
            }

            return back()
                ->withErrors(['email' => $message])
                ->onlyInput('email');
        }

        if ($this->activeSessionService->hasConflictingSession($user, $request)) {
            $this->activeSessionService->recordAuditLog(
                $user,
                'Login denied because another active session exists on a different device.',
                'auth.login.device_conflict'
            );

            $message = 'Baska bir cihazda aktif oturumunuz bulunuyor.';

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => $message,
                ], 409);
            }

            return back()
                ->withErrors(['email' => $message])
                ->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $this->activeSessionService->startSession($user, $request);
        $this->activeSessionService->recordAuditLog(
            $user,
            'Login completed successfully.',
            'auth.login.success'
        );

        $redirect = (int) $user->first_login === 1
            ? route('auth.reset-password')
            : route('app.index');

        if ($request->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Giris basarili.',
                'redirect' => $redirect,
            ]);
        }

        return redirect()->to($redirect);
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user) {
            $this->activeSessionService->endSession($user, $request);
            $this->activeSessionService->recordAuditLog(
                $user,
                'Logout completed successfully.',
                'auth.logout.success'
            );
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }

    public function register(Request $request, array $payload): RedirectResponse
    {
        $user = User::query()->create([
            'firstname' => $payload['firstname'],
            'lastname' => $payload['lastname'],
            'email' => $payload['email'],
            'phone' => $payload['phone'] ?? null,
            'status' => 'active',
            'password' => $payload['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $this->activeSessionService->startSession($user, $request);
        $this->activeSessionService->recordAuditLog(
            $user,
            'User registered and an authenticated session was started.',
            'auth.register.success'
        );

        return redirect()->route('app.index');
    }

    public function sendResetLink(array $payload): RedirectResponse
    {
        $status = Password::sendResetLink([
            'email' => $payload['email'],
        ]);

        if ($status !== Password::RESET_LINK_SENT) {
            return back()
                ->withErrors([
                    'email' => __($status),
                ])
                ->onlyInput('email');
        }

        return back()->with('status', __($status));
    }
}
