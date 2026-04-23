<?php

namespace App\Services;

use App\Mail\ResetCodeMail;
use App\Models\ResetCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class AuthService
{
    private const RESET_CODE_EXPIRES_MINUTES = 15;

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
            'title' => 'Sifre Yenileme',
            'description' => 'E-posta adresinize 6 haneli dogrulama kodu gonderecegiz.',
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
            ? route('auth.reset-password') .'?email='.$credentials['email']
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

    public function sendResetCode(Request $request, array $payload): JsonResponse
    {
        $email = strtolower(trim((string) $payload['email']));
        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'Bu e-posta adresine ait kullanici bulunamadi.',
            ], 404);
        }

        if ($request->user() && strtolower((string) $request->user()->email) !== $email) {
            return response()->json([
                'status' => false,
                'message' => 'Sadece kendi e-posta adresiniz icin kod isteyebilirsiniz.',
            ], 403);
        }

        ResetCode::query()
            ->where('email', $email)
            ->where('status', 1)
            ->update(['status' => 0]);

        $code = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(self::RESET_CODE_EXPIRES_MINUTES);

        $resetCode = ResetCode::query()->create([
            'email' => $email,
            'code' => $code,
            'status' => 1,
            'expires_at' => $expiresAt,
        ]);

        $request->session()->put('password_reset.email', $email);
        $request->session()->put('password_reset.code_id', $resetCode->id);
        $request->session()->forget('password_reset.verified');

        Mail::to($email)->send(new ResetCodeMail($code, self::RESET_CODE_EXPIRES_MINUTES));

        return response()->json([
            'status' => true,
            'message' => 'Dogrulama kodu e-posta adresinize gonderildi.',
        ]);
    }

    public function verifyResetCode(Request $request, array $payload): JsonResponse
    {
        $email = strtolower(trim((string) $payload['email']));
        $otp = (string) $payload['otp'];

        $resetCode = ResetCode::query()
            ->where('email', $email)
            ->where('code', $otp)
            ->where('status', 1)
            ->where('expires_at', '>=', now())
            ->orderByDesc('id')
            ->first();

        if (! $resetCode) {
            return response()->json([
                'status' => false,
                'message' => 'Kod gecersiz veya suresi dolmus.',
            ], 422);
        }

        if ($request->user() && strtolower((string) $request->user()->email) !== $email) {
            return response()->json([
                'status' => false,
                'message' => 'Bu islem icin yetkiniz yok.',
            ], 403);
        }

        $resetCode->forceFill(['status' => 2])->save();

        $request->session()->put('password_reset.email', $email);
        $request->session()->put('password_reset.code_id', $resetCode->id);
        $request->session()->put('password_reset.verified', true);

        return response()->json([
            'status' => true,
            'message' => 'Kod dogrulandi.',
        ]);
    }

    public function setNewPassword(Request $request, array $payload): JsonResponse
    {
        $email = strtolower(trim((string) $payload['email']));

        if ($request->user() && strtolower((string) $request->user()->email) !== $email) {
            return response()->json([
                'status' => false,
                'message' => 'Bu islem icin yetkiniz yok.',
            ], 403);
        }

        $verified = (bool) $request->session()->get('password_reset.verified', false);
        $codeId = (int) $request->session()->get('password_reset.code_id', 0);
        $sessionEmail = (string) $request->session()->get('password_reset.email', '');

        if (! $verified || $codeId <= 0 || strtolower($sessionEmail) !== $email) {
            return response()->json([
                'status' => false,
                'message' => 'Once dogrulama kodunu girmeniz gerekiyor.',
            ], 422);
        }

        $resetCode = ResetCode::query()->where('id', $codeId)->first();
        if (! $resetCode || strtolower((string) $resetCode->email) !== $email || (int) $resetCode->status !== 2) {
            return response()->json([
                'status' => false,
                'message' => 'Dogrulama adimi gecersiz. Lutfen tekrar kod isteyin.',
            ], 422);
        }

        $user = User::query()->where('email', $email)->first();
        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'Bu e-posta adresine ait kullanici bulunamadi.',
            ], 404);
        }

        $user->forceFill([
            'password' => $payload['password'],
            'first_login' => 0,
        ])->save();

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status' => true,
            'message' => 'Sifreniz basariyla guncellendi. Tekrar giris yapin.',
            'redirect' => route('auth.login'),
        ]);
    }
}
