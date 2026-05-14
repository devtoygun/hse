<?php

namespace App\Http\Controllers;

use App\Services\FormService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Form;

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
        $response = $this->formService->createForm($payload, (int) $request->user()->id);

        // Basarili kayit sonrasinda istemcinin kullanabilecegi cevabi donuyoruz.
        return response()->json($response);
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
}
