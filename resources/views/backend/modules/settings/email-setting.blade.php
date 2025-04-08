@extends('backend.layouts.app')

@use(App\Enums\Permissions\PermissionEnum)
@use(App\Enums\Emails\EmailEngineEnum)
@use(App\Enums\Emails\EmailEncryptionEnum)

@section('toolbar')
    @include('backend.includes.toolbar', [
        'viewButton' => true,
        'viewButtonId' => $email?->id,
        'title' => 'E-Mail Ayarları',
        'breadcrumbs' => [
            [
                'name' => 'Tüm Ayarlar',
                'url' => route('admin.setting'),
            ],
            [
                'name' => 'E-Mail Ayarları',
            ],
        ],
    ])
@endsection


@section('css')
@endsection


@section('content')
    <div class="card">
        <form class="form" data-action="{{ route('admin.setting.email.update') }}" id="update-email-form">
            @csrf

            <input type="hidden" name="setting_id" value="{{ $setting?->id }}">

            <div class="card-header card-header-stretch">
                <div class="card-title">
                    E-Mail Ayarları
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 fv-row mb-4">
                        <label class="required fs-6 fw-semibold mb-2">Bildirimleri Alıcak E-Mail Adresi</label>
                        <input type="text" name="notification_email" value="{{ $email?->notification_email }}" class="form-control form-control-solid" placeholder="E-Mail Adresi *" min="1" required />
                        <div class="text-danger mt-2 fs-8">
                            İletişim ve geri bildirim için E-maillerin gönderileceği adrestir.
                        </div>
                    </div>

                    <div class="col-md-6 fv-row mb-4">
                        <label class="required fs-6 fw-semibold mb-2">Gönderici E-Mail Adresi</label>
                        <input type="text" name="sender_email" value="{{ $email?->sender_email }}" class="form-control form-control-solid" placeholder="E-Mail Adresi *" min="1" required />
                    </div>

                    <div class="col-md-6 fv-row mb-4">
                        <label class="required fs-6 fw-semibold mb-2">E-Mail Başlık</label>
                        <input type="text" name="subject" value="{{ $email?->subject }}" class="form-control form-control-solid" placeholder="E-Mail Başlık *" min="1" required />
                    </div>

                    <div class="col-md-6 fv-row mb-4">
                        <label class="required fs-6 fw-semibold mb-2">Host</label>
                        <input type="text" name="host" value="{{ $email?->host }}" class="form-control form-control-solid" placeholder="Host Adı *" min="1" required />
                    </div>

                    <div class="col-md-6 fv-row mb-4">
                        <label class="required fs-6 fw-semibold mb-2">Port</label>
                        <input type="number" name="port" value="{{ $email?->port }}" class="form-control form-control-solid" placeholder="Port *" min="1" max="65535" required />
                    </div>

                    <div class="col-md-6 fv-row mb-4">
                        <label class="required fs-6 fw-semibold mb-2">Şifreleme</label>
                        <select class="form-control form-control-solid form-select mb-2" name="encryption" id="encryption" data-live-search="true" required>
                            @foreach (EmailEncryptionEnum::cases() as $item)
                                <option value="{{ $item->value }}" {{ $email?->encryption?->value == $item->value ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 fv-row mb-4">
                        <label class="required fs-6 fw-semibold mb-2">Kullanıcı Adı</label>
                        <input type="text" name="username" value="{{ $email?->username }}" class="form-control form-control-solid" placeholder="Kullanıcı Adı *" min="1" required />
                    </div>

                    <div class="col-md-6 fv-row">
                        <label class="required fs-6 fw-semibold mb-2">Parola</label>
                        <div class="position-relative mb-3">
                            <input id="passwordInput" name="password" class="form-control form-control-solid" type="password" placeholder="Parola *" min="1" required autocomplete="off" />
                            <span id="togglePassword" class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" style="cursor: pointer;">
                                <i class="bi bi-eye-slash fs-2"></i>
                                <i class="bi bi-eye fs-2 d-none"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            @can(PermissionEnum::EMAIL_UPDATE)
                <div class="card-footer text-center">
                    <button type="submit" id="update-email-btn" class="btn btn-light-primary">
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

    <div class="card mt-8">
        <div class="card-header card-header-stretch">
            <div class="card-title">
                E-Mail Test
            </div>
        </div>
        <form class="form" data-action="{{ route('admin.setting.email.test') }}" id="test-email-form">
            @csrf

            <div class="card-body pb-0">
                <div class="row mb-7">
                    <label class="required fs-6 fw-semibold mb-2">Test İçin Mail Gönderilecek Adres</label>

                    <div class="col-md-10 fv-row mb-4">
                        <input type="text" name="email" id="send-email" class="form-control form-control-solid" placeholder="E-Mail Adresi *" min="1" required />
                    </div>
                    <div class="col-md-2 fv-row mb-4">
                        <button type="submit" id="test-email-btn" class="btn btn-light-info">
                            Mail Gönder
                            <span class="svg-icon svg-icon-3 m-0">
                                <i class="fas fa-paper-plane mx-3 p-0"></i>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>


    {{-- E-Mail Ayarlar Son Güncelleme Görüntüleme Modal Başlangıç --}}
    <div class="modal fade" id="view-email-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h2 class="fw-bold">
                        Son Güncelleme
                    </h2>
                    <div id="view-email-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-regular fa-circle-xmark fs-2"></i>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <div class="d-flex flex-column" id="view-email-modal-scroll" data-kt-scroll="false">
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
                    <button type="button" id="view-email-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- E-Mail Ayarlar Son Güncelleme Görüntüleme Modal Bitiş --}}
@endsection


@section('js')
    <script src="{{ asset('backend/assets/js/modules/settings/email/view/read-view.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/settings/email/send/test-mail.js') }}"></script>

    <script>
        // $.fn.selectpicker.Constructor.BootstrapVersion = '5';

        $('#encryption').selectpicker();
    </script>

    @can(PermissionEnum::EMAIL_UPDATE)
        <script src="{{ asset('backend/assets/js/modules/settings/email/update/update.js') }}"></script>
    @endcan
    <script>
        $('#togglePassword').on('click', function() {
            var passwordInput = $('#passwordInput');
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
@endsection
