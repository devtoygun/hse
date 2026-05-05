<?php

namespace App\Http\Controllers;

use App\Services\FormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class FormController extends Controller
{
    public function __construct(
        private readonly FormService $formService
    ) {
    }

    public function index(): View
    {
        return view('app.form.index');
    }

    public function archive(): View
    {
        return view('app.form.archive');
    }

    public function create(): View
    {
        return view('app.form.new-form');
    }

    public function createSubform(): View
    {
        return view('app.form.new-subform');
    }

    public function attach(): View
    {
        return view('app.form.form-attachement');
    }

    public function list(): View
    {
        // Liste ekranini tum form kayitlariyla birlikte hazirliyoruz.
        return view('app.form.list', [
            'forms' => $this->formService->getAllForms(),
        ]);
    }

    public function create_form(Request $request): JsonResponse
    {
        // Form olusturma ekranindan gelen temel alanlari dogruluyoruz.
        $payload = $request->validate([
            'form_title' => ['required', 'string', 'max:255'],
            'form_detail' => ['nullable', 'string'],
            'annotations' => ['nullable', 'string'],
            'email_sending' => ['required', 'boolean'],
            'email_recipient_address' => ['nullable', 'email', 'max:255'],
        ], [
            'form_title.required' => 'Form basligi zorunludur.',
            'email_sending.required' => 'E-posta gonderim tercihi zorunludur.',
            'email_recipient_address.email' => 'Gecerli bir e-posta adresi giriniz.',
        ]);

        // E-posta gonderimi kapaliysa alici adresini temizliyoruz.
        if (! (bool) $payload['email_sending']) {
            $payload['email_recipient_address'] = null;
        }

        // E-posta gonderimi aciksa alici adresini zorunlu hale getiriyoruz.
        if ((bool) $payload['email_sending'] && empty($payload['email_recipient_address'])) {
            return response()->json([
                'status' => false,
                'errors' => [
                    'email_recipient_address' => ['E-posta alici adresi zorunludur.'],
                ],
            ], 422);
        }

        // Kayit isini servis katmanina birakarak controller'i sadece akis yonetiminde tutuyoruz.
        $form = $this->formService->createForm($payload, (int) $request->user()->id);

        // Basarili kayit sonrasinda istemcinin kullanabilecegi cevabi donuyoruz.
        return response()->json([
            'status' => true,
            'message' => 'Form basariyla olusturuldu.',
            'data' => [
                'id' => $form->id,
            ],
            'redirect' => route('form.index'),
        ]);
    }
}
