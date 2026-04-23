@php
    $isDashboard = request()->routeIs('app.index');
    $isForms = request()->routeIs('form.index');
    $isFormArchive = request()->routeIs('form.archive');
    $isFormManagement = request()->routeIs('form.create') || request()->routeIs('form.create-subform') || request()->routeIs('form.attach') || request()->routeIs('form.list');
    $isUserManagement = request()->routeIs('user.list-user') || request()->routeIs('user.list-admin') || request()->routeIs('user.create');
    $isFacilityManagement = request()->routeIs('facility.list-facility') || request()->routeIs('facility.create-unit') || request()->routeIs('facility.create-facility');
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
            <a href="{{ route('app.index') }}" class="menu-link text-white {{ $isDashboard ? 'active' : '' }}">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div>{{ __('ui.dashboard') }}</div>
            </a>
        </li>

        <li class="menu-item {{ $isForms ? 'active' : '' }}">
            <a href="{{ route('form.index') }}" class="menu-link text-white {{ $isForms ? 'active' : '' }}">
                <i class="menu-icon tf-icons ti ti-forms"></i>
                <div>{{ __('ui.forms') }}</div>
            </a>
        </li>

        @isAdmin
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">{{ __('ui.admin_settings') }}</span>
            </li>

            <li class="menu-item {{ $isFormArchive ? 'active' : '' }}">
                <a href="{{ route('form.archive') }}" class="menu-link text-white {{ $isFormArchive ? 'active' : '' }}">
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
                    <li class="menu-item {{ request()->routeIs('form.create') ? 'active' : '' }}"><a href="{{ route('form.create') }}" class="menu-link {{ request()->routeIs('form.create') ? 'active' : '' }}"><div>{{ __('ui.new_form') }}</div></a></li>
                    <li class="menu-item {{ request()->routeIs('form.create-subform') ? 'active' : '' }}"><a href="{{ route('form.create-subform') }}" class="menu-link {{ request()->routeIs('form.create-subform') ? 'active' : '' }}"><div>{{ __('ui.new_subform') }}</div></a></li>
                    <li class="menu-item {{ request()->routeIs('form.attach') ? 'active' : '' }}"><a href="{{ route('form.attach') }}" class="menu-link {{ request()->routeIs('form.attach') ? 'active' : '' }}"><div>{{ __('ui.form_attach') }}</div></a></li>
                    <li class="menu-item {{ request()->routeIs('form.list') ? 'active' : '' }}"><a href="{{ route('form.list') }}" class="menu-link {{ request()->routeIs('form.list') ? 'active' : '' }}"><div>{{ __('ui.all_forms') }}</div></a></li>
                </ul>
            </li>

            <li class="menu-item {{ $isUserManagement ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle text-white {{ $isUserManagement ? 'active' : '' }}">
                    <i class="menu-icon tf-icons ti ti-users"></i>
                    <div>{{ __('ui.user_management') }}</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('user.list-user') ? 'active' : '' }}"><a href="{{ route('user.list-user') }}" class="menu-link {{ request()->routeIs('user.list-user') ? 'active' : '' }}"><div>{{ __('ui.all_users') }}</div></a></li>
                    <li class="menu-item {{ request()->routeIs('user.list-admin') ? 'active' : '' }}"><a href="{{ route('user.list-admin') }}" class="menu-link {{ request()->routeIs('user.list-admin') ? 'active' : '' }}"><div>{{ __('ui.all_admins') }}</div></a></li>
                    <li class="menu-item {{ request()->routeIs('user.create') ? 'active' : '' }}"><a href="{{ route('user.create') }}" class="menu-link {{ request()->routeIs('user.create') ? 'active' : '' }}"><div>{{ __('ui.new_user') }}</div></a></li>
                </ul>
            </li>

            <li class="menu-item {{ $isFacilityManagement ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle text-white {{ $isFacilityManagement ? 'active' : '' }}">
                    <i class="menu-icon tf-icons ti ti-building-factory"></i>
                    <div>{{ __('ui.facility_management') }}</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item {{ request()->routeIs('facility.list-facility') ? 'active' : '' }}"><a href="{{ route('facility.list-facility') }}" class="menu-link {{ request()->routeIs('facility.list-facility') ? 'active' : '' }}"><div>{{ __('ui.all_facilities') }}</div></a></li>
                    <li class="menu-item {{ request()->routeIs('facility.create-unit') ? 'active' : '' }}"><a href="{{ route('facility.create-unit') }}" class="menu-link {{ request()->routeIs('facility.create-unit') ? 'active' : '' }}"><div>{{ __('ui.all_units') }}</div></a></li>
                    <li class="menu-item {{ request()->routeIs('facility.create-facility') ? 'active' : '' }}"><a href="{{ route('facility.create-facility') }}" class="menu-link {{ request()->routeIs('facility.create-facility') ? 'active' : '' }}"><div>{{ __('ui.new_facility') }}</div></a></li>
                    <li class="menu-item {{ request()->routeIs('facility.create-unit') ? 'active' : '' }}"><a href="{{ route('facility.create-unit') }}" class="menu-link {{ request()->routeIs('facility.create-unit') ? 'active' : '' }}"><div>{{ __('ui.new_unit') }}</div></a></li>
                </ul>
            </li>
        @endisAdmin
    </ul>
</aside>
