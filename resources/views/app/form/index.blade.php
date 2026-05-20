@extends('master')

@php($pageTitle = 'Formlar')

@section('content')
    <div class="row mb-3 g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-2">
                        Formlar
                    </h4>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            @foreach ($forms as $item)
                <div class="col-3">
                    <div class="card mb-4">
                    <div class="card-body">
                      <h5 class="card-title">{{$item->form_title}}</h5>
                      <div class="card-subtitle text-muted mb-3">Toplam Soru: {{$item->formQuestions->count()}}</div>
                      <p class="card-text">
                        {{ $item->form_detail }}
                      </p>
                      <a href="/form/{{ $item->id }}" class="btn btn-primary">Başla</a>
                    </div>
                  </div>
                </div>
            @endforeach
        </div>
        

    </div>
@endsection

