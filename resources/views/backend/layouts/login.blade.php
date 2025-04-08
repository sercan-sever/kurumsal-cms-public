<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <title>Localkod Kurumsal Panel Giriş</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Localkod Kurumsal Panel Giriş" />
    <meta property="og:url" content="{{ url('') }}" />
    <meta property="og:site_name" content="Localkod Kurumsal Panel Giriş" />
    <link rel="canonical" href="{{ url('') }}" />
    <link rel="shortcut icon" href="{{ asset('backend/assets/media/logos/favicon.png') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('backend/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/sweetalert.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/toastify.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet" type="text/css" />

    <style>
        body {
            background-image: url("{{ asset('backend/assets/media/auth/bg10.jpg') }}");
        }

        [data-theme="dark"] body {
            background-image: url("{{ asset('backend/assets/media/auth/bg10-dark.jpg') }}");
        }
    </style>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body id="kt_body" class="app-blank app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">

            <div class="d-flex flex-lg-row-fluid">
                <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
                    <img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20" src="{{ asset('backend/assets/media/auth/agency.png') }}" alt="" />
                    <img class="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20" src="{{ asset('backend/assets/media/auth/agency-dark.png') }}" alt="" />
                    <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7">Localkod Kurumsal Panel Giriş</h1>
                    <div class="text-gray-600 fs-base text-center fw-semibold">
                        Kurumsal web sitenizin yönetimini kolaylaştırın!
                        <br />
                        İçeriklerinizi güncelleyin, sayfalarınızı düzenleyin ve ziyaretçilerinize en iyi dijital deneyimi sunun.
                        <br />
                        Başarılı bir çevrimiçi varlık oluşturmanın yolu, panelimizle size sunulan güçlü araçlardan geçiyor.
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
                <div class="bg-body d-flex flex-center rounded-4 w-md-600px p-10">
                    <div class="w-md-400px">
                        <form class="form w-100" id="login-form" data-action="{{ route('admin.login.auth') }}" action="javascript:void(0);">
                            <div class="text-center mb-11">
                                <h1 class="text-dark fw-bolder mb-3">Giriş Yap</h1>
                                <div class="text-gray-500 fw-semibold fs-6">Giriş Yapmak İçin E-Posta ve Şifrenizi
                                    Giriniz.</div>
                            </div>
                            <div class="fv-row mb-8">
                                <input type="email" placeholder="E-Posta Adresi *" required name="email" autocomplete="on" class="form-control bg-transparent" />
                            </div>
                            <div class="fv-row mb-8">
                                <div class="position-relative mb-3">
                                    <input id="passwordInput" class="form-control bg-transparent" type="password" placeholder="Şifre *" minlength="6" required name="password" autocomplete="off" />
                                    <span id="togglePassword" class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" style="cursor: pointer;">
                                        <i class="bi bi-eye-slash fs-2"></i>
                                        <i class="bi bi-eye fs-2 d-none"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="fv-row mb-8">
                                <label class="form-check form-check-inline cst-remember">
                                    <input class="form-check-input" type="checkbox" name="remember" />
                                    <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">Beni Hatırla</span>
                                </label>
                            </div>

                            <div class="d-flex justify-content-center mt-8 flex-wrap gap-3 fs-base fw-semibold mb-8">
                                {!! NoCaptcha::display() !!}
                            </div>

                            <div class="d-grid mb-10">
                                <button type="submit" class="btn btn-primary">
                                    <span class="indicator-label">Giriş Yap</span>
                                    <span class="indicator-progress">
                                        Lütfen Bekleyin...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Yükleniyor --}}
    <x-wait.wait-modal />

    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-theme-mode");
            } else {
                if (localStorage.getItem("data-theme") !== null) {
                    themeMode = localStorage.getItem("data-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-theme", themeMode);
        }
    </script>

    <script src="{{ asset('backend/assets/js/axios.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('backend/assets/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('backend/assets/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/toastify.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/admin.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/auth/login.js') }}"></script>

</body>

</html>
