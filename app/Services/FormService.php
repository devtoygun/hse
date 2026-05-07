<?php

namespace App\Services;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Auth;

use App\Models\Form;
use App\Models\FormQuestion;
use App\Models\SubForm;
//use App\Models\FormArchive;

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

    public function createForm(array $payload, int $userId)
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

        return [
            "type"    => "success",
            "message" => "Form Oluşturuldu",
            'status' => true,
            'redirect' => '/form/list'
        ];
    }

    public function setStatus($form_id, $status)
    {
        // ###########################################################
        // Form Güncelleme
        // ###########################################################

        $form = Form::find($form_id);
        $form->status = $status;
        $form->save();

        // ###########################################################
        // Response
        // ###########################################################
        DB::table('log')->insert([
            'user_id' => Auth::user()->id,
            'message' => 'Form durumu değiştirildi: '.$form->form_title,
            'code' => 'form.setStatus',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [
            "type"    => "success",
            "message" => "Durum güncellendi!",
            'status' => true,
            'reload' => true
        ];
    }

    public function deleteForm($form_id)
    {
        // ###########################################################
        // Form Questions Kontrolü
        // ###########################################################

        $hasQuestions = FormQuestion::where('form_id', $form_id)->exists();

        if ($hasQuestions) {

            return [
                "status"  => false,
                "type"    => "warning",
                "message" => "Bu forma ait sorular bulunduğu için silinemez!"
            ];
        }

        // ###########################################################
        // Sub Forms Kontrolü
        // ###########################################################

        $hasSubForms = SubForm::where('form_id', $form_id)->exists();

        if ($hasSubForms) {

            return [
                "status"  => false,
                "type"    => "warning",
                "message" => "Bu forma ait alt formlar bulunduğu için silinemez!"
            ];
        }

        // ###########################################################
        // Form Archive Kontrolü
        // ###########################################################
/*
        $hasArchives = FormArchive::where('form_id', $form_id)->exists();

        if ($hasArchives) {

            return [
                "status"  => false,
                "type"    => "warning",
                "message" => "Bu forma ait arşiv kayıtları bulunduğu için silinemez!"
            ];
        }
*/
        // ###########################################################
        // Form Silme
        // ###########################################################

        $form = Form::find($form_id);
        $form->delete();

        DB::table('log')->insert([
            'user_id' => Auth::user()->id,
            'message' => 'Form Silindi: '.$form->form_title,
            'code' => 'form.delete',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        

        // ###########################################################
        // Response
        // ###########################################################

        return [
            "status"  => true,
            "type"    => "success",
            "message" => "Form başarıyla silindi!",
            "reload"  => true
        ];
    }
}
