<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
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
        <li class="menu-item">
            <a href="{{ url('/') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>Dashboard</div>
            </a>
        </li>
    </ul>
</aside>
