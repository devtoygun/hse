@extends('master')

@php($pageTitle = 'Tum Kullanicilar')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <h4>Kullanici Listesi</h4>
                </div>
                <div class="card-body">
                    <table class="userListTable table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Isim</th>
                                <th>E-posta</th>
                                <th>Telefon</th>
                                <th>Durum</th>
                                <th>Ilk Giris</th>
                                <th>Son Giris</th>
                                <th>Islem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td class="fw-bold">#{{ $user->id }}</td>
                                    <td><a class="text-body fw-semibold" href="/user/detail/{{ $user->id }}">{{ $user->firstname.' '.$user->lastname }}</a></td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $user->status == 'active' ? 'Aktif' : 'Pasif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $user->first_login ? 'bg-warning' : 'bg-primary' }}">
                                            {{ $user->first_login ? 'Yapilmadi' : 'Yapildi' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('H:i d/m/y') : '-' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <a href="/user/detail/{{ $user->id }}" class="btn btn-sm btn-icon btn-label-info" data-bs-toggle="tooltip" title="Goster">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-icon btn-label-primary" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" title="Duzenle">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button onclick="changePassword({{ $user->id }})" class="btn btn-sm btn-icon btn-label-warning" data-bs-toggle="tooltip" title="Sifre Degistir">
                                                <i class="ti ti-key"></i>
                                            </button>
                                            <button onclick="setUserStatus({{ $user->id }}, '{{ $user->status == 'active' ? 'passive' : 'active' }}')" class="btn btn-sm btn-icon {{ $user->status == 'active' ? 'btn-label-warning' : 'btn-label-success' }}" data-bs-toggle="tooltip" title="{{ $user->status == 'active' ? 'Pasif Yap' : 'Aktif Yap' }}">
                                                <i class="ti {{ $user->status == 'active' ? 'ti-user-off' : 'ti-user-check' }}"></i>
                                            </button>
                                            <button onclick="deleteUser({{ $user->id }})" class="btn btn-sm btn-icon btn-label-danger" data-bs-toggle="tooltip" title="Sil">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-simple">
                                        <div class="modal-content p-3 p-md-5">
                                            <div class="modal-body">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                <div class="text-center mb-4">
                                                    <h3 class="mb-2">Kullanici Duzenle</h3>
                                                    <p class="text-muted">#{{ $user->id }} ID'li kullanici bilgilerini guncelleyin.</p>
                                                </div>
                                                <form class="row g-3" onsubmit="return false">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="editFirstname{{ $user->id }}">Ad</label>
                                                        <input type="text" id="editFirstname{{ $user->id }}" class="form-control" value="{{ $user->firstname }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="editLastname{{ $user->id }}">Soyad</label>
                                                        <input type="text" id="editLastname{{ $user->id }}" class="form-control" value="{{ $user->lastname }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label" for="editEmail{{ $user->id }}">E-posta</label>
                                                        <input type="email" id="editEmail{{ $user->id }}" class="form-control" value="{{ $user->email }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label" for="editPhone{{ $user->id }}">Telefon</label>
                                                        <input type="text" id="editPhone{{ $user->id }}" class="form-control" value="{{ $user->phone }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="switch">
                                                            <input {{ $user->is_admin ? 'checked' : '' }} type="checkbox" class="switch-input" id="editIsAdmin{{ $user->id }}">
                                                            <span class="switch-toggle-slider">
                                                                <span class="switch-on"></span>
                                                                <span class="switch-off"></span>
                                                            </span>
                                                            <span class="switch-label">Yonetici yetkisi</span>
                                                        </label>
                                                    </div>
                                                    <div class="col-12 text-center">
                                                        <button type="button" onclick="updateUser({{ $user->id }})" class="btn btn-primary me-sm-3 me-1">Kaydet</button>
                                                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal">Vazgec</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.userListTable').DataTable({
            responsive: true,
            processing: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json'
            }
        });

        function requestAdminPassword(title, text, callback) {
            Swal.fire({
                title: title,
                text: text,
                input: 'password',
                inputPlaceholder: 'Kendi sifrenizi girin',
                showCancelButton: true,
                confirmButtonText: 'Onayla',
                cancelButtonText: 'Vazgec',
                preConfirm: (pass) => {
                    if (!pass) {
                        Swal.showValidationMessage('Devam etmek icin sifrenizi girmeniz gerekir.');
                    }
                    return pass;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    callback(result.value);
                }
            });
        }

        function handleUserResponse(response) {
            const data = response.data || response;
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: data.status ? 'success' : 'error',
                title: data.message || 'Islem tamamlandi.'
            });

            if (data.status && data.reload) {
                setTimeout(() => location.reload(), 700);
            }
        }

        function updateUser(userId) {
            requestAdminPassword('Kullanici guncellensin mi?', 'Bu islem kayit altina alinacak.', function (pass) {
                axios.post('/user/update-user', {
                    userid: userId,
                    firstname: $('#editFirstname' + userId).val(),
                    lastname: $('#editLastname' + userId).val(),
                    email: $('#editEmail' + userId).val(),
                    phone: $('#editPhone' + userId).val(),
                    is_admin: $('#editIsAdmin' + userId).is(':checked') ? 1 : 0,
                    pass: pass
                }).then(handleUserResponse).catch(() => {
                    Swal.fire('Hata!', 'Islem sirasinda bir sorun olustu.', 'error');
                });
            });
        }

        function changePassword(userId) {
            requestAdminPassword('Sifre degistirilsin mi?', 'Yeni gecici sifre olusturulacak ve kullanici ilk girise zorlanacak.', function (pass) {
                Swal.fire({
                    title: 'Islem Yapiliyor...',
                    text: 'Sifre guncelleniyor ve mail gonderiliyor, lutfen bekleyin.',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                axios.post('/user/change-password', {
                    userid: userId,
                    pass: pass
                }).then(handleUserResponse).catch(() => {
                    Swal.fire('Hata!', 'Islem sirasinda bir sorun olustu.', 'error');
                });
            });
        }

        function setUserStatus(userId, status) {
            requestAdminPassword('Durum guncellensin mi?', 'Kullanici ' + (status === 'active' ? 'aktif' : 'pasif') + ' yapilacak.', function (pass) {
                axios.post('/user/set-status', {
                    userid: userId,
                    status: status,
                    pass: pass
                }).then(handleUserResponse).catch(() => {
                    Swal.fire('Hata!', 'Islem sirasinda bir sorun olustu.', 'error');
                });
            });
        }

        function deleteUser(userId) {
            requestAdminPassword('Kullanici silinsin mi?', 'Bu islem geri alinamaz.', function (pass) {
                axios.post('/user/delete-user', {
                    userid: userId,
                    pass: pass
                }).then(handleUserResponse).catch(() => {
                    Swal.fire('Hata!', 'Islem sirasinda bir sorun olustu.', 'error');
                });
            });
        }
    </script>
@endsection
