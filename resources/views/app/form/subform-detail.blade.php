@extends('master')

@php($pageTitle = 'Alt Form Detay')

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $subform->form_title }}</h4>
                    <a href="{{ route('form.subforms') }}" class="btn btn-sm btn-label-secondary">
                        Geri
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3">Alt Form</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ID
                            <span class="badge bg-secondary">#{{ $subform->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Durum
                            <span class="badge {{ $subform->status ? 'bg-success' : 'bg-warning' }}">
                                {{ $subform->status ? 'Aktif' : 'Pasif' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Soru Sayisi
                            <span class="badge bg-primary">{{ $subform->questions->count() }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3">Ust Form</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Form ID
                            <span class="badge bg-secondary">#{{ $subform->form_id }}</span>
                        </li>
                        <li class="list-group-item">
                            <div class="fw-semibold mb-1">Form Adi</div>
                            <div>{{ $subform->form?->form_title ?? '-' }}</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sorular</h5>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#subformQuestionModal">
                        <i class="ti ti-plus me-1"></i>
                        Soru Ekle
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm subFormQuestionsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Soru</th>
                                    <th>Sira</th>
                                    <th>Onay</th>
                                    <th>Durum</th>
                                    <th>Islem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subform->questions->sortBy([fn ($a, $b) => ($a->question_order == 0 && $b->question_order == 0) ? $a->id <=> $b->id : $a->question_order <=> $b->question_order]) as $question)
                                    <tr>
                                        <td class="fw-bold">#{{ $question->id }}</td>
                                        <td>{{ $question->question_title }}</td>
                                        <td>{{ $question->question_order }}</td>
                                        <td>
                                            <span class="badge {{ $question->approval_required ? 'bg-label-primary' : 'bg-label-info' }}">
                                                {{ $question->approval_required ? 'Evet' : 'Hayir' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $question->status ? 'bg-label-success' : 'bg-label-warning' }}">
                                                {{ $question->status ? 'Aktif' : 'Pasif' }}
                                            </span>
                                        </td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-icon btn-label-danger"
                                                onclick="deleteSubformQuestion({{ $question->id }})"
                                                data-bs-toggle="tooltip"
                                                title="Sil">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="subformQuestionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Yeni Soru Ekle</h3>
                        <p class="text-muted">Eklediginiz soru alt formda goruntulenecek.</p>
                    </div>
                    <form class="row g-3" onsubmit="return false">
                        <div class="col-12">
                            <label class="form-label w-100" for="modalSubformQuestion">Soru</label>
                            <input id="modalSubformQuestion" class="form-control" type="text">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="modalSubformQuestionOrder">Soru Sirasi</label>
                            <input type="number" id="modalSubformQuestionOrder" class="form-control" value="0">
                        </div>
                        <div class="col-12">
                            <label class="switch">
                                <input type="checkbox" class="switch-input" id="subformApprovalRequired">
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                                <span class="switch-label">Bu soru icin onay gereksin mi?</span>
                            </label>
                        </div>
                        <div class="col-12 text-center">
                            <button type="button" onclick="addSubformQuestion({{ $subform->id }})" class="btn btn-primary me-sm-3 me-1">Kaydet</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal">Vazgec</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.subFormQuestionsTable').DataTable({
            responsive: true,
            processing: true,
            order: [[2, 'asc']],
            language: {
                url: '/assets/datatable/tr.json'
            }
        });

        function addSubformQuestion(subformId) {
            fastpost('/form/save-subform-question', {
                subform_id: subformId,
                title: $('#modalSubformQuestion').val(),
                order: $('#modalSubformQuestionOrder').val() || 0,
                approval: $('#subformApprovalRequired').is(':checked') ? 1 : 0
            });
        }

        function deleteSubformQuestion(questionId) {
            Swal.fire({
                title: 'Emin misiniz?',
                text: 'Bu soru silinecek.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sil',
                cancelButtonText: 'Vazgec'
            }).then((result) => {
                if (result.isConfirmed) {
                    fastpost('/form/delete-subform-question', {
                        question_id: questionId
                    });
                }
            });
        }
    </script>
@endsection
