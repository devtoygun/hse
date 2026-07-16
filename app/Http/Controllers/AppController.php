<?php

namespace App\Http\Controllers;

use App\Models\ActiveSession;
use App\Models\Facility;
use App\Models\Form;
use App\Models\FormArchive;
use App\Models\User;
use App\Services\FormDigitalSignatureService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    public function index(FormDigitalSignatureService $signatureService): View
    {
        $archives = FormArchive::query()
            ->with('form:id,form_title')
            ->latest()
            ->get();

        $decodedSignatures = $this->decodedSignatures($archives, $signatureService);
        $totalArchives = $archives->count();
        $weeklyCounts = $this->weeklyArchiveCounts();
        $activeSessions = ActiveSession::query()
            ->with('user:id,firstname,lastname,email')
            ->where('is_active', true)
            ->latest('last_seen_at')
            ->limit(8)
            ->get();

        return view('app.index', [
            'dashboardStats' => [
                'completed_forms' => $totalArchives,
                'users' => User::query()->count(),
                'forms' => Form::query()->count(),
                'facilities' => Facility::query()->count(),
            ],
            'formStats' => $this->formStats($archives),
            'browserStats' => $this->browserStats($decodedSignatures),
            'deviceStats' => $this->deviceStats($decodedSignatures),
            'weeklyReport' => [
                'labels' => $weeklyCounts->pluck('label')->values(),
                'series' => $weeklyCounts->pluck('count')->values(),
                'this_week' => $weeklyCounts->sum('count'),
                'this_month' => FormArchive::query()->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
                'average_duration' => $this->averageDuration($decodedSignatures),
            ],
            'activeSessions' => $activeSessions,
            'serverStatus' => $this->serverStatus(),
        ]);
    }

    public function setLocale(Request $request, string $locale): RedirectResponse
    {
        abort_unless(in_array($locale, ['tr', 'en'], true), 404);

        $request->session()->put('locale', $locale);

        return redirect()->to(url()->previous() ?: route('app.index'));
    }

    private function decodedSignatures(Collection $archives, FormDigitalSignatureService $signatureService): Collection
    {
        return $archives->map(function (FormArchive $archive) use ($signatureService) {
            try {
                return $signatureService->decode($archive->digital_signature);
            } catch (\Throwable) {
                return null;
            }
        })->filter()->values();
    }

    private function formStats(Collection $archives): Collection
    {
        $total = max(1, $archives->count());

        return $archives
            ->groupBy('form_id')
            ->map(function (Collection $group) use ($total) {
                $first = $group->first();

                return [
                    'label' => $first?->form?->form_title ?? 'Bilinmeyen Form',
                    'count' => $group->count(),
                    'percent' => round(($group->count() / $total) * 100, 1),
                ];
            })
            ->sortByDesc('count')
            ->take(5)
            ->values();
    }

    private function browserStats(Collection $signatures): Collection
    {
        return $this->distribution($signatures, fn (array $signature) => $this->detectBrowser(
            (string) data_get($signature, 'device.client.navigator.user_agent', data_get($signature, 'device.server.user_agent', ''))
        ), ['Google Chrome', 'Apple Safari', 'Mozilla Firefox', 'Opera', 'Microsoft Edge']);
    }

    private function deviceStats(Collection $signatures): Collection
    {
        return $this->distribution($signatures, fn (array $signature) => $this->detectDevice(
            (string) data_get($signature, 'device.client.navigator.user_agent', data_get($signature, 'device.server.user_agent', '')),
            (string) data_get($signature, 'device.client.navigator.platform', ''),
            (int) data_get($signature, 'device.client.navigator.max_touch_points', 0)
        ), ['Televizyon', 'Masaustu', 'Tablet', 'Telefon']);
    }

    private function distribution(Collection $items, callable $classifier, array $knownLabels): Collection
    {
        $counts = collect($knownLabels)->mapWithKeys(fn (string $label) => [$label => 0]);
        $counts['Bilinmeyen'] = 0;

        foreach ($items as $item) {
            $label = $classifier($item);
            $counts[$label] = ($counts[$label] ?? 0) + 1;
        }

        $total = max(1, $counts->sum());

        return $counts->map(fn (int $count, string $label) => [
            'label' => $label,
            'count' => $count,
            'percent' => round(($count / $total) * 100, 1),
        ])->values();
    }

    private function detectBrowser(string $userAgent): string
    {
        return match (true) {
            str_contains($userAgent, 'Edg') => 'Microsoft Edge',
            str_contains($userAgent, 'OPR') || str_contains($userAgent, 'Opera') => 'Opera',
            str_contains($userAgent, 'Firefox') => 'Mozilla Firefox',
            str_contains($userAgent, 'Chrome') || str_contains($userAgent, 'CriOS') => 'Google Chrome',
            str_contains($userAgent, 'Safari') => 'Apple Safari',
            default => 'Bilinmeyen',
        };
    }

    private function detectDevice(string $userAgent, string $platform, int $touchPoints): string
    {
        return match (true) {
            str_contains($userAgent, 'TV') || str_contains($userAgent, 'SmartTV') => 'Televizyon',
            str_contains($userAgent, 'iPad') || str_contains($userAgent, 'Tablet') => 'Tablet',
            str_contains($userAgent, 'Mobile') || str_contains($userAgent, 'Android') || str_contains($userAgent, 'iPhone') => 'Telefon',
            $platform !== '' || $touchPoints === 0 => 'Masaustu',
            default => 'Bilinmeyen',
        };
    }

    private function weeklyArchiveCounts(): Collection
    {
        return collect(range(6, 0))->map(function (int $daysAgo) {
            $date = now()->subDays($daysAgo);

            return [
                'label' => $date->locale('tr')->isoFormat('dd'),
                'count' => FormArchive::query()
                    ->whereBetween('created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
                    ->count(),
            ];
        });
    }

    private function averageDuration(Collection $signatures): int
    {
        $durations = $signatures
            ->map(fn (array $signature) => (int) data_get($signature, 'timing.duration_seconds', 0))
            ->filter(fn (int $duration) => $duration > 0);

        return (int) round($durations->avg() ?? 0);
    }

    private function serverStatus(): array
    {
        try {
            DB::select('select 1');
            $databaseStatus = 'Calisiyor';
        } catch (QueryException) {
            $databaseStatus = 'Hata';
        }

        $diskTotal = disk_total_space(base_path()) ?: 1;
        $diskFree = disk_free_space(base_path()) ?: 0;
        $diskUsedPercent = round((($diskTotal - $diskFree) / $diskTotal) * 100);

        return [
            'web_server' => request()->server('SERVER_SOFTWARE', 'Aktif'),
            'database' => $databaseStatus,
            'laravel' => app()->version(),
            'php' => PHP_VERSION,
            'cpu' => 'N/A',
            'memory' => $this->formatBytes(memory_get_usage(true)).' / '.ini_get('memory_limit'),
            'disk_used_percent' => $diskUsedPercent,
            'disk_text' => $this->formatBytes($diskTotal - $diskFree).' / '.$this->formatBytes($diskTotal),
            'bandwidth' => 'N/A',
            'active_sessions' => ActiveSession::query()->where('is_active', true)->count(),
        ];
    }

    private function formatBytes(float $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $index = 0;

        while ($bytes >= 1024 && $index < count($units) - 1) {
            $bytes /= 1024;
            $index++;
        }

        return round($bytes, 1).$units[$index];
    }
}
