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
    @include('backend.includes.toolbar', ['title' => 'Hizmetler', 'breadcrumbs' => [['name' => 'Hizmetler']]])
@endsection

@section('content')
    <div class="card">
        <div class="cst-table-header card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <i class="fa-solid fa-magnifying-glass fs-5"></i>
                    </span>
                    <input type="text" data-kt-service-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Başlığa Göre Ara..." />
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-service-table-toolbar="base">
                    @can(PermissionEnum::SERVICE_DELETE)
                        <button type="button" id="trashed-service" class="btn btn-sm btn-light-danger me-3 mt-2" data-bs-toggle="modal" data-bs-target="#trashed-service-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-trash-can fs-5"></i>
                            </span>
                            Silinenler
                        </button>
                    @endcan

                    @can(PermissionEnum::SERVICE_CREATE)
                        <button type="button" id="add-service" class="btn btn-sm btn-bg-light btn-light-primary me-3 mt-2" data-bs-toggle="modal" data-bs-target="#add-service-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-briefcase fs-5"></i>
                            </span>
                            Hizmet Ekle
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="service-lists">
                <thead>
                    <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="text-center min-w150">İşlemler</th>
                        <th class="text-center min-w150">Kapak Görsel</th>
                        <th class="text-center min-w150">Başlık</th>
                        <th class="text-center min-w100">Durum</th>
                        <th class="text-center min-w100">Sıralama</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600 text-center">
                    @foreach ($services as $service)
                        <tr data-id="{{ $service->id }}">
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
                                    <img src="{{ $service->getOtherImage() }}" />
                                </div>
                            </td>

                            <td>{{ $service?->content?->title }}</td>

                            <td>{!! $service->getStatusInput() !!}</td>

                            <td>{{ $service?->sorting }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Hizmet Detayını Görüntüleme Modal Başlangıç --}}
    <div class="modal fade" id="view-service-modal" tabindex="-1" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">
                        Hizmet İnceleme
                    </h2>
                    <div id="view-service-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-regular fa-circle-xmark fs-2"></i>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <div class="d-flex flex-column" id="view-service-modal-scroll" data-kt-scroll="false">

                    </div>
                </div>

                <div class="modal-footer flex-center">
                    <button type="button" id="view-service-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Hizmet Detayını Görüntüleme Modal Bitiş --}}

    @can(PermissionEnum::SERVICE_CREATE)
        {{-- Hizmet Ekleme Modal Başlangıç --}}
        <div class="modal fade" id="add-service-modal" aria-hidden="true" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <form id="create-form" class="form" data-action="{{ route('admin.service.create') }}">
                        @csrf

                        <div class="modal-header">
                            <h2 class="fw-bold">
                                Hizmet Ekle
                            </h2>
                            <div id="add-service-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="fa-regular fa-circle-xmark fs-2"></i>
                            </div>
                        </div>

                        <div class="modal-body p-0">
                            <div class="d-flex flex-column" id="add-service-modal-scroll" data-kt-scroll="false">

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
                                                                <div class="row cst-mobil-checkbox mb-7">
                                                                    <div class="col-md-6 fv-row mb-7 text-center">
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

                                                                    <div class="col-md-6 fv-row mb-7 text-center">
                                                                        <label class="required d-block fw-semibold fs-6">Kapak Görsel</label>

                                                                        <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true">
                                                                            <div class="image-input-wrapper w-100px h-100px"></div>

                                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                                                <input type="file" name="other_image" accept="{{ ImageTypeEnum::MIME_ACCEPT->value }}" />
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
                                                                                Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_05->value }} | W-H: 700x700
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <div class="fv-row mb-7">
                                                                <label class="required fs-6 fw-semibold mb-2">Hizmet ( {{ $language?->getCodeUppercase() }} )</label>
                                                                <input type="text" name="{{ $language?->code }}[title]" class="form-control form-control-solid" placeholder="Hizmet *" required />
                                                            </div>

                                                            <div class="fv-row mb-7">
                                                                <label class="required fs-6 fw-semibold mb-2">Kısa Açıklama ( {{ $language?->getCodeUppercase() }} )</label>
                                                                <textarea name="{{ $language?->code }}[short_description]" id="short_description_{{ $language?->id }}" class="form-control form-control-solid cst-description" rows="4" minlength="5" maxlength="2000" placeholder="Kısa Açıklama *"></textarea>
                                                            </div>

                                                            <div class="fv-row mb-7">
                                                                <label class="required fs-6 fw-semibold mb-2">Açıklama ( {{ $language?->getCodeUppercase() }} )</label>
                                                                <textarea name="{{ $language?->code }}[description]" id="editor_{{ $language?->id }}" class="form-control form-control-solid cst-description" rows="6" placeholder="Açıklama"></textarea>
                                                            </div>

                                                            @if ($loop->first)
                                                                <div class="row cst-mobil-checkbox">
                                                                    <div class="col-md-6 fv-row mb-4">
                                                                        <label for="multiple-service-image" class="fs-6 fw-semibold mb-2">Hizmet Görselleri </label>
                                                                        <input class="form-control" name="service_images[]" type="file" id="multiple-service-image" accept="{{ ImageTypeEnum::MIME_ACCEPT->value }}" multiple>

                                                                        <div class="text-muted fs-7 pt-2">
                                                                            <small class="text-danger">
                                                                                Max: 6 | Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_3->value }} | W-H: 700x700
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 fv-row mb-4">
                                                                        <label class="required fs-6 fw-semibold mb-2">Sıralama</label>
                                                                        <input type="number" name="sorting" class="form-control form-control-solid" id="sorting" placeholder="Sıralama" min="1" required />
                                                                    </div>
                                                                    <div class="col-md-2 fv-row mb-4 mt-2 px-7 align-content-center">
                                                                        <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                                                            <input type="checkbox" name="status" class="form-check-input" id="status" checked>
                                                                            <span class="form-check-label fs-6 fw-semibold mx-4" for="status">
                                                                                Aktif
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <div class="separator separator-dashed mt-7 mb-11"></div>

                                                            <h5 class="mb-7 d-flex align-items-center">
                                                                <span class="bullet bg-danger w-15px me-3"></span> Meta Etiketleri ( {{ $language?->getCodeUppercase() }} )
                                                            </h5>

                                                            <div class="row">
                                                                <div class="col-md-6 fv-row mb-4 cst-meta-keywords">
                                                                    <label class=" fs-6 fw-semibold mb-2">Meta Anaharat Kelimeler</label>
                                                                    <textarea name="{{ $language?->code }}[meta_keywords]" id="meta_keywords_{{ $language?->id }}" class="form-control form-control-solid" rows="3" placeholder="Meta Anaharat Kelimeler" minlength="20" maxlength="150"></textarea>
                                                                </div>
                                                                <div class="col-md-6 fv-row mb-4 cst-meta-descriptions">
                                                                    <label class=" fs-6 fw-semibold mb-2">Meta Açıklama</label>
                                                                    <textarea name="{{ $language?->code }}[meta_descriptions]" id="meta_descriptions_{{ $language?->id }}" class="form-control form-control-solid" rows="3" placeholder="Meta Açıklama" minlength="50" maxlength="160"></textarea>
                                                                </div>
                                                            </div>
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
                            <button type="button" id="add-service-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                                Kapat
                            </button>

                            <button type="submit" id="add-service-btn" class="btn btn-light-primary">
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
        {{-- Hizmet Ekleme Modal Bitiş --}}
    @endcan


    @can(PermissionEnum::SERVICE_UPDATE)
        {{-- Hizmet Güncelleme Modal Başlangıç --}}
        <div class="modal fade" id="update-service-modal" tabindex="-1" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Hizmet Güncelle
                        </h2>
                        <div id="update-service-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="update-service-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="update-service-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>

                        <button type="submit" form="update-form" id="update-service-btn" class="btn btn-light-primary">
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
        {{-- Hizmet Güncelleme Modal Bitiş --}}
    @endcan


    @can(PermissionEnum::SERVICE_DELETE)
        {{-- Silinen Hizmetler Listesi Başlangıç --}}
        <div class="modal fade" id="trashed-service-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Silinmiş Hizmetler</h2>
                        <div id="trashed-service-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body py-10 px-lg-10">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <i class="fa-solid fa-magnifying-glass fs-5"></i>
                            </span>
                            <input type="text" data-kt-service-trashed-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Başlığa Göre Ara..." />
                        </div>

                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="trashed-service-lists">
                            <thead>
                                <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center min-w150">İşlemler</th>
                                    <th class="text-center min-w150">Kapak Görsel</th>
                                    <th class="text-center min-w150">Başlık</th>
                                    <th class="text-center min-w250">Silinme Nedeni</th>
                                    <th class="text-center min-w100">Sıralama</th>
                                </tr>
                            </thead>

                            <tbody class="fw-semibold text-gray-600 text-center">
                                @foreach ($deletedServices as $service)
                                    <tr data-id="{{ $service->id }}">
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
                                                <img src="{{ $service->getOtherImage() }}" />
                                            </div>
                                        </td>

                                        <td>{{ $service?->content?->title }}</td>

                                        <td>{{ $service?->deleted_description }}</td>

                                        <td>{{ $service?->sorting }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-service-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Hizmetler Listesi Bitiş --}}

        {{-- Hizmet Silme Modal Başlangıç --}}
        <div class="modal fade" id="delete-service-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-danger me-3 service-delete-btn" data-id="">Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Hizmet Silme Modal Bitiş --}}

        {{-- Silinen Hizmet Detayını Görüntüleme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-view-service-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Silinen Hizmet
                        </h2>
                        <div id="trashed-view-service-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="trashed-view-service-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-view-service-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Hizmet Detayını Görüntüleme Modal Bitiş --}}

        {{-- Silinen Hizmeti Geri Getirme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-restore-service-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-primary me-3 trashed-restore-service-delete-btn" data-id="">Geri Yükle</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Hizmeti Geri Getirme Modal Bitiş --}}


        {{-- Hizmet Görseli Silme Modal Başlangıç --}}
        <div class="modal fade" id="delete-image-service-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-body text-center">
                        <button type="button" class="btn btn-icon btn-light-danger mb-5 mt-5 fs-2">
                            <i class="fa-solid fa-trash-can fs-3"></i>
                        </button>

                        <h3>Kalıcı Olarak Silmek İstediğine Emin misin ?</h3>
                        <small class="text-danger fw-bold">( Bu İşlem Geri Alınamaz !!! )</small>
                    </div>

                    <div class="modal-footer justify-content-center border-0 pt-0">
                        <button type="button" class="btn btn-light-primary me-3" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="button" class="btn btn-light-danger me-3 service-delete-image-btn" data-id="" data-service="">Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Hizmet Görseli Silme Modal Bitiş --}}
    @endcan


    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        {{-- Kalıcı Olarak Silme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-remove-service-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-danger me-3 trashed-remove-service-delete-btn" data-id="">Kalıcı Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Kalıcı Olarak Silme Modal Bitiş --}}
    @endhasrole
@endsection


@section('js')
    <script src="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/service/datatable/datatable.js') }}"></script>
    <script src="{{ asset('backend/assets/js/custom/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/service/view/read-view.js') }}"></script>

    @can(PermissionEnum::SERVICE_CREATE)
        <script src="{{ asset('backend/assets/js/modules/service/create/add.js') }}"></script>

        <script>
            @foreach ($languages as $al)
                CKEDITOR.replace('editor_{{ $al->id }}', {
                    extraPlugins: 'widget,widgetselection',

                    filebrowserImageBrowseUrl: "{{ url('laravel-filemanager?type=Images') }}",
                    filebrowserImageUploadUrl: "{{ url('laravel-filemanager/upload?type=Images&_token=') }}{{ csrf_token() }}",
                    filebrowserBrowseUrl: "{{ url('laravel-filemanager?type=Files') }}",
                    filebrowserUploadUrl: "{{ url('laravel-filemanager/upload?type=Files&_token=') }}{{ csrf_token() }}",

                    height: 500,
                    removePlugins: 'uploadimage',
                });

                $('#short_description_{{ $al->id }}').maxlength({
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

                $('#meta_keywords_{{ $al->id }}').maxlength({
                    alwaysShow: true,
                    warningClass: "badge badge-danger",
                    limitReachedClass: "badge badge-success"
                }).on('focus input', function() {
                    let length = $(this).val().length;

                    if (length >= 20) {
                        $(".bootstrap-maxlength").removeClass("badge-danger").addClass("badge-success"); // Yeşil yap
                    } else {
                        $(".bootstrap-maxlength").removeClass("badge-success").addClass("badge-danger"); // Kırmızı yap
                    }
                });

                $('#meta_descriptions_{{ $al->id }}').maxlength({
                    alwaysShow: true,
                    warningClass: "badge badge-danger",
                    limitReachedClass: "badge badge-success"
                }).on('focus input', function() {
                    let length = $(this).val().length;

                    if (length >= 50) {
                        $(".bootstrap-maxlength").removeClass("badge-danger").addClass("badge-success"); // Yeşil yap
                    } else {
                        $(".bootstrap-maxlength").removeClass("badge-success").addClass("badge-danger"); // Kırmızı yap
                    }
                });
            @endforeach

            $("#multiple-service-image").on("change", function() {
                let allowedTypes = {!! json_encode(ImageTypeEnum::getMimeType()) !!};

                let maxFiles = 6;
                let maxSize = {{ ImageSizeEnum::SIZE_3->value }} * 1024 * 1024;
                let files = this.files;

                if (files.length > maxFiles) {
                    showSwal('warning', 'En fazla ' + maxFiles + ' dosya seçebilirsiniz!', 'center');
                    $(this).val(""); // Seçimi temizle
                    return;
                }

                for (let i = 0; i < files.length; i++) {
                    if (!allowedTypes.includes(files[i].type)) {
                        showSwal('warning', "Geçersiz dosya türü: " + files[i].name, 'center');
                        $(this).val(""); // Seçimi temizle
                        return;
                    }

                    if (files[i].size > maxSize) {
                        showSwal('warning', files[i].name + " çok büyük! Maksimum " + maxSize + " MB olabilir.", 'center');
                        $(this).val(""); // Seçimi temizle
                        return;
                    }
                }
            });
        </script>
    @endcan

    @can(PermissionEnum::SERVICE_UPDATE)
        <script src="{{ asset('backend/assets/js/modules/service/update/change-status.js') }}"></script>

        <script src="{{ asset('backend/assets/js/modules/service/update/get-edit.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/service/update/update.js') }}"></script>
    @endcan

    @can(PermissionEnum::SERVICE_DELETE)
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
        <script src="{{ asset('backend/assets/js/modules/service/delete/get-delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/service/delete/delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/service/delete/get-trashed.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/service/delete/get-trashed-restore.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/service/delete/trashed-restore.js') }}"></script>

        <script src="{{ asset('backend/assets/js/modules/service/delete/get-image-delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/service/delete/image-delete.js') }}"></script>
    @endcan

    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        <script src="{{ asset('backend/assets/js/modules/service/remove/get-trashed-remove.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/service/remove/trashed-remove.js') }}"></script>
    @endhasrole
@endsection
