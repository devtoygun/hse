@extends('master') {{-- BURAYI KENDİ LAYOUTUNA GÖRE DEĞİŞTİR --}}

@section('content')

<div class="container py-4">

    {{-- -------------------------------------------------- --}}
    {{-- Header --}}
    {{-- -------------------------------------------------- --}}
    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <h3 class="mb-0">
                            {{ $form->form_title ?? 'Form' }}
                        </h3>

                        <span id="questionCounter" class="badge bg-primary">
                            1 / {{ $form->formQuestions->count() }}
                        </span>

                    </div>

                    {{-- -------------------------------------------------- --}}
                    {{-- Questions --}}
                    {{-- -------------------------------------------------- --}}
                    @foreach($form->formQuestions as $index => $question)

                        <div
                            class="question-card {{ $index > 0 ? 'd-none' : '' }}"
                            data-index="{{ $index }}"
                            data-question-id="{{ $question->id }}"
                            data-approval-required="{{ $question->approval_required }}"
                            data-send-notification="{{ (int) $question->send_nofitication }}"
                        >

                            {{-- ---------------------------------------------- --}}
                            {{-- Badges --}}
                            {{-- ---------------------------------------------- --}}
                            <div class="mb-3">

                                @if($question->approval_required)

                                    <span class="badge bg-warning text-dark">
                                        Onay Gerekiyor
                                    </span>

                                @endif

                                @if($question->send_nofitication)

                                    <span class="badge bg-info">
                                        Bildirim Gönderilecek
                                    </span>

                                @endif

                            </div>

                            {{-- ---------------------------------------------- --}}
                            {{-- Question --}}
                            {{-- ---------------------------------------------- --}}
                            <h4 class="mb-4">
                                {{ $question->question_title }}
                            </h4>

                            {{-- ---------------------------------------------- --}}
                            {{-- Answers --}}
                            {{-- ---------------------------------------------- --}}
                            <div class="d-flex gap-4">

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="question_{{ $question->id }}"
                                        value="yes"
                                    >

                                    <label class="form-check-label">
                                        Evet
                                    </label>

                                </div>

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="question_{{ $question->id }}"
                                        value="no"
                                    >

                                    <label class="form-check-label">
                                        Hayır
                                    </label>

                                </div>

                            </div>

                        </div>

                    @endforeach

                    {{-- -------------------------------------------------- --}}
                    {{-- Navigation --}}
                    {{-- -------------------------------------------------- --}}
                    <div class="d-flex justify-content-between mt-5">

                        <button
                            type="button"
                            id="btnPrevious"
                            class="btn btn-secondary"
                        >
                            Geri
                        </button>

                        <button
                            type="button"
                            id="btnNext"
                            class="btn btn-primary"
                        >
                            Devam
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- -------------------------------------------------- --}}
{{-- Finish Modal --}}
{{-- -------------------------------------------------- --}}
<div
    class="modal fade"
    id="finishModal"
    tabindex="-1"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
>

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Form Tamamlandı
                </h5>

            </div>

            <div class="modal-body">

                Tüm sorular cevaplandı.

                İmzala butonuna basarak işlemi tamamlayabilirsiniz.

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    id="btnSaveForm"
                    class="btn btn-success"
                >
                    İmzala
                </button>

            </div>

        </div>

    </div>

</div>

@endsection

@section('script')

@php
    $subFormQuestions = $form->subForms
        ->flatMap(fn ($subForm) => $subForm->questions->map(fn ($question) => [
            'id' => $question->id,
            'subform_id' => $subForm->id,
            'subform_title' => $subForm->form_title,
            'question_title' => $question->question_title,
            'question_order' => $question->question_order,
        ]))
        ->values();
@endphp

<script>

document.addEventListener('DOMContentLoaded', function () {

    // --------------------------------------------------
    // Variables
    // --------------------------------------------------
    let currentQuestionIndex = 0;

    let answers = {};

    let subformAnswers = {};

    let subformsAsked = false;

    const subFormQuestions =
        @json($subFormQuestions);

    const formStartedAt =
        new Date();

    const cards =
        document.querySelectorAll('.question-card');

    const totalQuestions =
        cards.length;

    const counter =
        document.getElementById('questionCounter');

    const finishModal =
        new bootstrap.Modal(
            document.getElementById('finishModal'),
            {
                backdrop: 'static',
                keyboard: false
            }
        );

    // --------------------------------------------------
    // Device Info
    // --------------------------------------------------
    function getDeviceInfo()
    {
        return {
            navigator: {
                user_agent: navigator.userAgent || null,
                platform: navigator.platform || null,
                language: navigator.language || null,
                languages: navigator.languages || [],
                cookie_enabled: navigator.cookieEnabled,
                do_not_track: navigator.doNotTrack || null,
                hardware_concurrency: navigator.hardwareConcurrency || null,
                device_memory: navigator.deviceMemory || null,
                max_touch_points: navigator.maxTouchPoints || 0,
                vendor: navigator.vendor || null
            },
            screen: {
                width: window.screen?.width || null,
                height: window.screen?.height || null,
                available_width: window.screen?.availWidth || null,
                available_height: window.screen?.availHeight || null,
                color_depth: window.screen?.colorDepth || null,
                pixel_depth: window.screen?.pixelDepth || null,
                pixel_ratio: window.devicePixelRatio || null
            },
            viewport: {
                width: window.innerWidth || null,
                height: window.innerHeight || null
            },
            timezone: {
                name: Intl.DateTimeFormat().resolvedOptions().timeZone || null,
                offset_minutes: new Date().getTimezoneOffset()
            }
        };
    }

    // --------------------------------------------------
    // Toast
    // --------------------------------------------------
    function showToast(type, message)
    {
        if (typeof toastr !== 'undefined')
        {
            toastr[type](message);
            return;
        }

        alert(message);
    }

    // --------------------------------------------------
    // Update Counter
    // --------------------------------------------------
    function updateCounter()
    {
        counter.innerHTML =
            `${currentQuestionIndex + 1} / ${totalQuestions}`;
    }

    // --------------------------------------------------
    // Show Question
    // --------------------------------------------------
    function showQuestion(index)
    {
        cards.forEach(card => {
            card.classList.add('d-none');
        });

        cards[index].classList.remove('d-none');

        updateCounter();
    }

    // --------------------------------------------------
    // Sub Form Questions
    // --------------------------------------------------
    async function askSubFormQuestions()
    {
        if (subformsAsked || subFormQuestions.length === 0)
        {
            subformsAsked = true;
            return true;
        }

        for (let index = 0; index < subFormQuestions.length; index++)
        {
            const question =
                subFormQuestions[index];

            const result =
                await Swal.fire({
                    title:
                        `${question.subform_title} (${index + 1}/${subFormQuestions.length})`,

                    text:
                        question.question_title,

                    input:
                        'radio',

                    inputOptions:
                        {
                            yes: 'Evet',
                            no: 'Hayir'
                        },

                    inputValue:
                        subformAnswers[question.id] || null,

                    allowOutsideClick:
                        false,

                    allowEscapeKey:
                        false,

                    showCancelButton:
                        false,

                    confirmButtonText:
                        'Devam',

                    inputValidator:
                        (value) => {
                            if (!value)
                            {
                                return 'Lutfen bir cevap seciniz.';
                            }

                            return null;
                        }
                });

            if (!result.isConfirmed)
            {
                return false;
            }

            subformAnswers[question.id] =
                result.value;
        }

        subformsAsked = true;
        return true;
    }

    // --------------------------------------------------
    // Previous Button
    // --------------------------------------------------
    document
        .getElementById('btnPrevious')
        .addEventListener('click', function () {

            if (currentQuestionIndex <= 0)
            {
                return;
            }

            currentQuestionIndex--;

            showQuestion(currentQuestionIndex);

        });

    // --------------------------------------------------
    // Next Button
    // --------------------------------------------------
    document
        .getElementById('btnNext')
        .addEventListener('click', async function () {

            const currentCard =
                cards[currentQuestionIndex];

            const questionId =
                currentCard.dataset.questionId;

            const approvalRequired =
                currentCard.dataset.approvalRequired === '1';

            const sendNotification =
                currentCard.dataset.sendNotification === '1';

            const selectedAnswer =
                currentCard.querySelector(
                    'input[type="radio"]:checked'
                );

            if (!selectedAnswer)
            {
                showToast(
                    'warning',
                    'Lütfen bir cevap seçiniz.'
                );

                return;
            }

            const answer =
                selectedAnswer.value;

            answers[questionId] = answer;

            // ------------------------------------------
            // Notification / SMS Trigger
            // ------------------------------------------
            if (
                sendNotification ||
                (
                    approvalRequired &&
                    answer === 'yes'
                )
            )
            {
                const sendResponse =
                    await axios.post(
                        '/form/send-approval-code',
                        {
                            question_id: questionId,
                            answer: answer
                        }
                    )
                    .then(response => response.data)
                    .catch(() => ({
                        status: false,
                        message: 'İşlem başarısız.'
                    }));

                if (!sendResponse.status)
                {
                    showToast(
                        'error',
                        sendResponse.message
                    );

                    return;
                }
            }

            // ------------------------------------------
            // Approval Required
            // ------------------------------------------
            if (
                approvalRequired &&
                answer === 'yes'
            )
            {
                const result =
                    await Swal.fire({

                        title: 'Onay Kodu',

                        text:
                            'Telefonunuza gönderilen kodu giriniz.',

                        input: 'text',

                        inputPlaceholder:
                            'Onay Kodu',

                        allowOutsideClick: false,

                        allowEscapeKey: false,

                        showCancelButton: false,

                        confirmButtonText:
                            'Doğrula',

                        preConfirm: async (code) => {

                            const verifyResponse =
                                await axios.post(
                                    '/form/verify-approval-code',
                                    {
                                        question_id:
                                            questionId,
                                        code: code
                                    }
                                )
                                .then(response => response.data)
                                .catch(() => ({
                                    status: false,
                                    message:
                                        'Bağlantı hatası oluştu.'
                                }));

                            if (!verifyResponse.status)
                            {
                                Swal.showValidationMessage(
                                    verifyResponse.message ||
                                    'Kod hatalı.'
                                );

                                return false;
                            }

                            return true;

                        }

                    });

                if (!result.isConfirmed)
                {
                    return;
                }
            }

            // ------------------------------------------
            // Last Question
            // ------------------------------------------
            if (
                currentQuestionIndex >=
                totalQuestions - 1
            )
            {
                const subFormsCompleted =
                    await askSubFormQuestions();

                if (!subFormsCompleted)
                {
                    return;
                }

                finishModal.show();
                return;
            }

            // ------------------------------------------
            // Next Question
            // ------------------------------------------
            currentQuestionIndex++;

            showQuestion(currentQuestionIndex);

        });

    // --------------------------------------------------
    // Save Form
    // --------------------------------------------------
    document
        .getElementById('btnSaveForm')
        .addEventListener('click', async function () {

            const button = this;
            const originalText = button.innerHTML;

            button.disabled = true;
            button.innerHTML = 'İmzalanıyor...';

            const response =
                await axios.post(
                    '/form/save',
                    {
                        form_id:
                            {{ $form->id ?? 0 }}, // Gerekirse değiştir

                        answers:
                            answers,

                        subform_answers:
                            subformAnswers,

                        form_started_at:
                            formStartedAt.toISOString(),

                        form_completed_at:
                            new Date().toISOString(),

                        timezone:
                            Intl.DateTimeFormat().resolvedOptions().timeZone,

                        device_info:
                            getDeviceInfo()
                    }
                )
                .then(response => response.data)
                .catch(() => ({
                    status: false,
                    message:
                        'Kayıt sırasında hata oluştu.'
                }));

            if (!response.status)
            {
                button.disabled = false;
                button.innerHTML = originalText;

                showToast(
                    'error',
                    response.message
                );

                return;
            }

            Swal.fire({
                icon: 'success',
                title: 'Başarılı',
                text:
                    response.message ||
                    'Form başarıyla kaydedildi.'
            }).then(() => {

                window.location.href = '{{ route('form.index') }}';

            });

        });

});

</script>

@endsection
