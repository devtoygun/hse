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
                                <td>{{$user->firstname}}</td>
                                <td>{{$user->firstname}}</td>
                                <td>{{$user->firstname}}</td>
                                <td>{{$user->firstname}}</td>
                                <td>{{$user->firstname}}</td>
                                <td>{{$user->firstname}}</td>
                                <td>{{$user->firstname}}</td>
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
       dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
    });
    </script>
@endsection
