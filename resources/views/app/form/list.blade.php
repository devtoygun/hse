@extends('master')

@php($pageTitle = 'Tum Formlar')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <h4 class="mb-0">Tüm Formlar</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table formListTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Form Başlığı</th>
                                    <th>E-posta Gönderimi</th>
                                    <th>Alıcı E-posta Adresi</th>
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
                                        <td>{{ $form->email_recipient_address ?: '-' }}</td>
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
                                                    type="button"
                                                    class="btn btn-sm btn-icon {{ $form->status ? 'btn-label-warning' : 'btn-label-success' }}"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $form->status ? 'Pasif Yap' : 'Aktif Yap' }}">
                                                    <i class="ti {{ $form->status ? 'ti-toggle-left' : 'ti-toggle-right' }}"></i>
                                                </button>


                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger" data-bs-toggle="tooltip" title="Sil">
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
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
            },
            columnDefs: [
                // Islem kolonu icin siralama ve aramayi kapatiyoruz.
                {
                    targets: 7,
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Durum degistirme butonlarini tablo ciziminden bagimsiz sekilde yakaliyoruz.
        document.querySelector('.formListTable tbody').addEventListener('click', function (event) {
            const statusButton = event.target.closest('button[title="Pasif Yap"], button[title="Aktif Yap"]');

            if (!statusButton) {
                return;
            }

            const row = statusButton.closest('tr');

            if (!row) {
                return;
            }

            // Satirdaki temel verileri okuyup istekte kullanilacak bilgileri hazirliyoruz.
            const formId = row.querySelector('td:first-child')?.textContent.replace('#', '').trim();
            const currentStatusText = row.querySelector('td:nth-child(5) .badge')?.textContent.trim();
            const isCurrentlyActive = currentStatusText === 'Aktif';
            const nextStatus = isCurrentlyActive ? 0 : 1;
            const nextStatusLabel = isCurrentlyActive ? 'pasif' : 'aktif';

            Swal.fire({
                title: 'Emin misiniz?',
                text: 'Bu işlem kayıt altında tutulur ve geri alınamaz. Devam etmek için kendi oturum şifrenizi giriniz.',
                input: 'password',
                inputPlaceholder: 'Oturum şifrenizi giriniz',
                showCancelButton: true,
                confirmButtonText: 'Onayla ve Devam Et',
                cancelButtonText: 'Vazgeç',
                showLoaderOnConfirm: true,
                // Sifre girilmeden isleme devam edilmesini engelliyoruz.
                preConfirm: password => {
                    if (!password) {
                        Swal.showValidationMessage('Devam etmek için şifrenizi girmeniz zorunludur.');
                        return false;
                    }

                    // Backend hazir oldugunda karsilanacak istegi simdiden gonderiyoruz.
                    return axios.post('/form/set-status', {
                        form_id: formId,
                        pass: password,
                        status: nextStatus
                    })
                    .then(response => response.data)
                    .catch(error => {
                        const message =
                            error?.response?.data?.message ??
                            'İşlem sırasında bir hata oluştu.';

                        Swal.showValidationMessage(message);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(result => {
                if (!result.isConfirmed) {
                    return;
                }

                const response = result.value ?? {};

                // Backend cevabina gore kullaniciya sonuc bilgisini gosteriyoruz.
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                toast.fire({
                    icon: response.status ? 'success' : 'error',
                    title: response.message ?? `Form durumu ${nextStatusLabel} yapıldı.`
                });

                if (response.status) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            });
        });
    </script>
@endsection
