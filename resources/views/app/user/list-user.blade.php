@extends('master')

@php($pageTitle = 'Tum Kullanicilar')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <!-- Ajax Sourced Server-side -->
             <div class="card border">
                <div class="card-header">
                    <h4>Kullanıcı Listesi</h4>
                </div>
                <div class="card-body">
                     <table class="userListTable table">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>İsim</th>
                        <th>E-posta</th>
                        <th>Telefon</th>
                        <th>Durum</th>
                        <th>İlk Giriş</th>
                        <th>Son Giriş</th>
                        <th>İşlem</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="fw-bold">#{{$user->id}}</td>
                                <td><a class="text-white" href="/user/detail/{{ $user->id }}">{{$user->firstname.' '.$user->lastname}}</a></td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->phone}}</td>
                                <td><span class="badge {{ $user->status == 'active' ? 'bg-success':'bg-danger' }}">{{$user->status}}</span></td>
                                <td><span class="badge {{ $user->first_login ? 'bg-warning':'bg-primary' }}">{{$user->first_login ? 'Yapılmadı':'Yapıldı'}}</span></td>

                                <td>{{ \Carbon\Carbon::parse($user->last_login_at)->format('H:i d/m/y') }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
        <a href="/user/detail/{{ $user->id }}" class="btn btn-sm btn-icon btn-label-info" data-bs-toggle="tooltip" title="Göster">
            <i class="ti ti-eye"></i>
        </a>

        <a href="/user/edit/{{ $user->id }}" class="btn btn-sm btn-icon btn-label-primary" data-bs-toggle="tooltip" title="Düzenle">
            <i class="ti ti-edit"></i>
        </a>

        <button onclick="changePassword({{ $user->id }})" class="btn btn-sm btn-icon btn-label-warning" data-bs-toggle="tooltip" title="Şifre Değiştir">
            <i class="ti ti-key"></i>
        </button>

        <button class="btn btn-sm btn-icon {{ $user->status == 'active' ? 'btn-label-warning' : 'btn-label-success' }}" data-bs-toggle="tooltip" title="{{ $user->status == 'active' ? 'Pasif Yap' : 'Aktif Yap' }}">
            <i class="ti {{ $user->status == 'active' ? 'ti-user-off' : 'ti-user-check' }}"></i>
        </button>

        <button class="btn btn-sm btn-icon btn-label-danger" data-bs-toggle="tooltip" title="Sil">
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
@endsection

@section('script')
    <script>
        // 2. Şimdi senin bildiğin o düz mantıkla başlat
    $('.userListTable').DataTable({
        responsive: true,
        processing: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/tr.json' // Tabloyu Türkçe yapalım
        },
    });


    function changePassword(userId) {
    swal.fire({
        title: "Emin misin?",
        text: "Bu işlem geri alınamaz ve kayıt altında tutulur. Devam edebilmek için şifreni gir.",
        input: 'password',
        inputPlaceholder: "Kendi şifrenizi girin",
        showCancelButton: true,
        confirmButtonText: "Onayla ve Devam et",
        cancelButtonText: "Vazgeç",
        showLoaderOnConfirm: true, // Spinner'ı aktif eder
        preConfirm: (pass) => {
            if (!pass) {
                Swal.showValidationMessage('Devam etmek için şifrenizi girmeniz şart!');
            }
            return pass;
        },
        allowOutsideClick: () => !Swal.isLoading() // Yükleme sırasında dışarı tıklayıp kapatmayı engeller
    }).then((result) => {
        if (result.isConfirmed) {
            const pass = result.value;

            // SweetAlert'i yükleme moduna sok (Manuel spinner dondurma)
            Swal.fire({
                title: 'İşlem Yapılıyor...',
                text: 'Şifre güncelleniyor ve mail gönderiliyor, lütfen bekleyin.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post('/user/change-password', {
                userid: userId,
                pass: pass
            })
            .then((res) => {
                // Başarılı ise Toast göster
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: res.data.status ? 'success' : 'error',
                    title: res.data.message
                });
            })
            .catch((err) => {
                Swal.fire('Hata!', 'İşlem sırasında bir sorun oluştu.', 'error');
            });
        }
    });
}
    </script>
@endsection
