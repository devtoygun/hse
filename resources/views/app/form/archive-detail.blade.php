@extends('master')

@php($pageTitle = 'Arsiv Detay')

@section('content')
    <style>
        .digital-signature-text {
            font-size: 8px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        @media print {
            .no-print,
            .dataTables_length,
            .dataTables_filter,
            .dataTables_info,
            .dataTables_paginate {
                display: none !important;
            }

            .card {
                border: 1px solid #d9dee3 !important;
                box-shadow: none !important;
                break-inside: avoid;
            }
        }
    </style>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Arsiv Kaydi #{{ $archive->id }}</h4>
                    <div class="d-flex align-items-center gap-2 no-print">
                        <button type="button" class="btn btn-sm btn-label-primary" onclick="window.print()">
                            <i class="ti ti-printer me-1"></i>
                            Yazdir
                        </button>
                        <a href="{{ route('form.archive') }}" class="btn btn-sm btn-label-secondary">
                            Geri
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3">Form</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Form ID
                            <span class="badge bg-secondary">#{{ $archive->form_id }}</span>
                        </li>
                        <li class="list-group-item">
                            <div class="fw-semibold mb-1">Form Adi</div>
                            <div>{{ $archive->form?->form_title ?? '-' }}</div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Cevap Sayisi
                            <span class="badge bg-primary">{{ $archive->answers->count() }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3">Dolduran</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            User ID
                            <span class="badge bg-secondary">#{{ $archive->user_id }}</span>
                        </li>
                        <li class="list-group-item">
                            <div class="fw-semibold mb-1">Kisi</div>
                            <div>
                                @if ($archive->user)
                                    {{ trim($archive->user->firstname.' '.$archive->user->lastname) ?: $archive->user->email }}
                                @else
                                    -
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Durum
                            <span class="badge {{ $archive->status ? 'bg-success' : 'bg-warning' }}">
                                {{ $archive->status ? 'Aktif' : 'Pasif' }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3">Imza</h5>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="fw-semibold mb-1">Baslangic</div>
                            <div>{{ data_get($signature, 'timing.started_at', '-') }}</div>
                        </li>
                        <li class="list-group-item">
                            <div class="fw-semibold mb-1">Tamamlanma</div>
                            <div>{{ data_get($signature, 'timing.completed_at', $archive->created_at?->format('d.m.Y H:i')) }}</div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Sure
                            <span class="badge bg-info">
                                {{ data_get($signature, 'timing.duration_seconds', 0) }} sn
                            </span>
                        </li>
                        <li class="list-group-item">
                            <div class="fw-semibold mb-1">Timezone</div>
                            <div>{{ data_get($signature, 'timing.timezone', '-') }}</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Verilen Yanitlar</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm archiveAnswersTable">
                            <thead>
                                <tr>
                                    <th>Soru ID</th>
                                    <th>Soru</th>
                                    <th>Cevap</th>
                                    <th>Sira</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($archive->answers->sortBy(fn ($answer) => $answer->question?->question_order ?? 0) as $answer)
                                    <tr>
                                        <td class="fw-bold">#{{ $answer->form_question_id }}</td>
                                        <td>{{ $answer->question?->question_title ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $answer->answer === 'yes' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $answer->answer === 'yes' ? 'Evet' : 'Hayir' }}
                                            </span>
                                        </td>
                                        <td>{{ $answer->question?->question_order ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Cevap Ozeti</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Toplam
                            <span class="badge bg-primary">{{ data_get($signature, 'answer_summary.answered_question_count', $archive->answers->count()) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Evet
                            <span class="badge bg-success">{{ data_get($signature, 'answer_summary.yes_answer_count', $archive->answers->where('answer', 'yes')->count()) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Hayir
                            <span class="badge bg-danger">{{ data_get($signature, 'answer_summary.no_answer_count', $archive->answers->where('answer', 'no')->count()) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Onay Kodu
                            <span class="badge bg-warning">{{ data_get($signature, 'answer_summary.approval_code_question_count', 0) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Bildirim
                            <span class="badge bg-info">{{ data_get($signature, 'answer_summary.notification_question_count', 0) }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Cihaz Bilgileri</h5>
                </div>
                <div class="card-body">
                    @if ($signature)
                        <div class="mb-3">
                            <div class="fw-semibold mb-2">Tarayici</div>
                            <ul class="list-group list-group-flush border rounded">
                                <li class="list-group-item">
                                    <div class="text-muted small">User Agent</div>
                                    <div class="small text-break">{{ data_get($signature, 'device.client.navigator.user_agent', data_get($signature, 'device.server.user_agent', '-')) }}</div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between gap-3">
                                    <span>Platform</span>
                                    <span class="fw-semibold text-end">{{ data_get($signature, 'device.client.navigator.platform', '-') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between gap-3">
                                    <span>Dil</span>
                                    <span class="fw-semibold text-end">{{ data_get($signature, 'device.client.navigator.language', '-') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center gap-3">
                                    <span>Cookie</span>
                                    <span class="badge {{ data_get($signature, 'device.client.navigator.cookie_enabled') ? 'bg-success' : 'bg-secondary' }}">
                                        {{ data_get($signature, 'device.client.navigator.cookie_enabled') ? 'Acik' : 'Kapali' }}
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between gap-3">
                                    <span>Dokunmatik Nokta</span>
                                    <span class="fw-semibold text-end">{{ data_get($signature, 'device.client.navigator.max_touch_points', 0) }}</span>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <div class="fw-semibold mb-2">Baglanti</div>
                            <ul class="list-group list-group-flush border rounded">
                                <li class="list-group-item d-flex justify-content-between gap-3">
                                    <span>IP</span>
                                    <span class="fw-semibold text-end">{{ data_get($signature, 'device.server.ip', '-') }}</span>
                                </li>
                                <li class="list-group-item">
                                    <div class="text-muted small">Accept Language</div>
                                    <div class="small text-break">{{ data_get($signature, 'device.server.accept_language', '-') }}</div>
                                </li>
                                <li class="list-group-item">
                                    <div class="text-muted small">Referer</div>
                                    <div class="small text-break">{{ data_get($signature, 'device.server.referer', '-') }}</div>
                                </li>
                            </ul>
                        </div>
                    @else
                        <p class="text-muted mb-0">Dijital imza cozulemedi veya kayit bulunamadi.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($archive->subFormAnswers->isNotEmpty())
        <div class="row g-4 mt-1">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Doldurulan Alt Formlar</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($archive->subFormAnswers->groupBy(fn ($answer) => $answer->question?->subForm?->id ?? 0) as $subFormAnswers)
                            @php($subForm = $subFormAnswers->first()?->question?->subForm)
                            <div class="{{ $loop->last ? '' : 'mb-4' }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">{{ $subForm?->form_title ?? 'Alt Form' }}</h6>
                                    <span class="badge bg-label-primary">{{ $subFormAnswers->count() }} cevap</span>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Soru ID</th>
                                                <th>Soru</th>
                                                <th>Cevap</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($subFormAnswers->sortBy(fn ($answer) => $answer->question?->question_order ?? 0) as $answer)
                                                <tr>
                                                    <td class="fw-bold">#{{ $answer->sub_form_question_id }}</td>
                                                    <td>{{ $answer->question?->question_title ?? '-' }}</td>
                                                    <td>
                                                        <span class="badge {{ $answer->answer === 'yes' ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $answer->answer === 'yes' ? 'Evet' : 'Hayir' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4 mt-1">
        <div class="col-12">
            <div class="card">
                    <h5 class="card-header mb-0" style="font-size:8px">Bu belge dijital olarak imzalanmistir.</h5>
              
                <div class="card-body">
                    <div class="small text-muted digital-signature-text" style="font-size:6px">
                        {{ $archive->digital_signature ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.archiveAnswersTable').DataTable({
            responsive: true,
            processing: true,
            order: [[3, 'asc']],
            language: {
                url: '/assets/datatable/tr.json'
            }
        });
    </script>
@endsection
