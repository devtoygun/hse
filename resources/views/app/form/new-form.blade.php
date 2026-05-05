@extends('master')

@php($pageTitle = 'Yeni Form')

@section('content')
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Yeni Form Oluştur</h4>

                    <form id="newFormCard" class="row g-3" onsubmit="return false;">
                        <div class="col-12">
                            <div class="row align-items-center g-2">
                                <label for="form_title" class="col-sm-4 col-form-label">Form Başlığı</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="form_title" name="form_title" placeholder="Form başlığını giriniz">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row align-items-center g-2">
                                <label for="form_detail" class="col-sm-4 col-form-label">Form Detayı</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="form_detail" name="form_detail" rows="3" placeholder="Form detayını giriniz"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row align-items-center g-2">
                                <label for="annotations" class="col-sm-4 col-form-label">Açıklamalar</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="annotations" name="annotations" rows="3" placeholder="Ek açıklamaları giriniz"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row align-items-center g-2">
                                <label for="email_sending" class="col-sm-4 col-form-label">E-posta Gönderimi</label>
                                <div class="col-sm-8">
                                    <select class="form-select" id="email_sending" name="email_sending">
                                        <option value="0" selected>Hayır</option>
                                        <option value="1">Evet</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row align-items-center g-2">
                                <label for="email_recipient_address" class="col-sm-4 col-form-label">Alıcı E-posta Adresi</label>
                                <div class="col-sm-8">
                                    <input type="email" class="form-control" id="email_recipient_address" name="email_recipient_address" placeholder="ornek@firma.com" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-primary" id="saveNewFormButton">Kaydet</button>
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
            const emailSendingSelect = document.getElementById('email_sending');
            const emailRecipientInput = document.getElementById('email_recipient_address');
            const saveButton = document.getElementById('saveNewFormButton');

            // E-posta secimine gore alici alanini acip kapatiyoruz.
            const syncRecipientFieldState = function () {
                const isEmailSendingEnabled = emailSendingSelect.value === '1';

                emailRecipientInput.disabled = !isEmailSendingEnabled;

                // Gonderim kapaliysa gereksiz veriyi temizliyoruz.
                if (!isEmailSendingEnabled) {
                    emailRecipientInput.value = '';
                }
            };

            // Form verilerini fastpost ile ilgili rotaya iletiyoruz.
            const submitNewForm = function () {
                fastpost('/form/create-form', {
                    form_title: document.getElementById('form_title').value,
                    form_detail: document.getElementById('form_detail').value,
                    annotations: document.getElementById('annotations').value,
                    email_sending: emailSendingSelect.value === '1' ? 1 : 0,
                    email_recipient_address: emailRecipientInput.value
                });
            };

            emailSendingSelect.addEventListener('change', syncRecipientFieldState);
            saveButton.addEventListener('click', submitNewForm);

            // Sayfa ilk acildiginda alan durumunu dogru baslatiyoruz.
            syncRecipientFieldState();
        });
    </script>
@endsection
