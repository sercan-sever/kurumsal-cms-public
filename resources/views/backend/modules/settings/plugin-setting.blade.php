@extends('backend.layouts.app')

@use(App\Enums\Permissions\PermissionEnum)

@section('toolbar')
    @include('backend.includes.toolbar', [
        'viewButton' => true,
        'viewButtonId' => $plugin?->id,
        'title' => 'Eklenti Ayarları',
        'breadcrumbs' => [
            [
                'name' => 'Tüm Ayarlar',
                'url' => route('admin.setting'),
            ],
            [
                'name' => 'Eklenti Ayarları',
            ],
        ],
    ])
@endsection


@section('css')
@endsection


@section('content')
    <div class="card">
        <div class="card-header card-header-stretch">
            <div class="card-title">
                Eklenti Ayarları
            </div>
        </div>

        <form class="form" data-action="{{ route('admin.setting.plugin.update') }}" id="update-plugin-form">
            @csrf

            <input type="hidden" name="setting_id" value="{{ $setting?->id }}">

            <div class="card-body">

                <h5 class="mb-11 d-flex align-items-center">
                    <span class="bullet bg-danger w-15px me-3"></span> Google ReCAPTCHA
                </h5>

                <div class="row mb-7">
                    <div class="col-md-6 fv-row mb-4">
                        <label class="fs-5 fw-semibold mb-2">ReCAPTCHA Site Key</label>

                        <div class="position-relative mb-3">
                            <input id="recaptchaSiteKeyInput" name="recaptcha_site_key" value="{{ $plugin?->recaptcha_site_key }}" class="form-control form-control-solid" type="password" placeholder="ReCAPTCHA Site Key" />
                            <span id="toggleRecaptchaSiteKey" class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" style="cursor: pointer;">
                                <i class="bi bi-eye-slash fs-2"></i>
                                <i class="bi bi-eye fs-2 d-none"></i>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 fv-row mb-4">
                        <label class="fs-5 fw-semibold mb-2">ReCAPTCHA Secret Key</label>

                        <div class="position-relative mb-3">
                            <input id="recaptchaSecretKeyInput" name="recaptcha_secret_key" value="{{ $plugin?->recaptcha_secret_key }}" class="form-control form-control-solid" type="password" placeholder="ReCAPTCHA Secret Key" />
                            <span id="toggleRecaptchaSecretKey" class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" style="cursor: pointer;">
                                <i class="bi bi-eye-slash fs-2"></i>
                                <i class="bi bi-eye fs-2 d-none"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="separator separator-dashed mb-11"></div>

                <h5 class="mb-11 d-flex align-items-center">
                    <span class="bullet bg-danger w-15px me-3"></span> Google Analytics 4
                </h5>

                <div class="fv-row mb-4">
                    <label class="fs-5 fw-semibold mb-2">Ölçüm Kimliği</label>

                    <div class="position-relative mb-3">
                        <input id="analyticsFourInput" name="google_analytic" value="{{ $plugin?->analytics_four }}" class="form-control form-control-solid" type="password" placeholder="Ölçüm Kimliği" />
                        <span id="toggleAnalyticsFour" class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" style="cursor: pointer;">
                            <i class="bi bi-eye-slash fs-2"></i>
                            <i class="bi bi-eye fs-2 d-none"></i>
                        </span>
                    </div>
                </div>
            </div>

            @can(PermissionEnum::PLUGIN_UPDATE)
                <div class="card-footer text-center">
                    <button type="submit" id="update-plugin-btn" class="btn btn-light-primary">
                        <span class="indicator-label">Güncelle</span>
                        <span class="indicator-progress">
                            Lütfen Bekleyiniz...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            @endcan
        </form>
    </div>


    {{-- Eklenti Ayarlar Son Güncelleme Görüntüleme Modal Başlangıç --}}
    <div class="modal fade" id="view-plugin-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h2 class="fw-bold">
                        Son Güncelleme
                    </h2>
                    <div id="view-plugin-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-regular fa-circle-xmark fs-2"></i>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <div class="d-flex flex-column" id="view-plugin-modal-scroll" data-kt-scroll="false">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center flex-row-fluid flex-wrap">
                                <div class="col-md-10 fv-row mb-4 mt-4">
                                    <div class="mb-2 border-dashed border-gray-300 rounded">
                                        <div class="p-4 text-center">
                                            <span class="text-gray-800 text-hover-primary fs-4 fw-bold updated-name">

                                            </span>
                                            <span class="text-muted fw-semibold d-block fs-6 mb-4 mt-4 updated-email">

                                            </span>
                                            <span class="text-muted fw-semibold d-block fs-7 mb-4 mt-4 updated-statu">

                                            </span>
                                            <span class="text-muted fw-semibold d-block fs-7 mb-4 mt-4 updated-date">

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer flex-center border-0">
                    <button type="button" id="view-plugin-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Eklenti Ayarlar Son Güncelleme Görüntüleme Modal Bitiş --}}
@endsection


@section('js')
    <script src="{{ asset('backend/assets/js/modules/settings/plugin/view/read-view.js') }}"></script>

    @can(PermissionEnum::PLUGIN_UPDATE)
        <script src="{{ asset('backend/assets/js/modules/settings/plugin/update/update.js') }}"></script>

        <script>
            $('#toggleRecaptchaSiteKey').on('click', function() {
                var passwordInput = $('#recaptchaSiteKeyInput');
                var eyeSlashIcon = $(this).find('.bi-eye-slash');
                var eyeIcon = $(this).find('.bi-eye');

                // Şifreyi görünür yapma/gizleme işlemi
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    eyeSlashIcon.addClass('d-none');
                    eyeIcon.removeClass('d-none');
                } else {
                    passwordInput.attr('type', 'password');
                    eyeSlashIcon.removeClass('d-none');
                    eyeIcon.addClass('d-none');
                }
            });

            $('#toggleRecaptchaSecretKey').on('click', function() {
                var passwordInput = $('#recaptchaSecretKeyInput');
                var eyeSlashIcon = $(this).find('.bi-eye-slash');
                var eyeIcon = $(this).find('.bi-eye');

                // Şifreyi görünür yapma/gizleme işlemi
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    eyeSlashIcon.addClass('d-none');
                    eyeIcon.removeClass('d-none');
                } else {
                    passwordInput.attr('type', 'password');
                    eyeSlashIcon.removeClass('d-none');
                    eyeIcon.addClass('d-none');
                }
            });

            $('#toggleAnalyticsFour').on('click', function() {
                var passwordInput = $('#analyticsFourInput');
                var eyeSlashIcon = $(this).find('.bi-eye-slash');
                var eyeIcon = $(this).find('.bi-eye');

                // Şifreyi görünür yapma/gizleme işlemi
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    eyeSlashIcon.addClass('d-none');
                    eyeIcon.removeClass('d-none');
                } else {
                    passwordInput.attr('type', 'password');
                    eyeSlashIcon.removeClass('d-none');
                    eyeIcon.addClass('d-none');
                }
            });
        </script>
    @endcan
@endsection
