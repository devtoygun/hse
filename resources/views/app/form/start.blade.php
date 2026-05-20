@extends('master')

@section('content')

<style>

    /* ================================================== */
    /* RADIO CARD */
    /* ================================================== */
    .radio-card{
        border:2px solid #d9dee3;
        border-radius:16px;
        cursor:pointer;
        transition:0.2s ease;
        min-height:220px;

        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
    }

    /* ================================================== */
    /* HOVER */
    /* ================================================== */
    .radio-card:hover{
        border-color:#696cff;
        transform:translateY(-2px);
    }

    /* ================================================== */
    /* ACTIVE */
    /* ================================================== */
    .radio-card-input:checked + .radio-card{
        border-color:#696cff;
        background:rgba(105,108,255,0.08);
        box-shadow:0 0 0 4px rgba(105,108,255,0.15);
    }

</style>

    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title mb-2">{{ $form->form_title }}</h4>
            <p class="text-muted mb-0">{{ $form->form_detail }}</p>
        </div>
    </div>

    <div class="row">

        <!-- Vertical Icons Wizard -->
        <div class="col-12">

            <div class="bs-stepper vertical wizard-vertical-icons-example mt-2">

                <!-- ================================================== -->
                <!-- STEPPER HEADER -->
                <!-- ================================================== -->
                <div class="bs-stepper-header fixed">

                    @foreach ($form->formQuestions as $item)

                        <div class="step" data-target="#soru-{{ $item->id }}">

                            <button type="button" class="step-trigger">

                                <span class="bs-stepper-circle">
                                    <i class="ti ti-file-description"></i>
                                </span>

                                <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">
                                        Soru #{{ $item->id }}
                                    </span>

                                    <span class="bs-stepper-subtitle answer-text">
                                        Henüz cevap verilmedi
                                    </span>
                                </span>

                            </button>

                        </div>

                        @if(!$loop->last)
                            <div class="line"></div>
                        @endif

                    @endforeach

                </div>

                <!-- ================================================== -->
                <!-- STEPPER CONTENT -->
                <!-- ================================================== -->
                <div class="bs-stepper-content">

                    <form onsubmit="return false">

                        @foreach ($form->formQuestions as $item)

                            <div id="soru-{{ $item->id }}" class="content">

                                <div class="content-header mb-3">
                                    <h6 class="mb-0">
                                        Soru #{{ $item->id }}
                                    </h6>

                                    <small>
                                        Lütfen cevabınızı işaretleyin   
                                    </small>
                                </div>

                                <h4>{{ $item->question_title }}</h4>

                                <div class="row mt-4">

                                    <!-- ================================================== -->
                                    <!-- YES OPTION -->
                                    <!-- ================================================== -->
                                    <div class="col-6">

                                        <label class="w-100">

                                            <input
                                                type="radio"
                                                name="question_{{ $item->id }}"
                                                value="yes"
                                                class="d-none radio-card-input"
                                            >

                                            <div class="radio-card text-center p-5">

                                                <i class="ti ti-check fs-1 mb-3"></i>

                                                <h4 class="mb-0">
                                                    Evet
                                                </h4>

                                            </div>

                                        </label>

                                    </div>

                                    <!-- ================================================== -->
                                    <!-- NO OPTION -->
                                    <!-- ================================================== -->
                                    <div class="col-6">

                                        <label class="w-100">

                                            <input
                                                type="radio"
                                                name="question_{{ $item->id }}"
                                                value="no"
                                                class="d-none radio-card-input"
                                            >

                                            <div class="radio-card text-center p-5">

                                                <i class="ti ti-x fs-1 mb-3"></i>

                                                <h4 class="mb-0">
                                                    Hayır
                                                </h4>

                                            </div>

                                        </label>

                                    </div>

                                </div>

                                <hr>


                                 <div class="d-flex justify-content-between">

        <button
            type="button"
            class="btn btn-label-secondary btn-prev"
        >
            Geri
        </button>

        <button
            type="button"
            class="btn btn-primary btn-next"
            disabled
        >
            Devam
        </button>

    </div>


                            </div>

                        @endforeach

                    </form>

                </div>

            </div>

        </div>

    </div>











    <div class="modal" tabindex="-1" role="dialog" id="finishModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection


@section('script')
 <script>
document.addEventListener('DOMContentLoaded', function () {

    // --------------------------------------------------
    // Wizard Element
    // --------------------------------------------------
    const wizard = document.querySelector('.wizard-vertical-icons-example');

    // --------------------------------------------------
    // Init Stepper
    // --------------------------------------------------
    const stepper = new Stepper(wizard, {
        linear: true,
        animation: true
    });

    // --------------------------------------------------
    // Remove Default Active Classes
    // --------------------------------------------------
    wizard.querySelectorAll('.step').forEach(step => {
        step.classList.remove('active');
    });

    wizard.querySelectorAll('.content').forEach(content => {
        content.classList.remove('active', 'dstepper-block', 'dstepper-none');
    });

    // --------------------------------------------------
    // First Step Active
    // --------------------------------------------------
    const firstStep = wizard.querySelector('.step');

    if (firstStep) {

        firstStep.classList.add('active');

        const trigger = firstStep.querySelector('.step-trigger');

        if (trigger) {
            trigger.setAttribute('aria-selected', 'true');
        }
    }

    // --------------------------------------------------
    // First Content Active
    // --------------------------------------------------
    const firstContent = wizard.querySelector('.content');

    if (firstContent) {

        firstContent.classList.add('active');
        firstContent.classList.add('dstepper-block');

    }

    // --------------------------------------------------
    // Radio Change Event
    // --------------------------------------------------
    document.querySelectorAll('.radio-card-input').forEach(input => {

        input.addEventListener('change', function () {

            // ----------------------------------------------
            // Current Content
            // ----------------------------------------------
            const currentContent = input.closest('.content');

            // ----------------------------------------------
            // Current Question ID
            // ----------------------------------------------
            const contentId = currentContent.getAttribute('id');

            // ----------------------------------------------
            // Related Step
            // ----------------------------------------------
            const relatedStep = document.querySelector(
                `.step[data-target="#${contentId}"]`
            );

            // ----------------------------------------------
            // Subtitle Area
            // ----------------------------------------------
            const subtitle = relatedStep.querySelector('.answer-text');

            // ----------------------------------------------
            // Selected Text
            // ----------------------------------------------
            let answerText = '';

            if (input.value === 'yes') {
                answerText = 'Cevap verildi : Evet';
            }

            if (input.value === 'no') {
                answerText = 'Cevap verildi : Hayır';
            }

            // ----------------------------------------------
            // Update Subtitle
            // ----------------------------------------------
            subtitle.innerHTML = answerText;

        });

    });



   // --------------------------------------------------
// Next Buttons
// --------------------------------------------------
document.querySelectorAll('.btn-next').forEach(button => {

    button.addEventListener('click', function () {

        // ----------------------------------------------
        // Current Content
        // ----------------------------------------------
        const currentContent = button.closest('.content');

        // ----------------------------------------------
        // Checked Radio
        // ----------------------------------------------
        const checkedRadio = currentContent.querySelector(
            '.radio-card-input:checked'
        );

        // ----------------------------------------------
        // Validation
        // ----------------------------------------------
        if (!checkedRadio) {

            Swal.fire({
                icon: 'warning',
                title: 'Uyarı',
                text: 'Lütfen bir cevap seçin.'
            });

            return;
        }

        // ----------------------------------------------
        // Current Step
        // ----------------------------------------------
        const currentStep = currentContent.id;

        // ----------------------------------------------
        // All Contents
        // ----------------------------------------------
        const allContents = Array.from(
            document.querySelectorAll('.bs-stepper-content .content')
        );

        // ----------------------------------------------
        // Current Index
        // ----------------------------------------------
        const currentIndex = allContents.findIndex(content => {
            return content.id === currentStep;
        });

        // ----------------------------------------------
        // Last Step Check
        // ----------------------------------------------
        const isLastStep = currentIndex === allContents.length - 1;

        // ----------------------------------------------
        // Last Question
        // ----------------------------------------------
        if (isLastStep) {

            // ==========================================
            // START MODAL HERE
            // ==========================================
            $('#finishModal').modal('show');

            return;
        }

        // ----------------------------------------------
        // Next Step
        // ----------------------------------------------
        stepper.next();

    });

});



// --------------------------------------------------
// Radio Change Event
// --------------------------------------------------
document.querySelectorAll('.radio-card-input').forEach(input => {

    input.addEventListener('change', function () {

        // ----------------------------------------------
        // Current Content
        // ----------------------------------------------
        const currentContent = input.closest('.content');

        // ----------------------------------------------
        // Enable Next Button
        // ----------------------------------------------
        const nextButton = currentContent.querySelector('.btn-next');

        if (nextButton) {
            nextButton.disabled = false;
        }

        // ----------------------------------------------
        // Current Question ID
        // ----------------------------------------------
        const contentId = currentContent.getAttribute('id');

        // ----------------------------------------------
        // Related Step
        // ----------------------------------------------
        const relatedStep = document.querySelector(
            `.step[data-target="#${contentId}"]`
        );

        // ----------------------------------------------
        // Subtitle Area
        // ----------------------------------------------
        const subtitle = relatedStep.querySelector('.answer-text');

        // ----------------------------------------------
        // Selected Text
        // ----------------------------------------------
        let answerText = '';

        if (input.value === 'yes') {
            answerText = 'Cevap verildi : Evet';
        }

        if (input.value === 'no') {
            answerText = 'Cevap verildi : Hayır';
        }

        // ----------------------------------------------
        // Update Subtitle
        // ----------------------------------------------
        subtitle.innerHTML = answerText;

    });

});













});


</script>
@endsection