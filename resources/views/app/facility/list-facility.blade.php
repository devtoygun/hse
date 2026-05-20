@extends('master')

@php($pageTitle = 'Tum Tesisler')

@section('content')
     <div class="row mb-3 ">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-2 d-flex justify-content-between">
                        <span>Tesisler/Birimler</span>
                        <span><a href="/facility/new" class="btn btn-sm btn-primary"><i class="fas me-2 fa-plus"></i> Yeni</a></span>
                    </h4>
                </div>
            </div>
        </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tesisler</h5>

                        <div class="table-responsive text-nowrap">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Tesis</th>
                        <th>#</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                     
                        @foreach ($facilities as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->facility}}</td>
                                <td><a href="javascript:;" class="btn btn-sm btn-danger" onclick="deleteFacility({{ $item->id }})"><i class="fas fa-trash "></i></a></td>
                            </tr>
                        @endforeach
                    
                    </tbody>
                  </table>
                </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Birimler</h5>

                        <div class="table-responsive text-nowrap">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Tesis</th>
                        <th>Birim</th>
                        <th>#</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                     
                        @foreach ($units as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->facility->facility ?? '-'}}</td>
                                <td>{{$item->unit}}</td>
                                <td><a href="javascript:;" class="btn btn-sm btn-danger" onclick="deleteUnit({{ $item->id }})"><i class="fas fa-trash "></i></a></td>
                            </tr>
                        @endforeach
                    
                    </tbody>
                  </table>
                </div>
                    </div>
                </div>
            </div>
        </div>

@endsection


@section('script')
    <script>
        function deleteFacility(id){
            fastpost('/facility/delete-facility', {id:id})
        }

        function deleteUnit(id){
            fastpost('/facility/delete-unit', {id:id})
        }
    </script>
@endsection