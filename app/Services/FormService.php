<?php

namespace App\Services;

use App\Models\Form;
use Illuminate\Database\Eloquent\Collection;

class FormService
{
    public function __construct(
        private readonly ActiveSessionService $activeSessionService
    ) {
    }

    public function getAllForms(): Collection
    {
        // Liste ekraninda kullanici bilgisine ek sorgu atmamak icin iliskiyi onceden yukluyoruz.
        return Form::query()
            ->with(['user:id,firstname,lastname'])
            ->orderByDesc('id')
            ->get();
    }

    public function createForm(array $payload, int $userId): Form
    {
        // Form kaydini tek noktadan olusturarak controller'i sade tutuyoruz.
        $form = Form::query()->create([
            'form_title' => $payload['form_title'],
            'form_detail' => $payload['form_detail'] ?? null,
            'annotations' => $payload['annotations'] ?? null,
            'email_sending' => (bool) $payload['email_sending'],
            'email_recipient_address' => $payload['email_recipient_address'] ?? null,
            'status' => true,
            'created_by' => $userId,
        ]);

        // Form olusturma olayini loglayarak sonradan takip edilebilir hale getiriyoruz.
        $this->activeSessionService->recordAuditLog(
            $form->creator,
            'Yeni form olusturuldu: '.$form->form_title,
            'form.create.success'
        );

        return $form;
    }
}
