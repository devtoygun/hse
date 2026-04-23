<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    private const SUPPORTED_LOCALES = ['tr', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale');

        if (! $locale || ! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = $request->getPreferredLanguage(self::SUPPORTED_LOCALES) ?? config('app.fallback_locale', 'tr');
            $request->session()->put('locale', $locale);
        }

        App::setLocale($locale);

        return $next($request);
    }
}

