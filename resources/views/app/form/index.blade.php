@extends('master')

@php($pageTitle = 'Formlar')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-2">Formlar</h4>
                    <p class="text-muted mb-0">Formlar; birden fazla sayfadan olusabilen, evet/hayir tipi sorular iceren ve alt form iliskileriyle genisleyebilen ana yapiyi yonetir.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-2">Sayfali Yapi</h5>
                    <p class="mb-0">Her form icin sinirsiz sayfa tanimlanabilir. Boylesi uzun checklist ve denetim akislarini parcali yapida tutmamiza imkan verir.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-2">Evet / Hayir Sorulari</h5>
                    <p class="mb-0">Bu yapida tum sorular yes/no mantiginda kurgulanir. Sonraki adimda soru, sayfa ve cevap modellerini birlikte netlestirebiliriz.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-2">Alt Form Baglantisi</h5>
                    <p class="mb-0">Alt formlar ana forma bagli calisir ve ihtiyaca gore detay denetim akislarini devreye alir.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

