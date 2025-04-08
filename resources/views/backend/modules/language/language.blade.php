@extends('backend.layouts.app')

@use(App\Enums\Roles\RoleEnum)
@use(App\Enums\Permissions\PermissionEnum)
@use(App\Enums\Images\ImageTypeEnum)
@use(App\Enums\Images\ImageSizeEnum)

@section('css')
    <link href="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .image-input-placeholder {
            background-image: url("{{ asset('backend/assets/media/svg/files/blank-image.svg') }}");
        }

        [data-bs-theme="dark"] .image-input-placeholder {
            background-image: url("{{ asset('backend/assets/media/svg/files/blank-image-dark.svg') }}");
        }
    </style>
@endsection

@section('toolbar')
    @include('backend.includes.toolbar', ['title' => 'Dil Yönetimi', 'breadcrumbs' => [['name' => 'Dil Yönetimi']]])
@endsection

@section('content')
    <div class="card">
        <div class="cst-table-header card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <i class="fa-solid fa-magnifying-glass fs-5"></i>
                    </span>
                    <input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Ada Göre Ara..." />
                </div>
            </div>

            @hasrole([RoleEnum::SUPER_ADMIN])
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <button type="button" id="trashed-language" class="btn btn-sm btn-light-danger me-3 mt-2" data-bs-toggle="modal" data-bs-target="#trashed-language-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-trash-can fs-5"></i>
                            </span>
                            Silinenler
                        </button>
                        <button type="button" id="add-language" class="btn btn-sm btn-bg-light btn-light-primary me-3 mt-2" data-bs-toggle="modal" data-bs-target="#add-language-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-globe fs-5"></i>
                            </span>
                            Dil Ekle
                        </button>
                    </div>
                </div>
            @endhasrole
        </div>

        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="language-lists">
                <thead>
                    <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="text-center min-w150">İşlemler</th>
                        <th class="text-center min-w100">Görsel</th>
                        <th class="text-center min-w250">Ad</th>
                        <th class="text-center min-w100">Kısa Gösterim</th>
                        <th class="text-center min-w100">Durum</th>
                        <th class="text-center min-w100">Varsayılan</th>
                        <th class="text-center min-w100">Sıralama</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600 text-center">
                    @foreach ($languages as $language)
                        <tr data-id="{{ $language?->id }}">
                            <td>
                                @hasrole([RoleEnum::SUPER_ADMIN])
                                    <button type="button" class="read-view-btn btn btn-icon btn-bg-light btn-light-warning btn-sm me-1 mb-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @endhasrole

                                @can(PermissionEnum::STATIC_TEXT_VIEW)
                                    <button type="button" class="translate-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                                        <i class="fa-solid fa-language"></i>
                                    </button>
                                @endcan

                                @hasrole([RoleEnum::SUPER_ADMIN])
                                    <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                                        <i class="fa-solid fa-pencil"></i>
                                    </button>
                                    <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @endhasrole
                            </td>

                            <td>
                                <div class="symbol symbol-50px">
                                    <img src="{{ $language->getImage() }}" />
                                </div>
                            </td>

                            <td>{{ $language->name }}</td>

                            <td>{{ $language->code }}</td>

                            <td>
                                {!! $language->getStatusInput() !!}
                            </td>
                            <td>
                                {!! $language->getDefaultIcon() !!}
                            </td>
                            <td>
                                {{ $language->sorting }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    @hasrole([RoleEnum::SUPER_ADMIN])
        {{-- Dil Detayını Görüntüleme Modal Başlangıç --}}
        <div class="modal fade" id="view-language-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Son Güncelleme
                        </h2>
                        <div id="view-language-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="view-language-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="view-language-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Dil Detayını Görüntüleme Modal Bitiş --}}


        {{-- Ekleme Modal Başlangıç --}}
        <div class="modal fade" id="add-language-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form class="form" data-action="{{ route('admin.language.create') }}" id="create-form">
                        @csrf

                        <div class="modal-header">
                            <h2 class="fw-bold">Dil Ekle</h2>
                            <div id="add-language-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="fa-regular fa-circle-xmark fs-2"></i>
                            </div>
                        </div>

                        <div class="modal-body py-10 px-lg-10">
                            <div class="scroll-y me-n7 pe-7" id="add-language-modal-scroll" data-kt-scroll="false">

                                <div class="fv-row mb-7">
                                    <label class="required d-block fw-semibold fs-6">Görsel</label>

                                    <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true">
                                        <div class="image-input-wrapper w-100px h-100px"></div>

                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <input type="file" name="image" accept="{{ ImageTypeEnum::MIME_ACCEPT->value }}" />
                                            <input type="hidden" name="image_remove" />
                                        </label>
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                    </div>

                                    <div class="text-muted fs-7">
                                        <small class="text-danger">
                                            Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB:
                                            {{ ImageSizeEnum::SIZE_1->value }} | W-H: 100x100
                                        </small>
                                    </div>
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-semibold mb-2">Dil Adı</label>
                                    <input type="text" name="name" class="form-control form-control-solid" placeholder="Dil Adı *" min="1" required />
                                </div>

                                <div class="row mb-7">
                                    <div class="col-md-6 fv-row mb-4">
                                        <label class="required fs-6 fw-semibold mb-2">Kısa Gösterim</label>
                                        <input type="text" name="code" class="form-control form-control-solid" placeholder="Kısa Gösterim *" min="1" required />
                                    </div>

                                    <div class="col-md-6 fv-row mb-4">
                                        <label class="required fs-6 fw-semibold mb-2">Sıralama</label>
                                        <input type="number" name="sorting" class="form-control form-control-solid" id="sorting" placeholder="Sıralama *" min="1" required />
                                    </div>
                                </div>

                                <div class="row cst-mobil-checkbox">
                                    <div class="col-md-3 fv-row mb-4 px-7">
                                        <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                            <input type="checkbox" name="status" class="form-check-input" id="status" checked>
                                            <span class="form-check-label fs-6 fw-semibold mx-4" for="status">
                                                Aktif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-3 fv-row mb-4 px-7">
                                        <div class="form-check form-check-custom form-check-success form-check-solid">
                                            <input type="checkbox" name="default" class="cst-default form-check-input" id="default" />
                                            <i class="fa-solid text-white fa-xmark"></i>

                                            <span class="form-check-label fs-6 fw-semibold mx-4" for="default">
                                                Varsayılan
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer flex-center">
                            <button type="button" id="add-language-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                                Kapat
                            </button>

                            <button type="submit" id="add-language-btn" class="btn btn-light-primary">
                                <span class="indicator-label">Kaydet</span>
                                <span class="indicator-progress">
                                    Lütfen Bekleyiniz...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Ekleme Modal Bitiş --}}


        {{-- Güncelleme Modal Başlangıç --}}
        <div class="modal fade" id="update-language-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form class="form" data-action="{{ route('admin.language.update') }}" id="update-form">
                        @csrf

                        <input type="hidden" name="id" id="update-id" required>

                        <div class="modal-header">
                            <h2 class="fw-bold">Dil Güncelleme</h2>
                            <div id="language-update-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="fa-regular fa-circle-xmark fs-2"></i>
                            </div>
                        </div>

                        <div class="modal-body py-10 px-lg-10">
                            <div class="scroll-y me-n7 pe-7" id="update-language-modal-scroll" data-kt-scroll="false">

                                <div class="fv-row mb-7">
                                    <label class="d-block fw-semibold fs-6">Görsel</label>

                                    <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true">
                                        <div class="image-input-wrapper w-100px h-100px"></div>

                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <input type="file" name="image" id="update-image" accept="{{ ImageTypeEnum::MIME_ACCEPT->value }}" />
                                            <input type="hidden" name="image_remove" />
                                        </label>
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                    </div>

                                    <div class="text-muted fs-7">
                                        <small class="text-danger">
                                            Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB:
                                            {{ ImageSizeEnum::SIZE_1->value }} | W-H: 100x100
                                        </small>
                                    </div>
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-semibold mb-2">Dil Adı</label>
                                    <input type="text" name="name" class="form-control form-control-solid" id="update-name" placeholder="Dil Adı *" min="1" required />
                                </div>

                                <div class="row mb-7">
                                    <div class="col-md-6 fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Kısa Gösterim</label>
                                        <input type="text" name="code" class="form-control form-control-solid" id="update-code" placeholder="Kısa Gösterim *" min="1" required />
                                    </div>

                                    <div class="col-md-6 fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Sıralama</label>
                                        <input type="number" name="sorting" class="form-control form-control-solid" id="update-sorting" placeholder="Sıralama *" min="1" required />
                                    </div>
                                </div>

                                <div class="row cst-mobil-checkbox">
                                    <div class="col-md-3 fv-row mb-4 px-7">
                                        <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                            <input type="checkbox" name="status" class="form-check-input" id="update-status" checked>
                                            <span class="form-check-label fs-6 fw-semibold mx-4" for="update-status">
                                                Aktif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-3 fv-row mb-4 px-7">
                                        <div class="form-check form-check-custom form-check-success form-check-solid">
                                            <input type="checkbox" name="default" class="cst-default form-check-input" id="update-default" />
                                            <i class="fa-solid text-white fa-xmark"></i>

                                            <span class="form-check-label fs-6 fw-semibold mx-4" for="update-default">
                                                Varsayılan
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer flex-center">
                            <button type="button" id="update-language-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                                Kapat
                            </button>

                            <button type="submit" id="update-language-btn" class="btn btn-light-primary">
                                <span class="indicator-label">Güncelle</span>
                                <span class="indicator-progress">
                                    Lütfen Bekleyiniz...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Güncelleme Modal Bitiş --}}


        {{-- Silme Modal Başlangıç --}}
        <div class="modal fade" id="delete-language-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <button type="button" class="btn btn-icon btn-light-danger mb-5 mt-5 fs-2">
                            <i class="fa-solid fa-trash-can fs-3"></i>
                        </button>

                        <h3>Silmek İstediğine Emin misin ?</h3>

                        <div class="fv-row mt-7">
                            <textarea name="deleted_description" id="deleted-description" class="form-control form-control-solid" rows="4" placeholder="Silme Nedeni *" minlength="5" maxlength="200"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-light-primary me-3" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="button" class="btn btn-light-danger me-3 language-delete-btn" data-id="">Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silme Modal Bitiş --}}


        {{-- Silinenler Modal Başlangıç --}}
        <div class="modal fade" id="trashed-language-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Silinmiş Diller</h2>
                        <div id="trashed-language-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body py-10 px-lg-10">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <i class="fa-solid fa-magnifying-glass fs-5"></i>
                            </span>
                            <input type="text" data-kt-customer-trashed-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Ada Göre Ara..." />
                        </div>

                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="trashed-language-lists">
                            <thead>
                                <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center min-w150">İşlemler</th>
                                    <th class="text-center min-w100">Görsel</th>
                                    <th class="text-center min-w250">Ad</th>
                                    <th class="text-center min-w100">Kısa Gösterim</th>
                                </tr>
                            </thead>

                            <tbody class="fw-semibold text-gray-600 text-center">
                                @foreach ($trashedLanguages as $language)
                                    <tr data-id="{{ $language->id }}">
                                        <td>
                                            <button type="button" class="read-delete-view-btn btn btn-icon btn-bg-light btn-light-warning btn-sm me-1 mb-1">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="trashed-restore-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                                                <i class="fa-solid fa-recycle"></i>
                                            </button>
                                        </td>

                                        <td>
                                            <div class="symbol symbol-50px">
                                                <img src="{{ $language->getImage() }}" alt="{{ $language->name }}" />
                                            </div>
                                        </td>

                                        <td>{{ $language->name }}</td>

                                        <td>{{ $language->code }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-language-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinenler Modal Bitiş --}}


        {{-- Silinen Veriyi Getirme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-restore-language-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <button type="button" class="btn btn-icon btn-light-info mb-5 mt-5 fs-2">
                            <i class="fa-solid fa-recycle fs-3"></i>
                        </button>

                        <h3>Geri Yüklemek İstediğine Emin misin ?</h3>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-light-danger me-3" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="button" class="btn btn-light-primary me-3 trashed-restore-language-delete-btn" data-id="">Geri Yükle</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Veriyi Getirme Modal Bitiş --}}
    @endhasrole
@endsection

@section('js')
    <script src="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/language/datatable/datatable.js') }}"></script>
    @hasrole([RoleEnum::SUPER_ADMIN])
        <script>
            $('#deleted-description').maxlength({
                alwaysShow: true,
                warningClass: "badge badge-danger",
                limitReachedClass: "badge badge-success"
            }).on('focus input', function() {
                let length = $(this).val().length;

                if (length >= 5) {
                    $(".bootstrap-maxlength").removeClass("badge-danger").addClass("badge-success"); // Yeşil yap
                } else {
                    $(".bootstrap-maxlength").removeClass("badge-success").addClass("badge-danger"); // Kırmızı yap
                }
            });
        </script>

        <script src="{{ asset('backend/assets/js/modules/language/view/read-view.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/language/view/read-delete-view.js') }}"></script>

        <script src="{{ asset('backend/assets/js/modules/language/create/add.js') }}"></script>

        <script src="{{ asset('backend/assets/js/modules/language/update/update.js') }}"></script>

        <script src="{{ asset('backend/assets/js/modules/language/delete/delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/language/delete/trashed-restore.js') }}"></script>
    @endhasrole
    <script src="{{ asset('backend/assets/js/modules/language/update/get.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/language/update/change-status.js') }}"></script>
@endsection
