<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="@yield('htmlClass', 'light-style layout-navbar-fixed layout-menu-fixed')"
    dir="ltr"
    data-theme="@yield('theme', 'theme-default')"
    data-assets-path="{{ asset('assets') }}/"
    data-template="@yield('template', 'vertical-menu-template')">
    <head>
        @include('layout.head', ['pageTitle' => $pageTitle ?? config('app.name')])
        @stack('styles')
    </head>

    <body>
        @include('layout.language-links')

        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                @include('layout.sidebar')

                <div class="layout-page">
                    @include('layout.navbar')

                    <div class="content-wrapper">
                        <div class="container-xxl flex-grow-1 container-p-y">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layout.scripts')
        @stack('scripts')
        @yield('script')
    </body>
</html>
