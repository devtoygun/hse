<?php

namespace App\Services;

use App\Models\Form;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class FormDigitalSignatureService
{
    public function encode(array $payload): string
    {
        return Crypt::encryptString(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function decode(string $signature): array
    {
        $payload = Crypt::decryptString($signature);

        return json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Builds and encodes the signed form metadata stored in form_archives.
     *
     * @param array<string, string> $answers
     * @param array<int, int|string> $approvedQuestionIds
     * @param array<int, int|string> $notifiedQuestionIds
     * @param array<string, mixed> $clientDevice
     */
    public function make(
        Form $form,
        User $user,
        array $answers,
        array $approvedQuestionIds,
        array $notifiedQuestionIds,
        array $clientDevice,
        ?string $startedAt,
        ?string $completedAt,
        ?string $timezone,
        Request $request
    ): string {
        $completed = $completedAt ? Carbon::parse($completedAt) : now();
        $started = $startedAt ? Carbon::parse($startedAt) : $completed;

        $yesCount = collect($answers)->filter(fn ($answer) => $answer === 'yes')->count();
        $noCount = collect($answers)->filter(fn ($answer) => $answer === 'no')->count();

        $payload = [
            'signed_by' => [
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'user_id' => $user->id,
            ],
            'signed_form' => [
                'id' => $form->id,
                'title' => $form->form_title,
            ],
            'filled_form' => [
                'id' => $form->id,
                'title' => $form->form_title,
            ],
            'answer_summary' => [
                'answered_question_count' => count($answers),
                'yes_answer_count' => $yesCount,
                'no_answer_count' => $noCount,
                'approval_code_question_count' => count(array_unique($approvedQuestionIds)),
                'notification_question_count' => count(array_unique($notifiedQuestionIds)),
            ],
            'device' => [
                'client' => $clientDevice,
                'server' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'accept_language' => $request->header('Accept-Language'),
                    'referer' => $request->header('Referer'),
                ],
            ],
            'timing' => [
                'started_at' => $started->toIso8601String(),
                'completed_at' => $completed->toIso8601String(),
                'duration_seconds' => max(0, $started->diffInSeconds($completed, false)),
                'timezone' => $timezone,
            ],
            'signed_at' => now()->toIso8601String(),
        ];

        return $this->encode($payload);
    }
}
