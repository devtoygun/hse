@extends('master')

@php($pageTitle = 'Alt Formlar')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card border">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Alt Formlar</h4>
                    <a href="{{ route('form.create-subform') }}" class="btn btn-sm btn-success">
                        <i class="ti ti-plus me-1"></i>
                        Yeni Alt Form
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table subFormsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Alt Form</th>
                                    <th>Ust Form</th>
                                    <th>Soru Sayisi</th>
                                    <th>Durum</th>
                                    <th>Olusturulma Tarihi</th>
                                    <th>Islem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subforms as $subform)
                                    <tr>
                                        <td class="fw-bold">#{{ $subform->id }}</td>
                                        <td>{{ $subform->form_title }}</td>
                                        <td>{{ $subform->form?->form_title ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-label-primary">{{ $subform->questions_count }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $subform->status ? 'bg-success' : 'bg-warning' }}">
                                                {{ $subform->status ? 'Aktif' : 'Pasif' }}
                                            </span>
                                        </td>
                                        <td>{{ $subform->created_at?->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <a
                                                href="{{ route('form.subform-detail', $subform->id) }}"
                                                class="btn btn-sm btn-icon btn-label-info"
                                                data-bs-toggle="tooltip"
                                                title="Detay">
                                                <i class="ti ti-eye"></i>
                                            </a>
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
@endsection

@section('script')
    <script>
        $('.subFormsTable').DataTable({
            responsive: true,
            processing: true,
            order: [[0, 'desc']],
            language: {
                url: '/assets/datatable/tr.json'
            }
        });
    </script>
@endsection
