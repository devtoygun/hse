<?php

namespace App\Http\Controllers;

use App\Services\FormService;
use App\Services\FormDigitalSignatureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\Form;
use App\Models\FormArchive;
use App\Models\FormArchiveAnswer;
use App\Models\FormArchiveSubFormAnswer;
use App\Models\FormQuestion;
use App\Models\SubForm;
use App\Models\SubFormQuestion;
use Throwable;

class FormController extends Controller
{
    public function __construct(
        private readonly FormService $formService,
        private readonly FormDigitalSignatureService $signatureService
    ) {
    }

    public function index(): View
    {
        return view('app.form.index', ['forms'=>Form::all()]);
    } 

    public function form_start($id){
        return view('app.form.start', [
            'form' => Form::query()
                ->with([
                    'formQuestions',
                    'subForms.questions' => fn ($query) => $query->where('status', true)->orderBy('question_order')->orderBy('id'),
                ])
                ->findOrFail($id),
        ]);
    }

    public function archive(): View
    {
        return view('app.form.archive', [
            'archives' => FormArchive::query()
                ->with(['form:id,form_title', 'user:id,firstname,lastname,email'])
                ->withCount('answers')
                ->orderByDesc('id')
                ->get(),
        ]);
    }

    public function archive_detail(int $id): View
    {
        $archive = FormArchive::query()
            ->with([
                'form:id,form_title,form_detail',
                'user:id,firstname,lastname,email',
                'answers.question:id,question_title,question_order',
                'subFormAnswers.question.subForm:id,form_id,form_title',
            ])
            ->findOrFail($id);

        try {
            $signature = $archive->decodeDigitalSignature();
        } catch (Throwable) {
            $signature = null;
        }

        return view('app.form.archive-detail', [
            'archive' => $archive,
            'signature' => $signature,
        ]);
    }

    public function create(): View
    {
        return view('app.form.new-form');
    }

    public function createSubform(): View
    {
        return view('app.form.new-subform', [
            'forms' => Form::query()
                ->where('status', true)
                ->orderBy('form_title')
                ->get(['id', 'form_title']),
        ]);
    }

    public function subforms(): View
    {
        return view('app.form.subforms', [
            'subforms' => SubForm::query()
                ->with(['form:id,form_title'])
                ->withCount('questions')
                ->orderByDesc('id')
                ->get(),
        ]);
    }

    public function subform_detail(int $id): View
    {
        return view('app.form.subform-detail', [
            'subform' => SubForm::query()
                ->with(['form:id,form_title', 'questions'])
                ->findOrFail($id),
        ]);
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
        $response = $this->formService->createForm($payload, (int) $request->user()->id);

        // Basarili kayit sonrasinda istemcinin kullanabilecegi cevabi donuyoruz.
        return response()->json($response);
    }

    public function create_subform(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'form_id' => ['required', 'integer', 'exists:forms,id'],
            'form_title' => ['required', 'string', 'max:255'],
        ], [
            'form_id.required' => 'Ust form secimi zorunludur.',
            'form_id.exists' => 'Secilen ust form bulunamadi.',
            'form_title.required' => 'Alt form basligi zorunludur.',
        ]);

        $response = $this->formService->createSubForm($payload, (int) $request->user()->id);

        return response()->json($response);
    }

    public function save_subform_question(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'subform_id' => ['required', 'integer', 'exists:sub_forms,id'],
            'title' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer'],
            'approval' => ['nullable', 'boolean'],
        ], [
            'subform_id.required' => 'Alt form bilgisi zorunludur.',
            'subform_id.exists' => 'Alt form bulunamadi.',
            'title.required' => 'Soruyu girin.',
        ]);

        $question = SubFormQuestion::query()->create([
            'subform_id' => $payload['subform_id'],
            'question_title' => trim(ucfirst($payload['title'])),
            'question_order' => $payload['order'] ?? 0,
            'question_text' => trim(ucfirst($payload['title'])),
            'approval_required' => (bool) ($payload['approval'] ?? false),
            'status' => true,
        ]);

        DB::table('log')->insert([
            'user_id' => $request->user()?->id,
            'message' => $payload['subform_id'].' ID\'li alt forma soru eklendi. Soru ID: '.$question->id,
            'code' => 'subform.add-question',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'type' => 'success',
            'message' => 'Soru eklendi',
            'status' => true,
            'reload' => true,
        ]);
    }

    public function delete_subform_question(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'question_id' => ['required', 'integer', 'exists:sub_form_questions,id'],
        ], [
            'question_id.required' => 'Soru bilgisi zorunludur.',
            'question_id.exists' => 'Soru bulunamadi.',
        ]);

        $question = SubFormQuestion::query()->findOrFail((int) $payload['question_id']);
        $questionId = $question->id;
        $subformId = $question->subform_id;
        $question->delete();

        DB::table('log')->insert([
            'user_id' => $request->user()?->id,
            'message' => $subformId.' ID\'li alt formdan soru silindi. Soru ID: '.$questionId,
            'code' => 'subform.delete-question',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'type' => 'success',
            'message' => 'Soru silindi',
            'status' => true,
            'reload' => true,
        ]);
    }


    public function set_status(Request $request){
        $form_id = $request->id;
        $status = $request->status;
        $pass = $request->password;

        if(empty($pass)){
            return response()->json(["type"=>"warning", "message" => "Şifrenizi girmelisiniz!"]);
        }

        if (!Hash::check($pass, Auth::user()->password)) {

            return response()->json([
                "type"    => "warning",
                "message" => "Şifreniz hatalı!"
            ]);
        }

        $response = $this->formService->setStatus($form_id, $status);

        return response()->json($response);

    }

    public function delete_form(Request $request){
         $form_id = $request->id;
        $pass = $request->password;

        if(empty($pass)){
            return response()->json(["type"=>"warning", "message" => "Şifrenizi girmelisiniz!"]);
        }

        if (!Hash::check($pass, Auth::user()->password)) {

            return response()->json([
                "type"    => "error",
                "message" => "Şifreniz hatalı!"
            ]);
        }


        $response = $this->formService->deleteForm($form_id);

        return response()->json($response);
    }

    public function form_detail($id){
        $form = Form::find($id);

        return view('app.form.form-detail', ['form' => $form]);
    }

    public function save_question(Request $request){
        $title = trim(ucfirst($request->title));
        $order = $request->order;
        $approval = $request->approval;
        $formid = $request->formid;

        if(empty($request->title)){
            return response()->json(["type"=>"warning","message"=>"Soruyu girin..."]);
        }

        if(empty($request->order)){
            $order = 0;
        }

        

        $response = $this->formService->saveQuestion($formid,$title,$order,$approval);
        return response()->json($response);
    }


    public function edit_question(Request $request){
         $title = trim(ucfirst($request->title));
        $order = $request->order;
        $approval = $request->approval;
        $question_id = $request->questionid;

         if(empty($request->title)){
            return response()->json(["type"=>"warning","message"=>"Soruyu girin..."]);
        }

        if(empty($request->order)){
            $order = 0;
        }

        $response = $this->formService->editQuestion($question_id,$title,$order,$approval);
        return response()->json($response);
    }

    public function send_approval_code(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'question_id' => ['required', 'integer', 'exists:form_questions,id'],
            'answer' => ['required', 'in:yes,no'],
        ], [
            'question_id.required' => 'Soru bilgisi zorunludur.',
            'question_id.exists' => 'Soru bulunamadi.',
            'answer.required' => 'Cevap zorunludur.',
        ]);

        $question = FormQuestion::query()
            ->with('form')
            ->findOrFail((int) $payload['question_id']);

        $approvalRequired = (bool) $question->approval_required && $payload['answer'] === 'yes';
        $notificationRequired = (bool) ($question->send_notification ?? $question->send_nofitication ?? false);

        if (! $approvalRequired && ! $notificationRequired) {
            return response()->json([
                'status' => true,
                'type' => 'success',
                'message' => 'Ek islem gerekli degil.',
            ]);
        }

        $user = $request->user();
        $messages = [];

        if ($approvalRequired) {
            $number = trim((string) $question->sms_receipe);

            if ($number === '') {
                return response()->json([
                    'status' => false,
                    'type' => 'warning',
                    'message' => 'SMS gonderimi icin kullanici telefon numarasi bulunamadi.',
                ], 422);
            }

            $settings = config('services.mutlucell');

            if (empty($settings['username']) || empty($settings['password']) || empty($settings['originator'])) {
                return response()->json([
                    'status' => false,
                    'type' => 'warning',
                    'message' => 'SMS servis ayarlari eksik.',
                ], 422);
            }

            $code = (string) random_int(1000, 9999);
            $name = trim($user->firstname.' '.$user->lastname) ?: $user->email;
            $message = $name.', "'.$question->question_title.'" sorusu icin onay istiyor. Onay Kodu: '.$code;
            $xmlEscape = fn ($value) => htmlspecialchars((string) $value, ENT_XML1 | ENT_COMPAT, 'UTF-8');

            $xml = sprintf(
                '<?xml version="1.0" encoding="UTF-8"?><smspack ka="%s" pwd="%s" org="%s"><mesaj><metin>%s</metin><nums>%s</nums></mesaj></smspack>',
                $xmlEscape($settings['username']),
                $xmlEscape($settings['password']),
                $xmlEscape($settings['originator']),
                $xmlEscape($message),
                $xmlEscape($number)
            );

            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'text/xml',
                ])->withBody($xml, 'text/xml')->post($settings['url']);
            } catch (Throwable) {
                return response()->json([
                    'status' => false,
                    'type' => 'error',
                    'message' => 'SMS gonderilemedi.',
                ], 502);
            }

            if (! $response->successful()) {
                return response()->json([
                    'status' => false,
                    'type' => 'error',
                    'message' => 'SMS gonderilemedi.',
                ], 502);
            }

            DB::table('approval_codes')->insert([
                'code' => $code,
                'status' => 1,
            ]);

            $request->session()->put('form_approval_codes.'.$question->id, [
                'code' => $code,
                'user_id' => $user->id,
                'question_id' => $question->id,
                'sent_at' => now()->toDateTimeString(),
            ]);

            $messages[] = 'Onay kodu SMS olarak gonderildi.';
        }

        if ($notificationRequired) {
            $settings = config('services.mutlucell');
            $form = $question->form;
             $number = trim((string) $question->sms_receipe);

            if (! $form || ! (bool) $form->email_sending || empty($form->email_recipient_address)) {
                return response()->json([
                    'status' => false,
                    'type' => 'warning',
                    'message' => 'Bildirim gonderimi icin form e-posta ayarlari eksik.',
                ], 422);
            }

            $name = trim($user->firstname.' '.$user->lastname) ?: $user->email;
            $answerText = $payload['answer'] === 'yes' ? 'Evet' : 'Hayir';


            $message = $name.', "'.$question->question_title.'" sorusunu yanıtladı. Yanıt: '.$answerText;
            $xmlEscape = fn ($value) => htmlspecialchars((string) $value, ENT_XML1 | ENT_COMPAT, 'UTF-8');

            $xml = sprintf(
                '<?xml version="1.0" encoding="UTF-8"?><smspack ka="%s" pwd="%s" org="%s"><mesaj><metin>%s</metin><nums>%s</nums></mesaj></smspack>',
                $xmlEscape($settings['username']),
                $xmlEscape($settings['password']),
                $xmlEscape($settings['originator']),
                $xmlEscape($message),
                $xmlEscape($number)
            );

            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'text/xml',
                ])->withBody($xml, 'text/xml')->post($settings['url']);
            } catch (Throwable) {
                return response()->json([
                    'status' => false,
                    'type' => 'error',
                    'message' => 'SMS gonderilemedi.',
                ], 502);
            }

            if (! $response->successful()) {
                return response()->json([
                    'status' => false,
                    'type' => 'error',
                    'message' => 'SMS gonderilemedi.',
                ], 502);
            }

            $messages[] = 'Bildirim gonderildi.';

            $request->session()->put('form_notifications.'.$question->id, [
                'user_id' => $user->id,
                'question_id' => $question->id,
                'sent_at' => now()->toDateTimeString(),
            ]);
        }

        return response()->json([
            'status' => true,
            'type' => 'success',
            'message' => implode(' ', $messages),
        ]);
    }

    public function verify_approval_code(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'question_id' => ['required', 'integer', 'exists:form_questions,id'],
            'code' => ['required', 'digits:4'],
        ], [
            'question_id.required' => 'Soru bilgisi zorunludur.',
            'question_id.exists' => 'Soru bulunamadi.',
            'code.required' => 'Onay kodu zorunludur.',
            'code.digits' => 'Onay kodu 4 haneli olmalidir.',
        ]);

        $updated = DB::table('approval_codes')
            ->where('code', (string) $payload['code'])
            ->where('status', 1)
            ->update(['status' => 0]);

        if ($updated === 0) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => 'Onay kodu hatali veya daha once kullanilmis.',
            ], 422);
        }

        $request->session()->put('form_verified_approval_codes.'.$payload['question_id'], [
            'user_id' => $request->user()?->id,
            'question_id' => (int) $payload['question_id'],
            'verified_at' => now()->toDateTimeString(),
        ]);

        return response()->json([
            'status' => true,
            'type' => 'success',
            'message' => 'Onay kodu dogrulandi.',
        ]);
    }

    public function save(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'form_id' => ['required', 'integer', 'exists:forms,id'],
            'answers' => ['required', 'array'],
            'answers.*' => ['required', 'in:yes,no'],
            'subform_answers' => ['nullable', 'array'],
            'subform_answers.*' => ['required', 'in:yes,no'],
            'form_started_at' => ['nullable', 'date'],
            'form_completed_at' => ['nullable', 'date'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'device_info' => ['nullable', 'array'],
        ], [
            'form_id.required' => 'Form bilgisi zorunludur.',
            'form_id.exists' => 'Form bulunamadi.',
            'answers.required' => 'Form cevaplari zorunludur.',
            'answers.array' => 'Form cevaplari gecersiz.',
            'answers.*.in' => 'Cevap degeri gecersiz.',
            'subform_answers.array' => 'Alt form cevaplari gecersiz.',
            'subform_answers.*.in' => 'Alt form cevap degeri gecersiz.',
        ]);

        $form = Form::query()
            ->with(['formQuestions:id,form_id', 'subForms.questions:id,subform_id'])
            ->findOrFail((int) $payload['form_id']);

        $questionIds = $form->formQuestions
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->values();

        $answerQuestionIds = collect(array_keys($payload['answers']))
            ->map(fn ($id) => (string) $id)
            ->values();

        if ($questionIds->diff($answerQuestionIds)->isNotEmpty()) {
            return response()->json([
                'status' => false,
                'type' => 'warning',
                'message' => 'Tum sorular cevaplanmadan form imzalanamaz.',
            ], 422);
        }

        if ($answerQuestionIds->diff($questionIds)->isNotEmpty()) {
            return response()->json([
                'status' => false,
                'type' => 'warning',
                'message' => 'Forma ait olmayan cevap gonderildi.',
            ], 422);
        }

        $subFormQuestionIds = $form->subForms
            ->flatMap(fn ($subForm) => $subForm->questions->pluck('id'))
            ->map(fn ($id) => (string) $id)
            ->values();

        $subFormAnswers = $payload['subform_answers'] ?? [];
        $subFormAnswerQuestionIds = collect(array_keys($subFormAnswers))
            ->map(fn ($id) => (string) $id)
            ->values();

        if ($subFormQuestionIds->diff($subFormAnswerQuestionIds)->isNotEmpty()) {
            return response()->json([
                'status' => false,
                'type' => 'warning',
                'message' => 'Tum alt form sorulari cevaplanmadan form imzalanamaz.',
            ], 422);
        }

        if ($subFormAnswerQuestionIds->diff($subFormQuestionIds)->isNotEmpty()) {
            return response()->json([
                'status' => false,
                'type' => 'warning',
                'message' => 'Forma ait olmayan alt form cevabi gonderildi.',
            ], 422);
        }

        DB::transaction(function () use ($payload, $form, $questionIds, $subFormQuestionIds, $subFormAnswers, $request) {
            $now = now();
            $userId = $request->user()?->id;
            $questionIdIntegers = $questionIds->map(fn (string $id) => (int) $id)->all();
            $approvedQuestionIds = collect($request->session()->get('form_verified_approval_codes', []))
                ->pluck('question_id')
                ->map(fn ($id) => (int) $id)
                ->intersect($questionIdIntegers)
                ->values()
                ->all();
            $notifiedQuestionIds = collect($request->session()->get('form_notifications', []))
                ->pluck('question_id')
                ->map(fn ($id) => (int) $id)
                ->intersect($questionIdIntegers)
                ->values()
                ->all();

            $archive = FormArchive::query()->create([
                'form_id' => $form->id,
                'user_id' => $userId,
                'digital_signature' => $this->signatureService->make(
                    $form,
                    $request->user(),
                    $payload['answers'],
                    $approvedQuestionIds,
                    $notifiedQuestionIds,
                    $payload['device_info'] ?? [],
                    $payload['form_started_at'] ?? null,
                    $payload['form_completed_at'] ?? null,
                    $payload['timezone'] ?? null,
                    $request
                ),
                'status' => 1,
            ]);

            $rows = $questionIds->map(function (string $questionId) use ($payload, $archive, $now) {
                return [
                    'form_archive_id' => $archive->id,
                    'form_question_id' => (int) $questionId,
                    'answer' => $payload['answers'][$questionId],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->all();

            FormArchiveAnswer::query()->insert($rows);

            $subFormRows = $subFormQuestionIds->map(function (string $questionId) use ($subFormAnswers, $archive, $now) {
                return [
                    'form_archive_id' => $archive->id,
                    'sub_form_question_id' => (int) $questionId,
                    'answer' => $subFormAnswers[$questionId],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->all();

            if ($subFormRows !== []) {
                FormArchiveSubFormAnswer::query()->insert($subFormRows);
            }

            foreach ($questionIds as $questionId) {
                $request->session()->forget('form_verified_approval_codes.'.$questionId);
                $request->session()->forget('form_notifications.'.$questionId);
                $request->session()->forget('form_approval_codes.'.$questionId);
            }
        });

        return response()->json([
            'status' => true,
            'type' => 'success',
            'message' => 'Form imzalandi ve arsive kaydedildi.',
        ]);
    }
}
