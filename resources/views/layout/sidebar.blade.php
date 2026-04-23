@php
    $currentPath = request()->path();
    $isDashboard = request()->routeIs('app.index');
    $isForms = request()->is('form');
    $isFormArchive = request()->is('form/archive');
    $isFormManagement = request()->is('form/new-form') || request()->is('form/new-subform') || request()->is('form/form-attachement') || request()->is('form/list');
    $isUserManagement = request()->is('user/list-user') || request()->is('user/list-admin') || request()->is('user/new-user');
    $isFacilityManagement = request()->is('facility/list-facility') || request()->is('facility/new-unit') || request()->is('facility/new-facility');
@endphp

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
        <li class="menu-item {{ $isDashboard ? 'active' : '' }}">
            <a href="{{ url('/') }}" class="menu-link text-white {{ $isDashboard ? 'active' : '' }}">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>{{ __('ui.dashboard') }}</div>
            </a>
        </li>

        <li class="menu-item {{ $isForms ? 'active' : '' }}">
            <a href="{{ url('/form') }}" class="menu-link text-white {{ $isForms ? 'active' : '' }}">
                <i class="menu-icon tf-icons ti ti-forms"></i>
                <div>{{ __('ui.forms') }}</div>
            </a>
        </li>

        @isAdmin
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">{{ __('ui.admin_settings') }}</span>
            </li>

            <li class="menu-item {{ $isFormArchive ? 'active' : '' }}">
                <a href="{{ url('/form/archive') }}" class="menu-link text-white {{ $isFormArchive ? 'active' : '' }}">
                    <i class="menu-icon tf-icons ti ti-archive"></i>
                    <div>{{ __('ui.form_archive') }}</div>
                </a>
            </li>

            <li class="menu-item {{ $isFormManagement ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle text-white {{ $isFormManagement ? 'active' : '' }}">
                    <i class="menu-icon tf-icons ti ti-layout-sidebar"></i>
                    <div>{{ __('ui.form_management') }}</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('form/new-form') ? 'active' : '' }}"><a href="/form/new-form" class="menu-link {{ request()->is('form/new-form') ? 'active' : '' }}"><div>{{ __('ui.new_form') }}</div></a></li>
                    <li class="menu-item {{ request()->is('form/new-subform') ? 'active' : '' }}"><a href="/form/new-subform" class="menu-link {{ request()->is('form/new-subform') ? 'active' : '' }}"><div>{{ __('ui.new_subform') }}</div></a></li>
                    <li class="menu-item {{ request()->is('form/form-attachement') ? 'active' : '' }}"><a href="/form/form-attachement" class="menu-link {{ request()->is('form/form-attachement') ? 'active' : '' }}"><div>{{ __('ui.form_attach') }}</div></a></li>
                    <li class="menu-item {{ request()->is('form/list') ? 'active' : '' }}"><a href="/form/list" class="menu-link {{ request()->is('form/list') ? 'active' : '' }}" target="_blank"><div>{{ __('ui.all_forms') }}</div></a></li>
                </ul>
            </li>

            <li class="menu-item {{ $isUserManagement ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle text-white {{ $isUserManagement ? 'active' : '' }}">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div>{{ __('ui.user_management') }}</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('user/list-user') ? 'active' : '' }}"><a href="/user/list-user" class="menu-link {{ request()->is('user/list-user') ? 'active' : '' }}"><div>{{ __('ui.all_users') }}</div></a></li>
                    <li class="menu-item {{ request()->is('user/list-admin') ? 'active' : '' }}"><a href="/user/list-admin" class="menu-link {{ request()->is('user/list-admin') ? 'active' : '' }}"><div>{{ __('ui.all_admins') }}</div></a></li>
                    <li class="menu-item {{ request()->is('user/new-user') ? 'active' : '' }}"><a href="/user/new-user" class="menu-link {{ request()->is('user/new-user') ? 'active' : '' }}"><div>{{ __('ui.new_user') }}</div></a></li>
                </ul>
            </li>

            <li class="menu-item {{ $isFacilityManagement ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle text-white {{ $isFacilityManagement ? 'active' : '' }}">
                    <i class="menu-icon tf-icons ti ti-building-factory"></i>
                    <div>{{ __('ui.facility_management') }}</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ request()->is('facility/list-facility') ? 'active' : '' }}"><a href="/facility/list-facility" class="menu-link {{ request()->is('facility/list-facility') ? 'active' : '' }}"><div>{{ __('ui.all_facilities') }}</div></a></li>
                    <li class="menu-item {{ request()->is('facility/new-unit') ? 'active' : '' }}"><a href="/facility/new-unit" class="menu-link {{ request()->is('facility/new-unit') ? 'active' : '' }}"><div>{{ __('ui.all_units') }}</div></a></li>
                    <li class="menu-item {{ request()->is('facility/new-facility') ? 'active' : '' }}"><a href="/facility/new-facility" class="menu-link {{ request()->is('facility/new-facility') ? 'active' : '' }}"><div>{{ __('ui.new_facility') }}</div></a></li>
                    <li class="menu-item {{ request()->is('facility/new-unit') ? 'active' : '' }}"><a href="/facility/new-unit" class="menu-link {{ request()->is('facility/new-unit') ? 'active' : '' }}"><div>{{ __('ui.new_unit') }}</div></a></li>
                </ul>
            </li>
        @endisAdmin
    </ul>
</aside>
