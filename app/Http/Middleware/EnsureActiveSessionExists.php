<?php

namespace App\Http\Middleware;

use App\Models\ActiveSession;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSessionExists
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $hasActiveSession = ActiveSession::query()
            ->where('user_id', $user->id)
            ->where('session_id', $request->session()->getId())
            ->where('is_active', true)
            ->exists();

        if (! $hasActiveSession) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('auth.login')
                ->withErrors([
                    'email' => __('auth.session_expired'),
                ]);
        }

        return $next($request);
    }
}

