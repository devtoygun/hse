@extends('master')

@php($pageTitle = 'Tum Yoneticiler')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title mb-2">Tum Yoneticiler</h4>
            <p class="text-muted mb-0">`is_admin = 1` olan kullanicilar bu alanda listelenecek. Boylece yonetici hesaplari standart kullanicilardan ayri sekilde izlenebilecek.</p>
        </div>
    </div>
@endsection

