@extends('master')

@php($pageTitle = config('app.name').' Dashboard')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card mb-6">
                <div class="card-body">
                    <h4 class="card-title mb-3">Hos geldin, {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</h4>
                    <p class="mb-4">Vuexy tema assetleri projeye dahil edildi. Bundan sonraki ekranlari bu yapi ustunden kurabiliriz.</p>

                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
