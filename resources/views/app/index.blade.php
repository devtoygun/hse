@extends('master')

@php($pageTitle = config('app.name').' Dashboard')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5 class="card-title mb-0">Istatistikler</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                    <i class="ti ti-sm tf-icons ti ti-archive"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ number_format($dashboardStats['completed_forms']) }}</h5>
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
                                    <h5 class="mb-0">{{ number_format($dashboardStats['users']) }}</h5>
                                    <small>Kullanici</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                    <i class="ti ti-layout-sidebar ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ number_format($dashboardStats['forms']) }}</h5>
                                    <small>Form</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded-pill bg-label-success me-3 p-2">
                                    <i class="ti ti-building-factory ti-sm"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ number_format($dashboardStats['facilities']) }}</h5>
                                    <small>Tesis</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title m-0 me-2">
                        <h5 class="m-0 me-2">Form Istatistigi</h5>
                        <small class="text-muted">Doldurulan formlara gore</small>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        @forelse ($formStats as $stat)
                            <li class="d-flex mb-4 pb-1 align-items-center">
                                <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                    <i class="ti ti-forms ti-sm"></i>
                                </div>
                                <div class="d-flex w-100 align-items-center gap-2">
                                    <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                                        <div>
                                            <h6 class="mb-0">{{ $stat['label'] }}</h6>
                                            <small class="text-muted">{{ number_format($stat['count']) }} kayit</small>
                                        </div>
                                        <div class="user-progress d-flex align-items-center gap-2">
                                            <h6 class="mb-0">{{ $stat['percent'] }}%</h6>
                                        </div>
                                    </div>
                                    <div class="chart-progress" data-color="primary" data-series="{{ (int) $stat['percent'] }}"></div>
                                </div>
                            </li>
                        @empty
                            <li class="text-muted">Henuz doldurulmus form yok.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title m-0 me-2">
                        <h5 class="m-0 me-2">Cihaz Istatistigi</h5>
                        <small class="text-muted">Dijital imzalardan hesaplandi</small>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        @php($deviceIcons = ['Televizyon' => 'ti-device-tv-old', 'Masaustu' => 'ti-device-desktop', 'Tablet' => 'ti-device-tablet', 'Telefon' => 'ti-device-mobile', 'Bilinmeyen' => 'ti-file-unknown'])
                        @foreach ($deviceStats as $stat)
                            <li class="d-flex mb-4 pb-1 align-items-center">
                                <i class="ti {{ $deviceIcons[$stat['label']] ?? 'ti-file-unknown' }} me-3"></i>
                                <div class="d-flex w-100 align-items-center gap-2">
                                    <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                                        <div>
                                            <h6 class="mb-0">{{ $stat['label'] }}</h6>
                                            <small class="text-muted">{{ number_format($stat['count']) }} kayit</small>
                                        </div>
                                        <div class="user-progress d-flex align-items-center gap-2">
                                            <h6 class="mb-0">{{ $stat['percent'] }}%</h6>
                                        </div>
                                    </div>
                                    <div class="chart-progress" data-color="success" data-series="{{ (int) $stat['percent'] }}"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title m-0 me-2">
                        <h5 class="m-0 me-2">Tarayici Istatistigi</h5>
                        <small class="text-muted">Dijital imzalardan hesaplandi</small>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        @php($browserImages = ['Google Chrome' => 'chrome.png', 'Apple Safari' => 'safari.png', 'Mozilla Firefox' => 'firefox.png', 'Opera' => 'opera.png', 'Microsoft Edge' => 'edge.png'])
                        @foreach ($browserStats as $stat)
                            <li class="d-flex mb-4 pb-1 align-items-center">
                                @if (isset($browserImages[$stat['label']]))
                                    <img src="{{ asset('assets/img/icons/brands/'.$browserImages[$stat['label']]) }}" alt="{{ $stat['label'] }}" height="28" class="me-3 rounded">
                                @else
                                    <i class="ti ti-world me-3"></i>
                                @endif
                                <div class="d-flex w-100 align-items-center gap-2">
                                    <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                                        <div>
                                            <h6 class="mb-0">{{ $stat['label'] }}</h6>
                                            <small class="text-muted">{{ number_format($stat['count']) }} kayit</small>
                                        </div>
                                        <div class="user-progress d-flex align-items-center gap-2">
                                            <h6 class="mb-0">{{ $stat['percent'] }}%</h6>
                                        </div>
                                    </div>
                                    <div class="chart-progress" data-color="info" data-series="{{ (int) $stat['percent'] }}"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="card border h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Haftalik Form Raporu</h5>
                        <small class="text-muted">Son 7 gunluk doldurulan form ozeti</small>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-3">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-chart-pie-2 ti-sm"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Bu Hafta</h6>
                                    <small class="text-muted">Doldurulan form</small>
                                </div>
                                <div class="user-progress">
                                    <small>{{ number_format($weeklyReport['this_week']) }}</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-3">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success"><i class="ti ti-calendar ti-sm"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Bu Ay</h6>
                                    <small class="text-muted">Doldurulan form</small>
                                </div>
                                <div class="user-progress">
                                    <small>{{ number_format($weeklyReport['this_month']) }}</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-3">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-secondary"><i class="ti ti-clock ti-sm"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Ortalama Sure</h6>
                                    <small class="text-muted">Imza suresi</small>
                                </div>
                                <div class="user-progress">
                                    <small>{{ gmdate('H:i:s', $weeklyReport['average_duration']) }}</small>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div id="reportBarChart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border h-100">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title m-0 me-2">Aktif Oturumlar</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless border-top">
                        <thead class="border-bottom">
                            <tr>
                                <th>Kullanici</th>
                                <th>Oturum</th>
                                <th>Oturum Suresi</th>
                                <th>Cihaz</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($activeSessions as $session)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <p class="mb-0 fw-semibold">
                                                {{ $session->user ? trim($session->user->firstname.' '.$session->user->lastname) : '-' }}
                                            </p>
                                            <small class="text-muted">{{ $session->ip_address }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <p class="mb-0 fw-semibold">{{ $session->logged_in_at?->format('d.m.Y H:i') ?? '-' }}</p>
                                            <small class="text-muted">Son gorulme: {{ $session->last_seen_at?->format('H:i') ?? '-' }}</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-label-success">{{ gmdate('H:i:s', (int) $session->active_duration_seconds) }}</span></td>
                                    <td>{{ $session->device_type ?? $session->browser_name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted text-center">Aktif oturum yok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
            <div class="card border h-100">
                <div class="card-header d-flex justify-content-between">
                    <div class="card-title m-0 me-2">
                        <h5 class="m-0 me-2">Sunucu Durumu</h5>
                        <small class="text-muted">Canli sistem ozeti</small>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-3 pb-1 align-items-center">
                            <div class="badge bg-label-primary me-3 rounded p-2"><i class="ti ti-server ti-sm"></i></div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2"><h6 class="mb-0">Web Sunucusu</h6><small class="text-muted d-block">{{ $serverStatus['web_server'] }}</small></div>
                                <div class="user-progress d-flex align-items-center gap-1"><span class="badge bg-label-success">Aktif</span></div>
                            </div>
                        </li>
                        <li class="d-flex mb-3 pb-1 align-items-center">
                            <div class="badge bg-label-info rounded me-3 p-2"><i class="ti ti-database ti-sm"></i></div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2"><h6 class="mb-0">MySQL Sunucusu</h6><small class="text-muted d-block">Database</small></div>
                                <div class="user-progress"><h6 class="mb-0 text-success">{{ $serverStatus['database'] }}</h6></div>
                            </div>
                        </li>
                        <li class="d-flex mb-3 pb-1 align-items-center">
                            <div class="badge bg-label-danger rounded me-3 p-2"><i class="ti ti-code ti-sm"></i></div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2"><h6 class="mb-0">Laravel Uygulamasi</h6><small class="text-muted d-block">PHP {{ $serverStatus['php'] }}</small></div>
                                <div class="user-progress"><h6 class="mb-0">v{{ $serverStatus['laravel'] }}</h6></div>
                            </div>
                        </li>
                        <li class="d-flex mb-3 pb-1 align-items-center">
                            <div class="badge bg-label-warning me-3 rounded p-2"><i class="ti ti-users ti-sm"></i></div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2"><h6 class="mb-0">Aktif Oturum</h6><small class="text-muted d-block">Session</small></div>
                                <div class="user-progress"><h6 class="mb-0 text-warning">{{ $serverStatus['active_sessions'] }}</h6></div>
                            </div>
                        </li>
                        <li class="d-flex mb-3 pb-1 align-items-center">
                            <div class="badge bg-label-warning me-3 rounded p-2"><i class="ti ti-chart-bar ti-sm"></i></div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2"><h6 class="mb-0">CPU Yuku</h6><small class="text-muted d-block">System Load</small></div>
                                <div class="user-progress"><h6 class="mb-0 text-warning">{{ $serverStatus['cpu'] }}</h6></div>
                            </div>
                        </li>
                        <li class="d-flex mb-3 pb-1 align-items-center">
                            <div class="badge bg-label-secondary me-3 rounded p-2"><i class="ti ti-components ti-sm"></i></div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2"><h6 class="mb-0">RAM Bellek</h6><small class="text-muted d-block">PHP Memory</small></div>
                                <div class="user-progress"><h6 class="mb-0">{{ $serverStatus['memory'] }}</h6></div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center">
                            <div class="badge bg-label-success me-3 rounded p-2"><i class="ti ti-layout-grid ti-sm"></i></div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2"><h6 class="mb-0">Disk Alani</h6><small class="text-muted d-block">{{ $serverStatus['disk_text'] }}</small></div>
                                <div class="user-progress"><h6 class="mb-0 {{ $serverStatus['disk_used_percent'] > 85 ? 'text-danger' : 'text-success' }}">%{{ $serverStatus['disk_used_percent'] }}</h6></div>
                            </div>
                        </li>
                        <li class="d-flex align-items-center mt-3">
                            <div class="badge bg-label-dark me-3 rounded p-2"><i class="ti ti-world ti-sm"></i></div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2"><h6 class="mb-0">Bant Genisligi</h6><small class="text-muted d-block">Network</small></div>
                                <div class="user-progress"><h6 class="mb-0">{{ $serverStatus['bandwidth'] }}</h6></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const reportBarChartEl = document.querySelector('#reportBarChart'),
            reportBarChartConfig = {
                chart: { height: 230, type: 'bar', toolbar: { show: false } },
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
                grid: { show: false, padding: { top: -20, bottom: 0, left: -10, right: -10 } },
                colors: [
                    config.colors_label.primary,
                    config.colors_label.primary,
                    config.colors_label.primary,
                    config.colors_label.primary,
                    config.colors.primary,
                    config.colors_label.primary,
                    config.colors_label.primary
                ],
                dataLabels: { enabled: false },
                series: [{ data: @json($weeklyReport['series']) }],
                legend: { show: false },
                xaxis: {
                    categories: @json($weeklyReport['labels']),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: labelColor, fontSize: '13px' } }
                },
                yaxis: { labels: { show: false } },
                responsive: [
                    { breakpoint: 1025, options: { chart: { height: 190 } } },
                    { breakpoint: 769, options: { chart: { height: 250 } } }
                ]
            };

        if (typeof reportBarChartEl !== undefined && reportBarChartEl !== null) {
            const barChart = new ApexCharts(reportBarChartEl, reportBarChartConfig);
            barChart.render();
        }
    </script>
@endsection
