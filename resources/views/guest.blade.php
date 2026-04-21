<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="@yield('htmlClass', 'light-style customizer-hide')"
    dir="ltr"
    data-theme="@yield('theme', 'theme-default')"
    data-assets-path="{{ asset('assets') }}/"
    data-template="@yield('template', 'vertical-menu-template-no-customizer')">
    <head>
        @include('layout.head', ['pageTitle' => $pageTitle ?? config('app.name')])
        @stack('styles')
    </head>

    <body>
        <div class="authentication-wrapper authentication-cover authentication-bg">
            <div class="authentication-inner row m-0">
                @yield('content')
            </div>
        </div>

        @include('layout.scripts')
        @stack('scripts')
        @yield('script')
    </body>
</html>
