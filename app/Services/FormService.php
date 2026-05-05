<?php

namespace App\Services;

use App\Models\Form;

class FormService
{
    public function createForm(array $payload, int $userId): Form
    {
        // Form kaydini tek noktadan olusturarak controller'i sade tutuyoruz.
        return Form::query()->create([
            'form_title' => $payload['form_title'],
            'form_detail' => $payload['form_detail'] ?? null,
            'annotations' => $payload['annotations'] ?? null,
            'email_sending' => (bool) $payload['email_sending'],
            'email_recipient_address' => $payload['email_recipient_address'] ?? null,
            'status' => true,
            'created_by' => $userId,
        ]);
    }
}
