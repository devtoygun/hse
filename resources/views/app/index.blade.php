@extends('master')

@php($pageTitle = config('app.name').' Dashboard')

@section('content')
    <div class="row mb-3">
    <!-- Statistics -->
        <div class="col-12  ">
            <div class="card border ">
            <div class="card-header">
                <div class="d-flex justify-content-between ">
                <h5 class="card-title mb-0">İstatistikler</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-3">
                <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                    <div class="badge rounded-pill bg-label-primary me-3 p-2">
                        <i class="ti  ti-sm tf-icons ti ti-archive"></i>
                    </div>
                    <div class="card-info">
                        <h5 class="mb-0">230k</h5>
                        <small>Tamamlanan Form</small>
                    </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                    <div class="badge rounded-pill bg-label-info me-3 p-2">
                        <i class="ti ti-users ti-sm"></i>
                    </div>
                    <div class="card-info">
                        <h5 class="mb-0">8.549k</h5>
                        <small>Kullanıcı</small>
                    </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                    <div class="badge rounded-pill bg-label-danger me-3 p-2">
                        <i class="ti ti ti-layout-sidebar ti-sm"></i>
                    </div>
                    <div class="card-info">
                        <h5 class="mb-0">1.423k</h5>
                        <small>Form</small>
                    </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="d-flex align-items-center">
                    <div class="badge rounded-pill bg-label-success me-3 p-2">
                        <i class="ti ti ti-building-factory ti-sm"></i>
                    </div>
                    <div class="card-info">
                        <h5 class="mb-0">$9745</h5>
                        <small>Tesis</small>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        <!--/ Statistics -->

    </div>

    <div class="row ">
        <!-- Browser States -->
                <div class="col-xl-4 col-md-6 mb-4">
                  <div class="card border h-100">
                    <div class="card-header d-flex justify-content-between">
                      <div class="card-title m-0 me-2">
                        <h5 class="m-0 me-2">Form İstatistiği</h5>
                        <small class="text-muted">Counter April 2022</small>
                      </div>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="employeeList"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false">
                          <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="employeeList">
                          <a class="dropdown-item" href="javascript:void(0);">Download</a>
                          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                          <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1 align-items-center">
                          <img
                            src="../../assets/img/icons/brands/chrome.png"
                            alt="Chrome"
                            height="28"
                            class="me-3 rounded" />
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Google Chrome</h6>
                              </div>

                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">90.4%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="secondary" data-series="85"></div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1 align-items-center">
                          <img
                            src="../../assets/img/icons/brands/safari.png"
                            alt="Safari"
                            height="28"
                            class="me-3 rounded" />
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Apple Safari</h6>
                              </div>
                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">70.6%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="success" data-series="70"></div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1 align-items-center">
                          <img
                            src="../../assets/img/icons/brands/firefox.png"
                            alt="Firefox"
                            height="28"
                            class="me-3 rounded" />
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Mozilla Firefox</h6>
                              </div>
                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">35.5%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="primary" data-series="25"></div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1 align-items-center">
                          <img
                            src="../../assets/img/icons/brands/opera.png"
                            alt="Opera"
                            height="28"
                            class="me-3 rounded" />
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Opera Mini</h6>
                              </div>

                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">80.0%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="danger" data-series="75"></div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1 align-items-center">
                          <img
                            src="../../assets/img/icons/brands/edge.png"
                            alt="Edge"
                            height="28"
                            class="me-3 rounded" />
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Internet Explorer</h6>
                              </div>
                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">62.2%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="info" data-series="60"></div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>

                <!-- Browser States -->
                <div class="col-xl-4 col-md-6 mb-4">
                  <div class="card border h-100">
                    <div class="card-header d-flex justify-content-between">
                      <div class="card-title m-0 me-2">
                        <h5 class="m-0 me-2">Cihaz İstatistiği</h5>
                        <small class="text-muted">Counter April 2022</small>
                      </div>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="employeeList"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false">
                          <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="employeeList">
                          <a class="dropdown-item" href="javascript:void(0);">Download</a>
                          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                          <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1 align-items-center">
                          <i class="ti ti-device-tv-old"></i>
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Televizyon</h6>
                              </div>

                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">90.4%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="secondary" data-series="85"></div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1 align-items-center">
                         <i class="ti ti-device-desktop"></i>
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Masaüstü</h6>
                              </div>
                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">70.6%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="success" data-series="70"></div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1 align-items-center">
                          <i class="ti ti-device-tablet"></i>
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Tablet</h6>
                              </div>
                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">35.5%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="primary" data-series="25"></div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1 align-items-center">
                          <i class="ti ti-device-mobile"></i>
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Telefon</h6>
                              </div>

                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">80.0%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="danger" data-series="75"></div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1 align-items-center">
                          <i class="ti ti-file-unknown"></i>
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Bilinmeyen</h6>
                              </div>
                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">62.2%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="info" data-series="60"></div>
                          </div>
                        </li>
                        
                      </ul>
                    </div>
                  </div>
                </div>

                <!-- Browser States -->
                <div class="col-xl-4 col-md-6 mb-4">
                  <div class="card border h-100">
                    <div class="card-header d-flex justify-content-between">
                      <div class="card-title m-0 me-2">
                        <h5 class="m-0 me-2">Tarayıcı İstatistiği</h5>
                        <small class="text-muted">Counter April 2022</small>
                      </div>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="employeeList"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false">
                          <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="employeeList">
                          <a class="dropdown-item" href="javascript:void(0);">Download</a>
                          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                          <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1 align-items-center">
                          <img
                            src="../../assets/img/icons/brands/chrome.png"
                            alt="Chrome"
                            height="28"
                            class="me-3 rounded" />
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Google Chrome</h6>
                              </div>

                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">90.4%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="secondary" data-series="85"></div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1 align-items-center">
                          <img
                            src="../../assets/img/icons/brands/safari.png"
                            alt="Safari"
                            height="28"
                            class="me-3 rounded" />
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Apple Safari</h6>
                              </div>
                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">70.6%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="success" data-series="70"></div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1 align-items-center">
                          <img
                            src="../../assets/img/icons/brands/firefox.png"
                            alt="Firefox"
                            height="28"
                            class="me-3 rounded" />
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Mozilla Firefox</h6>
                              </div>
                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">35.5%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="primary" data-series="25"></div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1 align-items-center">
                          <img
                            src="../../assets/img/icons/brands/opera.png"
                            alt="Opera"
                            height="28"
                            class="me-3 rounded" />
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Opera Mini</h6>
                              </div>

                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">80.0%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="danger" data-series="75"></div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1 align-items-center">
                          <img
                            src="../../assets/img/icons/brands/edge.png"
                            alt="Edge"
                            height="28"
                            class="me-3 rounded" />
                          <div class="d-flex w-100 align-items-center gap-2">
                            <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                              <div>
                                <h6 class="mb-0">Internet Explorer</h6>
                              </div>
                              <div class="user-progress d-flex align-items-center gap-2">
                                <h6 class="mb-0">62.2%</h6>
                              </div>
                            </div>
                            <div class="chart-progress" data-color="info" data-series="60"></div>
                          </div>
                        </li>
            
                      </ul>
                    </div>
                  </div>
                </div>
    </div>





    <div class="row mb-3">
          <!-- Earning Reports -->
                <div class="col-12">
                  <div class="card border h-100">
                    <div class="card-header d-flex justify-content-between">
                      <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Earning Reports</h5>
                        <small class="text-muted">Weekly Earnings Overview</small>
                      </div>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="earningReports"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false">
                          <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReports">
                          <a class="dropdown-item" href="javascript:void(0);">Download</a>
                          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                          <a class="dropdown-item" href="javascript:void(0);">Share</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body pb-0">
                      <ul class="p-0 m-0">
                        <li class="d-flex mb-3">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary"
                              ><i class="ti ti-chart-pie-2 ti-sm"></i
                            ></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Net Profit</h6>
                              <small class="text-muted">12.4k Sales</small>
                            </div>
                            <div class="user-progress">
                              <small>$1,619</small><i class="ti ti-chevron-up text-success ms-3"></i>
                              <small class="text-muted">18.6%</small>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-3">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-success"
                              ><i class="ti ti-currency-dollar ti-sm"></i
                            ></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Total Income</h6>
                              <small class="text-muted">Sales, Affiliation</small>
                            </div>
                            <div class="user-progress">
                              <small>$3,571</small><i class="ti ti-chevron-up text-success ms-3"></i>
                              <small class="text-muted">39.6%</small>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-3">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-secondary"
                              ><i class="ti ti-credit-card ti-sm"></i
                            ></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Total Expenses</h6>
                              <small class="text-muted">ADVT, Marketing</small>
                            </div>
                            <div class="user-progress">
                              <small>$430</small><i class="ti ti-chevron-up text-success ms-3"></i>
                              <small class="text-muted">52.8%</small>
                            </div>
                          </div>
                        </li>
                      </ul>
                      <div id="reportBarChart"></div>
                    </div>
                  </div>
                </div>
                <!--/ Earning Reports -->
    </div>



    <div class="row">
        <!-- Last Transaction -->
                <div class="col-lg-8 mb-4 mb-lg-0">
                  <div class="card border h-100">
                    <div class="card-header d-flex justify-content-between">
                      <h5 class="card-title m-0 me-2">Aktif Oturumlar</h5>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="teamMemberList"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false">
                          <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="teamMemberList">
                          <a class="btn btn-danger dropdown-item" href="javascript:void(0);">Tümünü Sonlandır</a>
                        </div>
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table class="table table-borderless border-top">
                        <thead class="border-bottom">
                          <tr>
                            <th>Kullanıcı</th>
                            <th>Oturum</th>
                            <th>Oturum Süresi</th>
                            <th>İşlem</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              <div class="d-flex justify-content-start align-items-center">
                              
                                <div class="d-flex flex-column">
                                  <p class="mb-0 fw-semibold">Alihan Öztürk</p>
                                </div>
                              </div>
                            </td>
                            <td>
                              <div class="d-flex flex-column">
                                <p class="mb-0 fw-semibold">17/10/1215 15:54</p>
                              </div>
                            </td>
                            <td><span class="badge bg-label-success">3 Saat 25 DK</span></td>
                            <td>
                              <a href="javascript:;" class="btn btn-sm btn-danger">Sonlandır</a>
                            </td>
                          </tr>

                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>


                 <!-- Transactions -->
                <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                  <div class="card border h-100">
                    <div class="card-header d-flex justify-content-between">
                      <div class="card-title m-0 me-2">
                        <h5 class="m-0 me-2">Sunucu Durumu</h5>
                        <small class="text-muted">Total 58 Transactions done in this Month</small>
                      </div>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="transactionID"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false">
                          <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                          <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                          <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                          <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                   <ul class="p-0 m-0">
    <li class="d-flex mb-3 pb-1 align-items-center">
        <div class="badge bg-label-primary me-3 rounded p-2">
            <i class="ti ti-server ti-sm"></i>
        </div>
        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
                <h6 class="mb-0">Apache Sunucusu</h6>
                <small class="text-muted d-block">Web Server</small>
            </div>
            <div class="user-progress d-flex align-items-center gap-1">
                <span class="badge bg-label-success">Aktif</span>
            </div>
        </div>
    </li>

    <li class="d-flex mb-3 pb-1 align-items-center">
        <div class="badge bg-label-info rounded me-3 p-2">
            <i class="ti ti-database ti-sm"></i>
        </div>
        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
                <h6 class="mb-0">MySQL Sunucusu</h6>
                <small class="text-muted d-block">Database</small>
            </div>
            <div class="user-progress">
                <h6 class="mb-0 text-success">Çalışıyor</h6>
            </div>
        </div>
    </li>

    <li class="d-flex mb-3 pb-1 align-items-center">
        <div class="badge bg-label-danger rounded me-3 p-2">
            <i class="ti ti-code ti-sm"></i>
        </div>
        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
                <h6 class="mb-0">Laravel Uygulaması</h6>
                <small class="text-muted d-block">Framework v11</small>
            </div>
            <div class="user-progress">
                <h6 class="mb-0">Stabil</h6>
            </div>
        </div>
    </li>

    <li class="d-flex mb-3 pb-1 align-items-center">
        <div class="badge bg-label-warning me-3 rounded p-2">
            <i class="ti ti-chart-bar ti-sm"></i>
        </div>
        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
                <h6 class="mb-0">CPU Yükü</h6>
                <small class="text-muted d-block">System Load</small>
            </div>
            <div class="user-progress">
                <h6 class="mb-0 text-warning">%24</h6>
            </div>
        </div>
    </li>

    <li class="d-flex mb-3 pb-1 align-items-center">
        <div class="badge bg-label-secondary me-3 rounded p-2">
            <i class="ti ti-components ti-sm"></i>
        </div>
        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
                <h6 class="mb-0">RAM Bellek</h6>
                <small class="text-muted d-block">Memory Usage</small>
            </div>
            <div class="user-progress">
                <h6 class="mb-0">1.2GB / 4GB</h6>
            </div>
        </div>
    </li>

    <li class="d-flex mb-3 pb-1 align-items-center">
        <div class="badge bg-label-success me-3 rounded p-2">
            <i class="ti ti-layout-grid ti-sm"></i>
        </div>
        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
                <h6 class="mb-0">Disk Alanı</h6>
                <small class="text-muted d-block">SSD Storage</small>
            </div>
            <div class="user-progress">
                <h6 class="mb-0 text-danger">%85</h6>
            </div>
        </div>
    </li>

    <li class="d-flex align-items-center">
        <div class="badge bg-label-dark me-3 rounded p-2">
            <i class="ti ti-world ti-sm"></i>
        </div>
        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="me-2">
                <h6 class="mb-0">Bant Genişliği</h6>
                <small class="text-muted d-block">Network</small>
            </div>
            <div class="user-progress">
                <h6 class="mb-0">12 Mb/s</h6>
            </div>
        </div>
    </li>
</ul>
                    </div>
                  </div>
                </div>
                <!--/ Transactions -->
    </div>
@endsection


@section('script')
    <script>
        // Earning Reports Bar Chart
  // --------------------------------------------------------------------
  const reportBarChartEl = document.querySelector('#reportBarChart'),
    reportBarChartConfig = {
      chart: {
        height: 230,
        type: 'bar',
        toolbar: {
          show: false
        }
      },
      plotOptions: {
        bar: {
          barHeight: '60%',
          columnWidth: '60%',
          startingShape: 'rounded',
          endingShape: 'rounded',
          borderRadius: 4,
          distributed: true
        }
      },
      grid: {
        show: false,
        padding: {
          top: -20,
          bottom: 0,
          left: -10,
          right: -10
        }
      },
      colors: [
        config.colors_label.primary,
        config.colors_label.primary,
        config.colors_label.primary,
        config.colors_label.primary,
        config.colors.primary,
        config.colors_label.primary,
        config.colors_label.primary
      ],
      dataLabels: {
        enabled: false
      },
      series: [
        {
          data: [40, 95, 60, 45, 90, 50, 75]
        }
      ],
      legend: {
        show: false
      },
      xaxis: {
        categories: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: labelColor,
            fontSize: '13px'
          }
        }
      },
      yaxis: {
        labels: {
          show: false
        }
      },
      responsive: [
        {
          breakpoint: 1025,
          options: {
            chart: {
              height: 190
            }
          }
        },
        {
          breakpoint: 769,
          options: {
            chart: {
              height: 250
            }
          }
        }
      ]
    };
  if (typeof reportBarChartEl !== undefined && reportBarChartEl !== null) {
    const barChart = new ApexCharts(reportBarChartEl, reportBarChartConfig);
    barChart.render();
  }

    </script>
@endsection