@extends('master')

@php($pageTitle = 'Form Detay')

@section('content')
    <div class="row g-4 mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-2">{{ $form->form_title }}</h4>
                    <p class="text-muted mb-0">{{$form->detail}}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-2">Ek Açıklamalar</h5>
                    <p class="mb-0">{{$form->annotations}}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">

                    <ul class="list-group">
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                            E-posta Gönderimi:
                            <span class="badge {{ $form->email_sending ? 'bg-primary':'bg-info' }}">{{$form->email_sending ? 'Evet' : 'Hayır'}}</span>
                          </li>
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                            Alıcı:
                            <span class="">{{$form->email_recipient_address}}</span>
                          </li>
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                            Altform Sayısı
                            <span class="badge bg-info">{{$form->subforms->count()}}</span>
                          </li>
                         
                        </ul>
                   
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                   <ul class="list-group">
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                            Form Durumu
                            <span class="badge {{ $form->status ? 'bg-success':'bg-warning' }}">{{$form->status ? 'Aktif':'Pasif'}}</span>
                          </li>
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                            Soru Sayısı:
                            <span class="badge bg-primary">{{$form->formQuestions->count()}}</span>
                          </li>
               
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                            Formu Oluşturan
                            <span class="badge bg-secondary"><a class="text-white" href="/user/detail/{{ $form->user->id }}">{{$form->user->firstname .' '.$form->user->lastname}}</a></span>
                          </li>
                          <li class="list-group-item d-flex justify-content-between align-items-center">
                            Form Oluşturulma
                            <span class="badge bg-secondary">{{ \Carbon\Carbon::parse($form->created_at)->format('d.m.Y')}}</span>
                          </li>
                        </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title d-flex justify-content-between">
                        <span>Sorular</span>
                        <a class="btn btn-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#soruModal" href="javascript:;"><i class="fas fa-plus"></i></a>
                    </h4>


                    <div class="table-responsive text-nowrap">
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Soru</th>
                        <th>Sıra</th>
                        <th>Onay</th>
                        <th>Durum</th>
                        <th>İşlem</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      <tr>
                        <td>
                          <i class="ti ti-brand-angular ti-lg text-danger me-3"></i> <strong>Angular Project</strong>
                        </td>
                        <td>Albert Cook</td>
                        <td>
                          <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Lilian Fuller">
                              <img src="../../assets/img/avatars/5.png" alt="Avatar" class="rounded-circle" />
                            </li>
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Sophia Wilkerson">
                              <img src="../../assets/img/avatars/6.png" alt="Avatar" class="rounded-circle" />
                            </li>
                            <li
                              data-bs-toggle="tooltip"
                              data-popup="tooltip-custom"
                              data-bs-placement="top"
                              class="avatar avatar-xs pull-up"
                              title="Christina Parker">
                              <img src="../../assets/img/avatars/7.png" alt="Avatar" class="rounded-circle" />
                            </li>
                          </ul>
                        </td>
                        <td><span class="badge bg-label-primary me-1">Active</span></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item" href="javascript:void(0);"
                                ><i class="ti ti-pencil me-1"></i> Edit</a
                              >
                              <a class="dropdown-item" href="javascript:void(0);"
                                ><i class="ti ti-trash me-1"></i> Delete</a
                              >
                            </div>
                          </div>
                        </td>
                      </tr>
                    
                    </tbody>
                  </table>
                </div>


                </div>
            </div>
        </div>
    </div>








    <!-- Soru Modal -->
              <div class="modal fade" id="soruModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
                  <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      <div class="text-center mb-4">
                        <h3 class="mb-2">Yeni Soru Ekle</h3>
                        <p class="text-muted">Eklediğiniz soru formda görüntülenecek</p>
                      </div>
                      <form id="addNewCCForm" class="row g-3" onsubmit="return false">
                        <div class="col-12">
                          <label class="form-label w-100" for="modalQuestion">Soru:</label>
                          <div class="input-group input-group-merge">
                            <input
                              id="modalQuestion"
                              name="modalAddCard"
                              class="form-control"
                              type="text"/>

                          </div>
                        </div>
                        <div class="col-12 col-md-6">
                          <label class="form-label" for="modalQuestionOrder">Soru Sırası</label>
                          <input type="number" id="modalQuestionOrder" class="form-control"/>
                        </div>
                       
                       
                        <div class="col-12">
                          <label class="switch">
                            <input type="checkbox" class="switch-input" id="approval_required"/>
                            <span class="switch-toggle-slider">
                              <span class="switch-on"></span>
                              <span class="switch-off"></span>
                            </span>
                            <span class="switch-label">Bu soru için onay gereksin mi?</span>
                          </label>
                        </div>
                        <div class="col-12 text-center">
                          <button type="submit" onclick="addQuestion({{ $form->id }})" class="btn btn-primary me-sm-3 me-1">Kaydet</button>
                          <button
                            type="reset"
                            class="btn btn-label-secondary btn-reset"
                            data-bs-dismiss="modal"
                            aria-label="Close">
                            Vazgeç
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <!--/ Soru Modal -->
@endsection


@section('script')
    <script>
        function addQuestion(formid){
            var title = $("#modalQuestion").val()
            var order = $("#modalQuestionOrder").val()
            var formid = {{ $form->id }}
            var approval
            
            if ($('#approval_required').is(':checked')) {
                approval = 1 
            } else {
                approval = 0
            }

            fastpost("/form/save-question", {title:title,order:order,approval:approval,formid:formid})

        }
    </script>
@endsection