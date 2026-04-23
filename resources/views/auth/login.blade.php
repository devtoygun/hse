@extends('guest')

@push('styles')
    <style>
        .login-panel {
            background-color: #242745;
            color: #fff;
        }

        .login-panel .form-label,
        .login-panel h4,
        .login-panel p,
        .login-panel small,
        .login-panel a {
            color: #fff;
        }

        .login-panel .form-control,
        .login-panel .input-group-text {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .login-panel .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
    </style>
@endpush

@section('content')
    <div class="d-none d-lg-flex col-lg-7 p-0">
        <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
            <img
                src="{{ asset('assets/img/illustrations/auth-login-illustration-light.png') }}"
                alt="Giris"
                class="img-fluid my-5 auth-illustration"
                data-app-light-img="illustrations/auth-login-illustration-light.png"
                data-app-dark-img="illustrations/auth-login-illustration-dark.png" />

            <img
                src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}"
                alt="Arka plan sekli"
                class="platform-bg"
                data-app-light-img="illustrations/bg-shape-image-light.png"
                data-app-dark-img="illustrations/bg-shape-image-dark.png" />
        </div>
    </div>

    <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4 login-panel">
        <div class="w-px-400 mx-auto">
            <h4 class="mb-1">{{ $title }}</h4>
            <p class="mb-6">{{ $description }}</p>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form method="POST" action="javascript:;" class="mb-6">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="email">E-posta</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"  autofocus>
                </div>

                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Sifre</label>
                        <a href="{{ route('auth.reset-password') }}">
                            <small>Sifremi unuttum</small>
                        </a>
                    </div>
                    <div class="input-group input-group-merge">
                        <input id="password" type="password" class="form-control" name="password" >
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                </div>

                <button type="button" onclick="login(event)" class="btn btn-primary d-grid w-100">Giris Yap</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
<script>
    function login(e){
        if (e && typeof e.preventDefault === 'function') e.preventDefault();

        var email = ($("#email").val() || "").trim();
        var pass  = ($("#password").val() || "");

        if (!email) {
            Swal.fire({ icon: "error", title: "E-posta zorunludur.", toast: true, position: "top-end", timer: 3000, showConfirmButton: false });
            return;
        }

        // Basic email sanity check before posting.
        var emailOk = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/.test(email);
        if (!emailOk) {
            Swal.fire({ icon: "error", title: "Lutfen gecerli bir e-posta adresi giriniz.", toast: true, position: "top-end", timer: 3000, showConfirmButton: false });
            return;
        }

        if (!pass) {
            Swal.fire({ icon: "error", title: "Sifre zorunludur.", toast: true, position: "top-end", timer: 3000, showConfirmButton: false });
            return;
        }

        fastpost("/auth/login", {email: email, password: pass}, "/");
    }
</script>
@endsection
