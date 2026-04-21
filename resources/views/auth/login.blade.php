@extends('guest')

@section('content')
    <div class="d-none d-lg-flex col-lg-7 p-0">
        <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
            <img
                src="{{ asset('assets/img/illustrations/auth-login-illustration-light.png') }}"
                alt="Login"
                class="img-fluid my-5 auth-illustration"
                data-app-light-img="illustrations/auth-login-illustration-light.png"
                data-app-dark-img="illustrations/auth-login-illustration-dark.png" />

            <img
                src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}"
                alt="Background shape"
                class="platform-bg"
                data-app-light-img="illustrations/bg-shape-image-light.png"
                data-app-dark-img="illustrations/bg-shape-image-dark.png" />
        </div>
    </div>

    <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
        <div class="w-px-400 mx-auto">
            <h4 class="mb-1">{{ $title }}</h4>
            <p class="mb-6">{{ $description }}</p>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('auth.store-login') }}" class="mb-6">
                @csrf

                <div class="mb-6">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-6 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Password</label>
                        <a href="{{ route('auth.reset-password') }}">
                            <small>Forgot Password?</small>
                        </a>
                    </div>
                    <div class="input-group input-group-merge">
                        <input id="password" type="password" class="form-control" name="password" required>
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                </div>

                <div class="mb-8">
                    <div class="form-check">
                        <input id="remember" class="form-check-input" type="checkbox" name="remember" value="1">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary d-grid w-100">Login</button>
            </form>

            <p class="text-center">
                <a href="{{ route('auth.register') }}">Register</a>
                <span class="mx-2">|</span>
                <a href="{{ route('auth.reset-password') }}">Reset password</a>
            </p>
        </div>
    </div>
@endsection
