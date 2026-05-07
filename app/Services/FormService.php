<?php

namespace App\Services;

use App\Models\Form;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class FormService
{
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

        // Form olusturma olayini dogrudan veritabanindaki log tablosuna kaydediyoruz.
        DB::table('log')->insert([
            'user_id' => $userId,
            'message' => 'Yeni form olusturuldu: '.$form->form_title,
            'code' => 'form.create.success',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $form;
    }

    public function setStatus($form_id, $status)
    {
        // ###########################################################
        // Form Güncelleme
        // ###########################################################

        Form::where('id', $form_id)->update([
            'status' => $status
        ]);

        // ###########################################################
        // Response
        // ###########################################################

        return [
            "type"    => "success",
            "message" => "Durum güncellendi!",
            'status' => true,
            'reload' => true
        ];
    }
}
