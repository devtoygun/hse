@extends('guest')

@section('content')
    <div class="d-none d-lg-flex col-lg-7 p-0">
        <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
            <img
                src="{{ asset('assets/img/illustrations/girl-verify-password-light.png') }}"
                alt="Sifre yenileme"
                class="img-fluid my-5 auth-illustration"
                data-app-light-img="illustrations/girl-verify-password-light.png"
                data-app-dark-img="illustrations/girl-verify-password-dark.png" />

            <img
                src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}"
                alt="Arka plan"
                class="platform-bg"
                data-app-light-img="illustrations/bg-shape-image-light.png"
                data-app-dark-img="illustrations/bg-shape-image-dark.png" />
        </div>
    </div>

    <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4 text-white" style="background-color: #242745;">
        <div class="w-px-400 mx-auto">
            <h4 class="mb-1 text-white">{{ $title }}</h4>
            <p class="mb-4">{{ $description }}</p>

            <form id="sendCodeForm" method="POST" action="javascript:;">
                @csrf

                <div class="mb-3">
                    <label class="form-label text-white" for="email">E-posta</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ request('email') }}" required autofocus>
                </div>

                <button id="sendCodeButton" type="button" onclick="sendCode(event)" class="btn btn-primary d-grid w-100">
                    <span class="button-text">Kod Gonder</span>
                </button>
            </form>

            <form id="twoStepsForm" class="mt-4 d-none" action="javascript:;" method="POST">
                <input type="hidden" id="otp" name="otp" value="">
                <input type="hidden" id="otpEmail" name="email" value="{{ request('email') }}">

                <div class="mb-3">
                    <div class="auth-input-wrapper d-flex align-items-center justify-content-sm-between numeral-mask-wrapper">
                        <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1" autofocus />
                        <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1" />
                        <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1" />
                        <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1" />
                        <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1" />
                        <input type="text" class="form-control auth-input h-px-50 text-center numeral-mask mx-1 my-2" maxlength="1" />
                    </div>
                </div>

                <button type="button" onclick="verifyCode(event)" class="btn btn-primary d-grid w-100 mb-3">Kodu Dogrula</button>
                <div class="text-center text-white-50">
                    Kodu alamadiniz mi?
                    <a class="text-white" href="javascript:void(0);" onclick="resendCode(event)">Tekrar Gonder</a>
                </div>
            </form>

            <form id="setPasswordForm" class="mt-4 d-none" action="javascript:;" method="POST">
                <input type="hidden" id="pwEmail" name="email" value="{{ request('email') }}">

                <div class="mb-3 form-password-toggle">
                    <label class="form-label text-white" for="new_password">Yeni Sifre</label>
                    <div class="input-group input-group-merge">
                        <input type="password" id="new_password" class="form-control" name="password" placeholder="********" />
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                </div>
                <div class="mb-3 form-password-toggle">
                    <label class="form-label text-white" for="new_password_confirm">Yeni Sifre (Tekrar)</label>
                    <div class="input-group input-group-merge">
                        <input type="password" id="new_password_confirm" class="form-control" name="password_confirmation" placeholder="********" />
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                </div>

                <button type="button" onclick="setNewPassword(event)" class="btn btn-primary d-grid w-100 mb-3">Yeni Sifreyi Kaydet</button>
                <div class="text-center">
                    <a class="text-white" href="{{ route('auth.login') }}">
                        <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                        Girise Don
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function showForm(idToShow) {
            $("#sendCodeForm").addClass("d-none");
            $("#twoStepsForm").addClass("d-none");
            $("#setPasswordForm").addClass("d-none");
            $(idToShow).removeClass("d-none");
        }

        function normalizeEmail() {
            return ($("#email").val() || "").trim().toLowerCase();
        }

        function setSendCodeLoading(isLoading) {
            var button = $("#sendCodeButton");
            if (!button.length) return;

            button.prop("disabled", isLoading);

            if (isLoading) {
                button.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span><span>Gonderiliyor...</span>');
                return;
            }

            button.html('<span class="button-text">Kod Gonder</span>');
        }

        function sendCode(e) {
            if (e && typeof e.preventDefault === "function") e.preventDefault();

            var email = normalizeEmail();
            $("#otpEmail").val(email);
            $("#pwEmail").val(email);

            if (!email) {
                Swal.fire({ icon: "error", title: "E-posta zorunludur.", toast: true, position: "top-end", timer: 3000, showConfirmButton: false });
                return;
            }

            setSendCodeLoading(true);

            return fastpost("/auth/send-reset-code", { email: email })
                .then(function (res) {
                    if (res && res.status) {
                        showForm("#twoStepsForm");
                        $("#twoStepsForm input[type='text']").first().focus();
                    }
                })
                .finally(function () {
                    setSendCodeLoading(false);
                });
        }

        function resendCode(e) {
            return sendCode(e);
        }

        function getOtpValue() {
            var digits = [];
            $("#twoStepsForm input[type='text']").each(function () {
                digits.push(($(this).val() || "").trim());
            });
            return digits.join("");
        }

        function verifyCode(e) {
            if (e && typeof e.preventDefault === "function") e.preventDefault();

            var email = ($("#otpEmail").val() || "").trim().toLowerCase();
            var otp = getOtpValue();

            if (!otp || otp.length !== 6) {
                Swal.fire({ icon: "error", title: "Lutfen 6 haneli kodu giriniz.", toast: true, position: "top-end", timer: 3000, showConfirmButton: false });
                return;
            }

            $("#otp").val(otp);

            return fastpost("/auth/verify-reset-code", { email: email, otp: otp }).then(function (res) {
                if (res && res.status) {
                    showForm("#setPasswordForm");
                    $("#new_password").focus();
                }
            });
        }

        function setNewPassword(e) {
            if (e && typeof e.preventDefault === "function") e.preventDefault();

            var email = ($("#pwEmail").val() || "").trim().toLowerCase();
            var password = $("#new_password").val() || "";
            var confirm = $("#new_password_confirm").val() || "";

            if (!password || !confirm) {
                Swal.fire({ icon: "error", title: "Sifre alanlari zorunludur.", toast: true, position: "top-end", timer: 3000, showConfirmButton: false });
                return;
            }

            if (password !== confirm) {
                Swal.fire({ icon: "error", title: "Sifreler eslesmiyor.", toast: true, position: "top-end", timer: 3000, showConfirmButton: false });
                return;
            }

            if (password.length < 6) {
                Swal.fire({ icon: "error", title: "Sifre en az 6 karakter olmalidir.", toast: true, position: "top-end", timer: 3000, showConfirmButton: false });
                return;
            }

            var hasLower = /[a-z]/.test(password);
            var hasUpper = /[A-Z]/.test(password);
            var hasSpecial = /[^A-Za-z0-9]/.test(password);
            if (!hasLower || !hasUpper || !hasSpecial) {
                Swal.fire({ icon: "error", title: "Sifre; en az 1 buyuk harf, 1 kucuk harf ve 1 ozel karakter icermelidir.", toast: true, position: "top-end", timer: 4000, showConfirmButton: false });
                return;
            }

            return fastpost("/auth/set-new-password", { email: email, password: password, password_confirmation: confirm });
        }

        $(document).on("input", "#twoStepsForm input[type='text']", function () {
            var val = ($(this).val() || "").replace(/[^0-9]/g, "");
            $(this).val(val);
            if (val.length === 1) {
                $(this).next("input[type='text']").focus();
            }
        });

        $(document).on("keydown", "#twoStepsForm input[type='text']", function (e) {
            if (e.key === "Backspace" && !$(this).val()) {
                $(this).prev("input[type='text']").focus();
            }
        });
    </script>
@endsection
