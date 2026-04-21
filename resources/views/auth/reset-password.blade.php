@extends('guest')

@section('content')
    <div class="d-none d-lg-flex col-lg-7 p-0">
        <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
            <img
                src="{{ asset('assets/img/illustrations/girl-verify-password-light.png') }}"
                alt="Reset password"
                class="img-fluid my-5 auth-illustration"
                data-app-light-img="illustrations/girl-verify-password-light.png"
                data-app-dark-img="illustrations/girl-verify-password-dark.png" />

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

            <form method="POST" action="{{ route('auth.store-reset-password') }}">
                @csrf

                <div class="mb-6">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <button type="submit" class="btn btn-primary d-grid w-100">Send reset link</button>
            </form>

            <p class="text-center mt-6">
                <a href="{{ route('auth.login') }}">Login</a>
                <span class="mx-2">|</span>
                <a href="{{ route('auth.register') }}">Register</a>
            </p>
        </div>
    </div>
@endsection
