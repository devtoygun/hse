<?php

namespace App\Http\Middleware;

use App\Services\ActiveSessionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackActiveSessionActivity
{
    public function __construct(
        private readonly ActiveSessionService $activeSessionService
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $this->activeSessionService->touchCurrentSession($request);

        return $response;
    }
}
