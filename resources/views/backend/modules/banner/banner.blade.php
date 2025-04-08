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
    @include('backend.includes.toolbar', ['title' => 'Banner', 'breadcrumbs' => [['name' => 'Banner']]])
@endsection


@section('content')
    <div class="card">
        <div class="cst-table-header card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <i class="fa-solid fa-magnifying-glass fs-5"></i>
                    </span>
                    <input type="text" data-kt-banner-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Başlığa Göre Ara..." />
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-banner-table-toolbar="base">
                    @can(PermissionEnum::BANNER_DELETE)
                        <button type="button" id="trashed-banner" class="btn btn-sm btn-light-danger me-3 mt-2" data-bs-toggle="modal" data-bs-target="#trashed-banner-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-trash-can fs-5"></i>
                            </span>
                            Silinenler
                        </button>
                    @endcan

                    @can(PermissionEnum::BANNER_CREATE)
                        <button type="button" id="add-banner" class="btn btn-sm btn-bg-light btn-light-primary me-3 mt-2" data-bs-toggle="modal" data-bs-target="#add-banner-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fas fa-images fs-5"></i>
                            </span>
                            Banner Ekle
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="banner-lists">
                <thead>
                    <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="text-center min-w150">İşlemler</th>
                        <th class="text-center">Görsel</th>
                        <th class="text-center min-w200">Başlık</th>
                        <th class="text-center min-w100">Durum</th>
                        <th class="text-center min-w100">Sıralama</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600 text-center">
                    @foreach ($banners as $banner)
                        <tr data-id="{{ $banner->id }}">
                            <td>
                                <button type="button" class="read-view-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>

                            <td>
                                <div class="symbol symbol-50px">
                                    <img src="{{ $banner->getImage() }}" />
                                </div>
                            </td>

                            <td>{{ $banner?->content?->title }}</td>

                            <td>{!! $banner->getStatusInput() !!}</td>

                            <td>{{ $banner?->sorting }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- Banner Detayını Görüntüleme Modal Başlangıç --}}
    <div class="modal fade" id="view-banner-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">
                        Banner İnceleme
                    </h2>
                    <div id="view-banner-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-regular fa-circle-xmark fs-2"></i>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <div class="d-flex flex-column" id="view-banner-modal-scroll" data-kt-scroll="false">

                    </div>
                </div>

                <div class="modal-footer flex-center">
                    <button type="button" id="view-banner-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Banner Detayını Görüntüleme Modal Bitiş --}}


    @can(PermissionEnum::BANNER_CREATE)
        {{-- Banner Ekleme Modal Başlangıç --}}
        <div class="modal fade" id="add-banner-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form id="create-form" class="form" data-action="{{ route('admin.banner.create') }}">
                        @csrf

                        <div class="modal-header">
                            <h2 class="fw-bold">
                                Banner Ekle
                            </h2>
                            <div id="add-banner-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="fa-regular fa-circle-xmark fs-2"></i>
                            </div>
                        </div>

                        <div class="modal-body p-0">
                            <div class="d-flex flex-column" id="add-banner-modal-scroll" data-kt-scroll="false">

                                <div class="card">
                                    <div class="card-header card-header-stretch">
                                        <div class="card-toolbar m-0">
                                            <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bold" role="tablist">
                                                @foreach ($languages as $language)
                                                    <li class="nav-item" role="presentation">
                                                        <a id="tab-{{ $language->id }}" class="nav-link justify-content-center text-active-gray-800 {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" role="tab" href="#body-{{ $language->id }}">
                                                            {{ $language->name }}
                                                            <span class="symbol symbol-20px ms-4">
                                                                <img class="rounded-1" src="{{ $language->getImage() }}">
                                                            </span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="card-body p-0">
                                        <div class="tab-content">
                                            @foreach ($languages as $language)
                                                <div id="body-{{ $language->id }}" class="card-body p-0 tab-pane fade show {{ $loop->first ? 'active' : '' }}" role="tabpanel" aria-labelledby="tab-{{ $language->id }}">
                                                    <div class="card">
                                                        <div class="card-body">

                                                            @if ($loop->first)
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
                                                                            Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 1920x1080
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <div class="fv-row mb-7">
                                                                <label class="fs-6 fw-semibold mb-2">Başlık ( {{ $language?->getCodeUppercase() }} )</label>
                                                                <input type="text" name="{{ $language?->code }}[title]" class="form-control form-control-solid" placeholder="Başlık" />
                                                            </div>
                                                            <div class="fv-row mb-7">
                                                                <label class=" fs-6 fw-semibold mb-2">Açıklama ( {{ $language?->getCodeUppercase() }} )</label>
                                                                <textarea name="{{ $language?->code }}[description]" class="form-control form-control-solid" id="description_{{ $language?->id }}" maxlength="500" rows="4" placeholder="Açıklama"></textarea>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6 fv-row mb-4">
                                                                    <label class=" fs-6 fw-semibold mb-2">Buton Başlık ( {{ $language?->getCodeUppercase() }} )</label>
                                                                    <input type="text" name="{{ $language?->code }}[button_title]" class="form-control form-control-solid" placeholder="Buton Başlık" />
                                                                </div>
                                                                <div class="col-md-6 fv-row mb-4">
                                                                    <label class=" fs-6 fw-semibold mb-2">Link ( {{ $language?->getCodeUppercase() }} )</label>
                                                                    <input type="url" name="{{ $language?->code }}[url]" class="form-control form-control-solid" placeholder="Buton Link" />
                                                                </div>
                                                            </div>

                                                            @if ($loop->first)
                                                                <div class="row cst-mobil-checkbox">
                                                                    <div class="col-md-12">
                                                                        <label class="required fs-6 fw-semibold mb-2">Sıralama</label>
                                                                    </div>
                                                                    <div class="col-md-6 fv-row mb-4">
                                                                        <input type="number" name="sorting" class="form-control form-control-solid" id="sorting" placeholder="Sıralama" min="1" required />
                                                                    </div>
                                                                    <div class="col-md-6 fv-row mb-4 mt-2 px-7">
                                                                        <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                                                            <input type="checkbox" name="status" class="form-check-input" id="status" checked>
                                                                            <span class="form-check-label fs-6 fw-semibold mx-4" for="status">
                                                                                Aktif
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer flex-center">
                            <button type="button" id="add-banner-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                                Kapat
                            </button>

                            <button type="submit" id="add-banner-btn" class="btn btn-light-primary">
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
        {{-- Banner Ekleme Modal Bitiş --}}
    @endcan


    @can(PermissionEnum::BANNER_UPDATE)
        {{-- Banner Güncelleme Modal Başlangıç --}}
        <div class="modal fade" id="update-banner-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Banner Güncelle
                        </h2>
                        <div id="update-banner-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="update-banner-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="update-banner-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>

                        <button type="submit" form="update-form" id="update-banner-btn" class="btn btn-light-primary">
                            <span class="indicator-label">Güncelle</span>
                            <span class="indicator-progress">
                                Lütfen Bekleyiniz...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Banner Güncelleme Modal Bitiş --}}
    @endcan


    @can(PermissionEnum::BANNER_DELETE)
        {{-- Silinen Banner Listesi Başlangıç --}}
        <div class="modal fade" id="trashed-banner-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Silinmiş Bannerlar</h2>
                        <div id="trashed-banner-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body py-10 px-lg-10">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <i class="fa-solid fa-magnifying-glass fs-5"></i>
                            </span>
                            <input type="text" data-kt-banner-trashed-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Nedene Göre Ara..." />
                        </div>

                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="trashed-banner-lists">
                            <thead>
                                <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center min-w150">İşlemler</th>
                                    <th class="text-center min-w150">Görsel</th>
                                    <th class="text-center min-w150">Başlık</th>
                                    <th class="text-center min-w150">Silinme Nedeni</th>
                                    <th class="text-center min-w150">Sıralama</th>
                                </tr>
                            </thead>

                            <tbody class="fw-semibold text-gray-600 text-center">
                                @foreach ($deletedBanners as $banner)
                                    <tr data-id="{{ $banner->id }}">
                                        <td>
                                            <button type="button" class="read-trashed-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="trashed-restore-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                                                <i class="fa-solid fa-recycle"></i>
                                            </button>
                                            @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
                                                <button type="button" class="trashed-remove-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                                                    <i class="fas fa-dumpster-fire"></i>
                                                </button>
                                            @endhasrole
                                        </td>

                                        <td>
                                            <div class="symbol symbol-50px">
                                                <img src="{{ $banner->getImage() }}" />
                                            </div>
                                        </td>

                                        <td>{{ $banner?->content?->title }}</td>

                                        <td>{{ $banner?->deleted_description }}</td>

                                        <td>{{ $banner?->sorting }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-banner-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Banner Listesi Bitiş --}}

        {{-- Banner Silme Modal Başlangıç --}}
        <div class="modal fade" id="delete-banner-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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

                    <div class="modal-footer justify-content-center border-0 pt-0">
                        <button type="button" class="btn btn-light-primary me-3" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="button" class="btn btn-light-danger me-3 banner-delete-btn" data-id="">Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Banner Silme Modal Bitiş --}}

        {{-- Silinen Bannerın Detayını Görüntüleme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-view-banner-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Silinen Banner
                        </h2>
                        <div id="trashed-view-banner-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="trashed-view-banner-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-view-banner-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Bannerın Detayını Görüntüleme Modal Bitiş --}}

        {{-- Silinen Bannerı Geri Getirme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-restore-banner-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <button type="button" class="btn btn-icon btn-light-info mb-5 mt-5 fs-2">
                            <i class="fa-solid fa-recycle fs-3"></i>
                        </button>

                        <h3>Geri Yüklemek İstediğine Emin misin ?</h3>
                    </div>

                    <div class="modal-footer justify-content-center border-0">
                        <button type="button" class="btn btn-light-danger me-3" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="button" class="btn btn-light-primary me-3 trashed-restore-banner-delete-btn" data-id="">Geri Yükle</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Bannerı Geri Getirme Modal Bitiş --}}
    @endcan



    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        {{-- Kalıcı Olarak Silme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-remove-banner-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <button type="button" class="btn btn-icon btn-light-danger mb-5 mt-5 fs-2">
                            <i class="fas fa-dumpster-fire fs-3"></i>
                        </button>

                        <h3>Kalıcı Olarak Silmek İstediğine Emin misin ?</h3>
                        <small class="text-danger fw-bold">( Bu İşlem Geri Alınamaz !!! )</small>
                    </div>

                    <div class="modal-footer justify-content-center border-0">
                        <button type="button" class="btn btn-light-primary me-3" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="button" class="btn btn-light-danger me-3 trashed-remove-banner-delete-btn" data-id="">Kalıcı Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Kalıcı Olarak Silme Modal Bitiş --}}
    @endhasrole

@endsection

@section('js')
    <script src="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/banner/datatable/datatable.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/banner/view/read-view.js') }}"></script>

    @can(PermissionEnum::BANNER_CREATE)
        <script src="{{ asset('backend/assets/js/modules/banner/create/add.js') }}"></script>

        <script>
            @foreach ($languages as $al)
                $('#description_{{ $al->id }}').maxlength({
                    alwaysShow: true,
                    warningClass: "badge badge-danger",
                    limitReachedClass: "badge badge-success"
                }).on('focus input', function() {
                    let length = $(this).val().length;

                    if (length >= 1) {
                        $(".bootstrap-maxlength").removeClass("badge-danger").addClass("badge-success"); // Yeşil yap
                    } else {
                        $(".bootstrap-maxlength").removeClass("badge-success").addClass("badge-danger"); // Kırmızı yap
                    }
                });
            @endforeach
        </script>
    @endcan

    @can(PermissionEnum::BANNER_UPDATE)
        <script src="{{ asset('backend/assets/js/modules/banner/update/change-status.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/banner/update/get-edit.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/banner/update/update.js') }}"></script>
    @endcan

    @can(PermissionEnum::BANNER_DELETE)
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
        <script src="{{ asset('backend/assets/js/modules/banner/delete/get-delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/banner/delete/delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/banner/delete/get-trashed.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/banner/delete/get-trashed-restore.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/banner/delete/trashed-restore.js') }}"></script>
    @endcan

    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        <script src="{{ asset('backend/assets/js/modules/banner/remove/get-trashed-remove.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/banner/remove/trashed-remove.js') }}"></script>
    @endhasrole
@endsection
