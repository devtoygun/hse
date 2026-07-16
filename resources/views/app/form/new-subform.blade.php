@extends('master')

@php($pageTitle = 'Yeni Alt Form')

@section('content')
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Yeni Alt Form Olustur</h4>

                    <form id="newSubFormCard" class="row g-3" onsubmit="return false;">
                        <div class="col-12">
                            <div class="row align-items-center g-2">
                                <label for="form_id" class="col-sm-4 col-form-label">Ust Form</label>
                                <div class="col-sm-8">
                                    <select class="form-select" id="form_id" name="form_id">
                                        <option value="" selected>Ust form seciniz</option>
                                        @foreach ($forms as $form)
                                            <option value="{{ $form->id }}">{{ $form->form_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row align-items-center g-2">
                                <label for="form_title" class="col-sm-4 col-form-label">Alt Form Basligi</label>
                                <div class="col-sm-8">
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="form_title"
                                        name="form_title"
                                        placeholder="Alt form basligini giriniz">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-primary" id="saveNewSubFormButton">Kaydet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const saveButton = document.getElementById('saveNewSubFormButton');

            const submitNewSubForm = function () {
                fastpost('/form/create-subform', {
                    form_id: document.getElementById('form_id').value,
                    form_title: document.getElementById('form_title').value
                });
            };

            saveButton.addEventListener('click', submitNewSubForm);
        });
    </script>
@endsection
