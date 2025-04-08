@extends('backend.layouts.app')

@use(App\Enums\Permissions\PermissionEnum)
@use(App\Enums\Images\ImageTypeEnum)
@use(App\Enums\Images\ImageSizeEnum)

@section('toolbar')
    @include('backend.includes.toolbar', [
        'viewButton' => true,
        'viewButtonId' => $logo?->id,
        'title' => 'Logo Ayarları',
        'breadcrumbs' => [
            [
                'name' => 'Tüm Ayarlar',
                'url' => route('admin.setting'),
            ],
            [
                'name' => 'Logo Ayarları',
            ],
        ],
    ])
@endsection


@section('css')
    <style>
        .image-input-placeholder {
            background-image: url("{{ asset('backend/assets/media/svg/files/blank-image.svg') }}");
        }

        [data-bs-theme="dark"] .image-input-placeholder {
            background-image: url("{{ asset('backend/assets/media/svg/files/blank-image-dark.svg') }}");
        }
    </style>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border card-favicon border-dashed border-gray-300 rounded card-flush py-4">
                <form class="form" data-action="{{ route('admin.setting.logo.favicon.update') }}" id="update-favicon-form">
                    @csrf

                    <input type="hidden" name="setting_id" value="{{ $setting->id }}">

                    <div class="card-body text-center pt-0">
                        <div class="fv-row mb-7">
                            <label class="d-block fw-semibold fs-6">Favicon</label>

                            @if (!empty($logo?->favicon))
                                <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $logo?->getBackendFavicon() }})">
                                @else
                                    <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true">
                            @endif

                            <div class="image-input-wrapper w-100px h-100px"></div>

                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change Favicon">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="image" accept="{{ ImageTypeEnum::MIME_ACCEPT->value }}" />
                                <input type="hidden" name="image_remove" />
                            </label>
                            @can(PermissionEnum::LOGO_UPDATE)
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel Favicon">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove Favicon">
                                    <i class="bi bi-x fs-2"></i>
                                </span>
                            @endcan
                        </div>

                        <div class="text-muted fs-7">
                            <small class="text-danger">
                                Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_05->value }} | W-H: 100x100
                            </small>
                        </div>
                    </div>
            </div>
            @can(PermissionEnum::LOGO_UPDATE)
                <div class="card-footer p-0 border-top-0 text-center">
                    <button type="submit" id="update-general-btn" class="btn btn-light-primary">
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
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border card-header-white border-dashed border-gray-300 rounded card-flush py-4">
            <form class="form" data-action="{{ route('admin.setting.logo.header.white.update') }}" id="update-header-white-form">
                @csrf

                <input type="hidden" name="setting_id" value="{{ $setting->id }}">

                <div class="card-body text-center pt-0">
                    <div class="fv-row mb-7">
                        <label class="d-block fw-semibold fs-6">Header Beyaz</label>

                        @if (!empty($logo?->header_white))
                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $logo?->getBackendHeaderWhite() }})">
                            @else
                                <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true">
                        @endif
                        <div class="image-input-wrapper w-100px h-100px"></div>

                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change Header Beyaz">
                            <i class="bi bi-pencil-fill fs-7"></i>
                            <input type="file" name="image" accept="{{ ImageTypeEnum::MIME_ACCEPT->value }}" />
                            <input type="hidden" name="image_remove" />
                        </label>
                        @can(PermissionEnum::LOGO_UPDATE)
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel Header Beyaz">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove Header Beyaz">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        @endcan
                    </div>

                    <div class="text-muted fs-7">
                        <small class="text-danger">
                            Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 250x100
                        </small>
                    </div>
                </div>
        </div>
        @can(PermissionEnum::LOGO_UPDATE)
            <div class="card-footer p-0 border-top-0 border-top-0 text-center">
                <button type="submit" id="update-general-btn" class="btn btn-light-primary">
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
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border card-header-dark border-dashed border-gray-300 rounded card-flush py-4">
            <form class="form" data-action="{{ route('admin.setting.logo.header.dark.update') }}" id="update-header-dark-form">
                @csrf

                <input type="hidden" name="setting_id" value="{{ $setting->id }}">

                <div class="card-body text-center pt-0">
                    <div class="fv-row mb-7">
                        <label class="d-block fw-semibold fs-6">Header Koyu</label>

                        @if (!empty($logo?->header_dark))
                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $logo?->getBackendHeaderDark() }})">
                            @else
                                <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true">
                        @endif
                        <div class="image-input-wrapper w-100px h-100px"></div>

                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change Header Koyu">
                            <i class="bi bi-pencil-fill fs-7"></i>
                            <input type="file" name="image" accept="{{ ImageTypeEnum::MIME_ACCEPT->value }}" />
                            <input type="hidden" name="image_remove" />
                        </label>
                        @can(PermissionEnum::LOGO_UPDATE)
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel Header Koyu">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove Header Koyu">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        @endcan
                    </div>

                    <div class="text-muted fs-7">
                        <small class="text-danger">
                            Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 250x100
                        </small>
                    </div>
                </div>
        </div>
        @can(PermissionEnum::LOGO_UPDATE)
            <div class="card-footer p-0 border-top-0 text-center">
                <button type="submit" id="update-general-btn" class="btn btn-light-primary">
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
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border card-footer-white border-dashed border-gray-300 rounded card-flush py-4">
            <form class="form" data-action="{{ route('admin.setting.logo.footer.white.update') }}" id="update-footer-white-form">
                @csrf

                <input type="hidden" name="setting_id" value="{{ $setting->id }}">

                <div class="card-body text-center pt-0">
                    <div class="fv-row mb-7">
                        <label class="d-block fw-semibold fs-6">Footer Beyaz</label>

                        @if (!empty($logo?->footer_white))
                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $logo?->getBackendFooterWhite() }})">
                            @else
                                <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true">
                        @endif
                        <div class="image-input-wrapper w-100px h-100px"></div>

                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change Footer Beyaz">
                            <i class="bi bi-pencil-fill fs-7"></i>
                            <input type="file" name="image" accept="{{ ImageTypeEnum::MIME_ACCEPT->value }}" />
                            <input type="hidden" name="image_remove" />
                        </label>
                        @can(PermissionEnum::LOGO_UPDATE)
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel Footer Beyaz">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove Footer Beyaz">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        @endcan
                    </div>

                    <div class="text-muted fs-7">
                        <small class="text-danger">
                            Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 250x100
                        </small>
                    </div>
                </div>
        </div>
        @can(PermissionEnum::LOGO_UPDATE)
            <div class="card-footer p-0 border-top-0 text-center">
                <button type="submit" id="update-general-btn" class="btn btn-light-primary">
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
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border card-footer-dark border-dashed border-gray-300 rounded card-flush py-4">
            <form class="form" data-action="{{ route('admin.setting.logo.footer.dark.update') }}" id="update-footer-dark-form">
                @csrf

                <input type="hidden" name="setting_id" value="{{ $setting->id }}">

                <div class="card-body text-center pt-0">
                    <div class="fv-row mb-7">
                        <label class="d-block fw-semibold fs-6">Footer Koyu</label>

                        @if (!empty($logo?->footer_dark))
                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $logo?->getBackendFooterDark() }})">
                            @else
                                <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true">
                        @endif
                        <div class="image-input-wrapper w-100px h-100px"></div>

                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change Footer Koyu">
                            <i class="bi bi-pencil-fill fs-7"></i>
                            <input type="file" name="image" accept="{{ ImageTypeEnum::MIME_ACCEPT->value }}" />
                            <input type="hidden" name="image_remove" />
                        </label>
                        @can(PermissionEnum::LOGO_UPDATE)
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel Footer Koyu">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove Footer Koyu">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        @endcan
                    </div>

                    <div class="text-muted fs-7">
                        <small class="text-danger">
                            Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 250x100
                        </small>
                    </div>
                </div>
        </div>
        @can(PermissionEnum::LOGO_UPDATE)
            <div class="card-footer p-0 border-top-0 text-center">
                <button type="submit" id="update-general-btn" class="btn btn-light-primary">
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
    </div>
    </div>

    {{-- Logo Ayarları Son Güncelleme Görüntüleme Modal Başlangıç --}}
    <div class="modal fade" id="view-logo-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h2 class="fw-bold">
                        Son Güncelleme
                    </h2>
                    <div id="view-logo-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-regular fa-circle-xmark fs-2"></i>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <div class="d-flex flex-column" id="view-logo-modal-scroll" data-kt-scroll="false">
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
                    <button type="button" id="view-logo-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Logo Ayarları Son Güncelleme Görüntüleme Modal Bitiş --}}
@endsection


@section('js')
    <script src="{{ asset('backend/assets/js/modules/settings/logo/view/read-view.js') }}"></script>

    @can(PermissionEnum::LOGO_UPDATE)
        <script src="{{ asset('backend/assets/js/modules/settings/logo/update/update.js') }}"></script>
    @endcan
@endsection
