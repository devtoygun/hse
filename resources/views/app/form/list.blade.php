@extends('master')

@php($pageTitle = 'Tum Formlar')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card border">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="mb-0">Tüm Formlar</h4>
                    <a href="/form/new-form" class="btn btn-sm btn-success"><i class="fas fa-plus me-2"></i> Yeni Form</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table formListTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Form Başlığı</th>
                                    <th>E-posta</th>
                                    <th>Durum</th>
                                    <th>Oluşturan</th>
                                    <th>Oluşturulma Tarihi</th>
                                    <th>İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($forms as $form)
                                    <tr>
                                        <td class="fw-bold">#{{ $form->id }}</td>
                                        <td>{{ $form->form_title }}</td>
                                        <td>
                                            <span class="badge {{ $form->email_sending ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $form->email_sending ? 'Evet' : 'Hayır' }}
                                            </span>
                                        </td>
                                        
                                        <td>
                                            <span class="badge {{ $form->status ? 'bg-success' : 'bg-danger' }}">
                                                {{ $form->status ? 'Aktif' : 'Pasif' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="/user/profile/{{ $form->created_by }}" class="text-body fw-semibold">
                                                {{ $form->user ? $form->user->firstname . ' ' . $form->user->lastname : '-' }}
                                            </a>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($form->created_at)->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1 flex-nowrap">
                                                <a href="/form/detail/{{ $form->id }}" class="btn btn-sm btn-icon btn-label-info" data-bs-toggle="tooltip" title="Detay">
                                                    <i class="ti ti-eye"></i>
                                                </a>

                                                <a href="/form/edit/{{ $form->id }}" class="btn btn-sm btn-icon btn-label-primary" data-bs-toggle="tooltip" title="Düzenle">
                                                    <i class="ti ti-edit"></i>
                                                </a>

                                                <button
                                                    onclick="setStatus({{ $form->id }}, {{ $form->status }})"
                                                    type="button"
                                                    class="btn btn-sm btn-icon {{ $form->status ? 'btn-label-success' : 'btn-label-warning' }}"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $form->status ? 'Pasif Yap' : 'Aktif Yap' }}">
                                                    <i class="ti {{ $form->status ? 'ti-toggle-right' : 'ti-toggle-left' }}"></i>
                                                </button>


                                                <button 
                                                onclick="deleteForm({{ $form->id }})"
                                                type="button" 
                                                class="btn btn-sm btn-icon btn-label-danger" data-bs-toggle="tooltip" title="Sil">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </div>
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
        // Form listesini tablo ozellikleriyle daha kullanisli hale getiriyoruz.
        $('.formListTable').DataTable({
            responsive: true,
            processing: true,
            order: [[0, 'desc']],
            language: {
                url: '/assets/datatable/tr.json'
            },
    
        });

        function setStatus(id,status){
            status = status ? 0 : 1;
        
            swal.fire({
                title: "Emin misiniz?",
                text : "Bu işlem kayıt altındadır. Devam etmek için lütfen şifrenizi girin.",
                input: "password",
                confirmButtonText: "Devam Et",
                "showCancelButton": true,
                "cancelButtonText": "Vazgeç",
            }).then((res) => {
                if(res.isConfirmed){
                    fastpost('/form/set-status', {id:id,status:status,password:res.value})
                    
                }
            })
        }

        function deleteForm(id){
            alert(id)
        }
    </script>
@endsection
