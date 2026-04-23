<aside id="layout-menu" class="layout-menu menu-vertical menu" style="background-color: #242745;">
    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/favicon/favicon.ico') }}" alt="{{ config('app.name') }}" width="24" height="24">
            </span>
            <span class="app-brand-text demo menu-text fw-bold">{{ config('app.name') }}</span>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item text-white active">
            <a href="{{ url('/') }}" class="menu-link active text-white">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>Pano</div>
            </a>
        </li>

        <li class="menu-item text-white active">
            <a href="{{ url('/form') }}" class="menu-link active text-white">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>Formlar</div>
            </a>
        </li>

        @isAdmin
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Yönetici Ayarları</span>
            </li>

        <li class="menu-item text-white active">
            <a href="{{ url('/form/archive') }}" class="menu-link active text-white">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>Form Arşivi</div>
            </a>
        </li>

        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle text-muted">
            <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
            <div data-i18n="Layouts">Form Yönetimi</div>
            </a>

            <ul class="menu-sub">
            <li class="menu-item">
                <a href="/form/new-form" class="menu-link">
                <div data-i18n="Collapsed menu">Yeni Form</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="/form/new-subform" class="menu-link">
                <div data-i18n="Content navbar">Yeni Alt Form</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="/form/form-attachement" class="menu-link">
                <div data-i18n="Content nav + Sidebar">Form İlişkilendir</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="/form/list" class="menu-link" target="_blank">
                <div data-i18n="Horizontal">Tüm Formlar</div>
                </a>
            </li>
            </ul>
        </li>

        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle text-muted">
            <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
            <div data-i18n="Layouts">Kullanıcı Yönetimi</div>
            </a>

            <ul class="menu-sub">
            <li class="menu-item">
                <a href="/user/list-user" class="menu-link">
                <div data-i18n="Collapsed menu">Tüm Kullanıcılar</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="/user/list-admin" class="menu-link">
                <div data-i18n="Content navbar">Tüm Yöneticiler</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="/user/new-user" class="menu-link">
                <div data-i18n="Content nav + Sidebar">Yeni Kullanıcı</div>
                </a>
            </li>
           
        </ul>
        </li>


        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle text-muted">
            <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
            <div data-i18n="Layouts">Tesis Yönetimi</div>
            </a>

            <ul class="menu-sub">
            <li class="menu-item">
                <a href="/facility/list-facility" class="menu-link">
                <div data-i18n="Collapsed menu">Tüm Tesisler</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="/facility/new-unit" class="menu-link">
                <div data-i18n="Content navbar">Tüm Birimler</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="/facility/new-facility" class="menu-link">
                <div data-i18n="Content nav + Sidebar">Yeni Tesis</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="/facility/new-unit" class="menu-link">
                <div data-i18n="Content nav + Sidebar">Yeni Birim</div>
                </a>
            </li>
           
        </ul>
        </li>

        @endisAdmin
    </ul>
</aside>
