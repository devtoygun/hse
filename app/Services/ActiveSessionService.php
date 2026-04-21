<?php

namespace App\Services;

use App\Models\ActiveSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ActiveSessionService
{
    public function hasConflictingSession(User $user, Request $request): bool
    {
        $this->expireStaleSessions($user);

        $fingerprint = $this->buildDeviceMetadata($request)['device_fingerprint'];

        return ActiveSession::query()
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->where('device_fingerprint', '!=', $fingerprint)
            ->exists();
    }

    public function startSession(User $user, Request $request): void
    {
        $metadata = $this->buildDeviceMetadata($request);
        $now = now();

        ActiveSession::query()
            ->where('user_id', $user->id)
            ->where('device_fingerprint', $metadata['device_fingerprint'])
            ->where('is_active', true)
            ->update([
                'session_id' => $request->session()->getId(),
                'last_seen_at' => $now,
                'ip_address' => $metadata['ip_address'],
                'user_agent' => $metadata['user_agent'],
                'browser_name' => $metadata['browser_name'],
                'browser_version' => $metadata['browser_version'],
                'operating_system' => $metadata['operating_system'],
                'device_type' => $metadata['device_type'],
                'device_family' => $metadata['device_family'],
                'logged_out_at' => null,
            ]);

        ActiveSession::query()->firstOrCreate(
            [
                'user_id' => $user->id,
                'session_id' => $request->session()->getId(),
            ],
            [
                ...$metadata,
                'is_active' => true,
                'logged_in_at' => $now,
                'last_seen_at' => $now,
                'active_duration_seconds' => 0,
            ]
        );

        $user->forceFill([
            'last_login_at' => $now,
        ])->save();
    }

    public function touchCurrentSession(Request $request): void
    {
        $user = $request->user();

        if (! $user) {
            return;
        }

        $activeSession = ActiveSession::query()
            ->where('user_id', $user->id)
            ->where('session_id', $request->session()->getId())
            ->where('is_active', true)
            ->first();

        if (! $activeSession) {
            $this->startSession($user, $request);

            return;
        }

        $now = now();
        $lastSeenAt = $activeSession->last_seen_at ?? $activeSession->logged_in_at ?? $now;
        $elapsedSeconds = max(0, Carbon::parse($lastSeenAt)->diffInSeconds($now));

        $activeSession->forceFill([
            'last_seen_at' => $now,
            'active_duration_seconds' => $activeSession->active_duration_seconds + $elapsedSeconds,
        ])->save();
    }

    public function endSession(User $user, Request $request): void
    {
        $activeSession = ActiveSession::query()
            ->where('user_id', $user->id)
            ->where('session_id', $request->session()->getId())
            ->where('is_active', true)
            ->first();

        if (! $activeSession) {
            return;
        }

        $now = now();
        $lastSeenAt = $activeSession->last_seen_at ?? $activeSession->logged_in_at ?? $now;
        $elapsedSeconds = max(0, Carbon::parse($lastSeenAt)->diffInSeconds($now));

        $activeSession->forceFill([
            'is_active' => false,
            'last_seen_at' => $now,
            'logged_out_at' => $now,
            'active_duration_seconds' => $activeSession->active_duration_seconds + $elapsedSeconds,
        ])->save();
    }

    public function expireStaleSessions(User $user): void
    {
        $cutoff = now()->subMinutes((int) config('session.lifetime', 120));

        ActiveSession::query()
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->where('last_seen_at', '<', $cutoff)
            ->update([
                'is_active' => false,
                'logged_out_at' => $cutoff,
            ]);
    }

    public function recordAuditLog(?User $user, string $message, string $code): void
    {
        DB::table('log')->insert([
            'user_id' => $user?->id,
            'message' => $message,
            'code' => $code,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function buildDeviceMetadata(Request $request): array
    {
        $userAgent = (string) $request->userAgent();
        $browser = $this->detectBrowser($userAgent);
        $operatingSystem = $this->detectOperatingSystem($userAgent);
        $deviceType = $this->detectDeviceType($userAgent);
        $deviceFamily = $this->detectDeviceFamily($userAgent, $operatingSystem, $deviceType);

        return [
            'user_agent' => $userAgent,
            'browser_name' => $browser['name'],
            'browser_version' => $browser['version'],
            'operating_system' => $operatingSystem,
            'device_type' => $deviceType,
            'device_family' => $deviceFamily,
            'device_fingerprint' => hash('sha256', implode('|', [
                $userAgent,
                $browser['name'],
                $browser['version'] ?? 'unknown',
                $operatingSystem,
                $deviceType,
            ])),
            'ip_address' => $request->ip(),
        ];
    }

    private function detectBrowser(string $userAgent): array
    {
        $browsers = [
            'Edge' => '/Edg\/([0-9\.]+)/',
            'Opera' => '/OPR\/([0-9\.]+)/',
            'Chrome' => '/Chrome\/([0-9\.]+)/',
            'Firefox' => '/Firefox\/([0-9\.]+)/',
            'Safari' => '/Version\/([0-9\.]+).*Safari/',
            'Internet Explorer' => '/(?:MSIE |rv:)([0-9\.]+)/',
        ];

        foreach ($browsers as $name => $pattern) {
            if (preg_match($pattern, $userAgent, $matches) === 1) {
                return [
                    'name' => $name,
                    'version' => $matches[1] ?? null,
                ];
            }
        }

        return [
            'name' => 'Unknown',
            'version' => null,
        ];
    }

    private function detectOperatingSystem(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'Windows') => 'Windows',
            str_contains($userAgent, 'Mac OS X') => 'macOS',
            str_contains($userAgent, 'Android') => 'Android',
            str_contains($userAgent, 'iPhone'),
            str_contains($userAgent, 'iPad') => 'iOS',
            str_contains($userAgent, 'Linux') => 'Linux',
            default => 'Unknown',
        };
    }

    private function detectDeviceType(string $userAgent): string
    {
        return match (true) {
            preg_match('/iPad|Tablet|PlayBook|Silk/i', $userAgent) === 1 => 'tablet',
            preg_match('/Mobile|iPhone|Android/i', $userAgent) === 1 => 'mobile',
            preg_match('/bot|crawl|spider/i', $userAgent) === 1 => 'bot',
            default => 'desktop',
        };
    }

    private function detectDeviceFamily(string $userAgent, string $operatingSystem, string $deviceType): string
    {
        return match (true) {
            str_contains($userAgent, 'iPhone') => 'iPhone',
            str_contains($userAgent, 'iPad') => 'iPad',
            str_contains($userAgent, 'Android') && $deviceType === 'tablet' => 'Android Tablet',
            str_contains($userAgent, 'Android') => 'Android Phone',
            $deviceType === 'desktop' && $operatingSystem === 'Windows' => 'Windows PC',
            $deviceType === 'desktop' && $operatingSystem === 'macOS' => 'Mac',
            $deviceType === 'desktop' && $operatingSystem === 'Linux' => 'Linux PC',
            default => ucfirst($deviceType),
        };
    }
}
