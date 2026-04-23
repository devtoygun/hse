<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center w-100" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper mb-0">
                <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
                    <i class="ti ti-search ti-md me-2"></i>
                    <span class="d-none d-md-inline-block text-muted">{{ __('ui.search') }}</span>
                </a>
            </div>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
                <a class="nav-link hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" aria-label="{{ __('ui.language') }}">
                    @if (app()->getLocale() === 'tr')
                        <i class="fi fi-tr fis rounded-circle fs-3"></i>
                    @else
                        <i class="fi fi-us fis rounded-circle fs-3"></i>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item {{ app()->getLocale() === 'tr' ? 'active selected' : '' }}" href="{{ route('locale.switch', 'tr') }}" data-language="tr">
                            <i class="fi fi-tr fis rounded-circle me-1 fs-3"></i>
                            <span class="align-middle">{{ __('ui.turkish') }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active selected' : '' }}" href="{{ route('locale.switch', 'en') }}" data-language="en">
                            <i class="fi fi-us fis rounded-circle me-1 fs-3"></i>
                            <span class="align-middle">{{ __('ui.english') }}</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item me-2 me-xl-0">
                <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);" aria-label="{{ __('ui.theme') }}">
                    <i class="ti ti-moon-stars"></i>
                </a>
            </li>

            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link hide-arrow d-flex align-items-center gap-2" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="{{ auth()->user()->firstname }}" class="h-auto rounded-circle" />
                    </div>
                    <div class="d-none d-md-flex flex-column align-items-start lh-sm">
                        <span class="fw-semibold text-body">{{ auth()->user()->firstname.' '.auth()->user()->lastname }}</span>
                        <small class="text-muted">{{ auth()->user()->is_admin ? __('auth.admin') : __('auth.user') }}</small>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="{{ auth()->user()->firstname }}" class="h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ auth()->user()->firstname.' '.auth()->user()->lastname }}</span>
                                    <small class="text-muted">{{ auth()->user()->is_admin ? __('auth.admin') : __('auth.user') }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>
                    <li>
                        <a class="dropdown-item" href="/user/auth/profile">
                            <i class="ti ti-user-check me-2 ti-sm"></i>
                            <span class="align-middle">{{ __('auth.profile') }}</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="/user/auth/settings">
                            <i class="ti ti-settings me-2 ti-sm"></i>
                            <span class="align-middle">{{ __('auth.settings') }}</span>
                        </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('auth.logout-get') }}">
                            <i class="ti ti-logout me-2 ti-sm"></i>
                            <span class="align-middle">{{ __('auth.logout') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

    <div class="navbar-search-wrapper search-input-wrapper d-none">
        <input
            type="text"
            class="form-control search-input container-xxl border-0"
            placeholder="{{ __('ui.search') }}..."
            aria-label="{{ __('ui.search') }}..." />
        <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
    </div>
</nav>
