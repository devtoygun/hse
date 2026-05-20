@extends('master')

@php($pageTitle = 'Yeni Tesis')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-2 d-flex justify-content-between">
                        <span>Yeni Tesis/Birim</span>
                        <span><a href="/facility/" class="btn btn-sm btn-primary"><i class="fas fa-arrow-left me-2"></i> Geri</a></span>
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Yeni Tesis</h5>

                    <div class="row mb-2 align-items-center g-2">
                        <label for="tesis_name" class="col-sm-4 col-form-label">Tesis Adı</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="tesis_name">
                        </div>
                    </div>
                    <div class="col-12 text-end">
                            <button type="button" class="btn btn-primary" onclick="saveFacilityBtn()">Kaydet</button>
                        </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Yeni Birim</h5>
                    <div class="row align-items-center g-2 mb-2">
                        <label for="tesis" class="col-sm-4 col-form-label">Tesis</label>
                        <div class="col-sm-8">
                            <select id="tesis" class="form-select">
                                <option value="0">Tesis Seçin</option>
                                @foreach ($facilities as $item)
                                    <option value="{{ $item->id }}">{{$item->facility}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-2 align-items-center g-2">
                        <label for="birim_adi" class="col-sm-4 col-form-label">Birim Adı</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="birim_adi" >
                        </div>
                    </div>
                    <div class="col-12 text-end">
                            <button type="button" class="btn btn-primary" onclick="saveUnitBtn()">Kaydet</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection

@section('script')
<script>
    function saveFacilityBtn(){
        var name = $("#tesis_name").val()

        fastpost('/facility/new-facility', {name:name});
    }

    function saveUnitBtn(){
        var tesis = $("#tesis").val()
        var unit_name = $("#birim_adi").val()

        fastpost('/facility/new-unit', {tesis:tesis, unit_name:unit_name})
    }
</script>
@endsection