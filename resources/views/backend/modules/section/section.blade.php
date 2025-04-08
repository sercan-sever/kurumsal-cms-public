@extends('backend.layouts.app')

@use(App\Enums\Roles\RoleEnum)
@use(App\Enums\Permissions\PermissionEnum)

@use(App\Enums\Pages\Section\PageSectionEnum)

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

        .select2-container--bootstrap5 .select2-selection--multiple.form-select-lg {
            justify-content: start;
        }
    </style>
@endsection

@section('toolbar')
    @include('backend.includes.toolbar', [
        'title' => 'Bölümler',
        'breadcrumbs' => [['name' => 'Sayfalar', 'url' => route('admin.pages')], ['name' => 'Bölümler']],
    ])
@endsection

@section('content')
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <i class="fa-solid fa-magnifying-glass fs-5"></i>
                    </span>
                    <input type="text" data-kt-section-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Başlığa Göre Ara..." />
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-section-table-toolbar="base">
                    @can(PermissionEnum::PAGE_SECTION_DELETE)
                        <button type="button" id="trashed-section" class="btn btn-sm btn-light-danger me-3" data-bs-toggle="modal" data-bs-target="#trashed-section-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-trash-can fs-5"></i>
                            </span>
                            Silinenler
                        </button>
                    @endcan

                    @can(PermissionEnum::PAGE_SECTION_CREATE)
                        <button type="button" id="add-section" class="btn btn-sm btn-bg-light btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#add-section-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-puzzle-piece fs-5"></i>
                            </span>
                            Bölüm Ekle
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="section-lists">
                <thead>
                    <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="text-center min-w150">İşlemler</th>
                        <th class="text-center">Görsel</th>
                        <th class="text-center min-w200">Başlık</th>
                        <th class="text-center min-w200">Kategori</th>
                        <th class="text-center min-w100">Durum</th>
                        <th class="text-center min-w100">Sıralama</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600 text-center">
                    @foreach ($sections as $section)
                        <tr data-id="{{ $section->id }}">
                            <td>
                                <button type="button" class="read-view-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                @if (!$section->isDefaultStatus())
                                    <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                @endif
                            </td>

                            <td>
                                {!! $section?->getImageHtml() !!}
                            </td>

                            <td>{{ $section?->title }}</td>

                            <td>{{ $section?->getSectionCategoryName() }}</td>

                            <td>{!! $section?->getStatusInput() !!}</td>

                            <td>{{ $section?->sorting }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- Bölüm Detay Görüntüleme Modal Başlangıç --}}
    <div class="modal fade" id="view-section-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">
                        Bölüm İnceleme
                    </h2>
                    <div id="view-section-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-regular fa-circle-xmark fs-2"></i>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <div class="d-flex flex-column" id="view-section-modal-scroll" data-kt-scroll="false">

                    </div>
                </div>

                <div class="modal-footer flex-center">
                    <button type="button" id="view-section-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Bölüm Detay Görüntüleme Modal Bitiş --}}


    @can(PermissionEnum::PAGE_SECTION_CREATE)
        {{-- Bölüm Seçme Modal Başlangıç --}}
        <div class="modal fade" id="add-section-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Bölüm Ekle
                        </h2>
                        <div id="add-section-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="add-section-modal-scroll" data-kt-scroll="false">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 hover-scale mb-7">
                                            <div class="card shadow-sm">
                                                <div class="card-body rounded border-primary border border-dashed text-center p-0">
                                                    <button type="button" class="btn add-section-btn" data-section="{{ PageSectionEnum::DYNAMIC->value }}">
                                                        <div class="symbol symbol-70px symbol-fixed position-relative mt-4">
                                                            <img class="mw-70 mh-70px card-rounded-bottom" src="{{ asset('images/sections/defaults/content.png') }}" />
                                                        </div>

                                                        <div class="separator separator-dashed mt-7 mb-4"></div>

                                                        <p class="text-gray-900 fw-bold fs-5">İçerik</p>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 hover-scale mb-7">
                                            <div class="card shadow-sm">
                                                <div class="card-body rounded border-primary border border-dashed text-center p-0">
                                                    <button type="button" class="btn add-section-btn" data-section="{{ PageSectionEnum::DYNAMIC_IMAGE->value }}">
                                                        <div class="symbol symbol-70px symbol-fixed position-relative mt-4">
                                                            <img class="mw-70 mh-70px card-rounded-bottom" src="{{ asset('images/sections/defaults/content.png') }}" />
                                                        </div>

                                                        <div class="separator separator-dashed mt-7 mb-4"></div>

                                                        <p class="text-gray-900 fw-bold fs-5">İçerik ( Görsel )</p>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 hover-scale mb-7">
                                            <div class="card shadow-sm">
                                                <div class="card-body rounded border-primary border border-dashed text-center p-0">
                                                    <button type="button" class="btn add-section-btn" data-section="{{ PageSectionEnum::DYNAMIC_LEFT->value }}">
                                                        <div class="symbol symbol-70px symbol-fixed position-relative mt-4">
                                                            <img class="mw-70 mh-70px card-rounded-bottom" src="{{ asset('images/sections/defaults/content-image.png') }}" />
                                                        </div>

                                                        <div class="separator separator-dashed mt-7 mb-4"></div>

                                                        <p class="text-gray-900 fw-bold fs-5">İçerik + Görsel Sol</p>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 hover-scale mb-7">
                                            <div class="card shadow-sm">
                                                <div class="card-body rounded border-primary border border-dashed text-center p-0">
                                                    <button type="button" class="btn add-section-btn" data-section="{{ PageSectionEnum::DYNAMIC_RIGHT->value }}">
                                                        <div class="symbol symbol-70px symbol-fixed position-relative mt-4">
                                                            <img class="mw-70 mh-70px card-rounded-bottom" src="{{ asset('images/sections/defaults/content-image.png') }}" />
                                                        </div>

                                                        <div class="separator separator-dashed mt-7 mb-4"></div>

                                                        <p class="text-gray-900 fw-bold fs-5">İçerik + Görsel Sağ</p>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="add-section-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Bölüm Seçme Modal Bitiş --}}

        {{-- Dinamik Bölüm Ekleme Modal Başlangıç --}}
        <div class="modal fade" id="add-section-content-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Bölüm Ekle
                        </h2>
                        <div id="add-section-content-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="add-section-content-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="add-section-content-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>

                        <button type="submit" form="create-form" id="add-section-content-btn" class="btn btn-light-primary">
                            <span class="indicator-label">Kaydet</span>
                            <span class="indicator-progress">
                                Lütfen Bekleyiniz...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Dinamik Bölüm Ekleme Modal Bitiş --}}
    @endcan


    @can(PermissionEnum::PAGE_SECTION_UPDATE)
        {{-- Bölüm Güncelleme Modal Başlangıç --}}
        <div class="modal fade" id="update-section-modal" tabindex="-1" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Bölüm Güncelle
                        </h2>
                        <div id="update-section-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="update-section-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="update-section-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>

                        <button type="submit" form="update-form" id="update-section-btn" class="btn btn-light-primary">
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
        {{-- Bölüm Güncelleme Modal Bitiş --}}
    @endcan


    @can(PermissionEnum::PAGE_SECTION_DELETE)
        {{-- Silinen Bölüm Detay Listesi Başlangıç --}}
        <div class="modal fade" id="trashed-section-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Silinmiş Bölüm Detayı</h2>
                        <div id="trashed-section-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body py-10 px-lg-10">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <i class="fa-solid fa-magnifying-glass fs-5"></i>
                            </span>
                            <input type="text" data-kt-section-trashed-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Başlığa Göre Ara..." />
                        </div>

                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="trashed-section-lists">
                            <thead>
                                <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center min-w150">İşlemler</th>
                                    <th class="text-center">Görsel</th>
                                    <th class="text-center min-w200">Başlık</th>
                                    <th class="text-center min-w150">Kategori</th>
                                    <th class="text-center min-w250">Silinme Nedeni</th>
                                    <th class="text-center min-w100">Sıralama</th>
                                </tr>
                            </thead>

                            <tbody class="fw-semibold text-gray-600 text-center">
                                @foreach ($deleteSections as $section)
                                    <tr data-id="{{ $section->id }}">
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
                                            {!! $section?->getImageHtml() !!}
                                        </td>

                                        <td>{{ $section?->title }}</td>

                                        <td>{{ $section?->getSectionCategoryName() }}</td>

                                        <td>{{ $section?->deleted_description }}</td>

                                        <td>{{ $section?->sorting }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-section-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Bölüm Detay Listesi Bitiş --}}

        {{-- Bölüm Silme Modal Başlangıç --}}
        <div class="modal fade" id="delete-section-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-danger me-3 section-delete-btn" data-id="">Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Bölüm Silme Modal Bitiş --}}

        {{-- Silinen Bölüm Detayını Görüntüleme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-view-section-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Bölüm Detay
                        </h2>
                        <div id="trashed-view-section-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="trashed-view-section-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-view-section-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Bölüm Detayını Görüntüleme Modal Bitiş --}}

        {{-- Silinen Bölüm Geri Getirme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-restore-section-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-primary me-3 trashed-restore-section-delete-btn" data-id="">Geri Yükle</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Bölüm Geri Getirme Modal Bitiş --}}
    @endcan

    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        {{-- Kalıcı Olarak Silme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-remove-section-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-danger me-3 trashed-remove-section-delete-btn" data-id="">Kalıcı Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Kalıcı Olarak Silme Modal Bitiş --}}
    @endhasrole
@endsection

@section('js')
    <script src="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/section/datatable/datatable.js') }}"></script>
    <script src="{{ asset('backend/assets/js/custom/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/section/view/read-view.js') }}"></script>

    <script>
        $(document).on('shown.bs.modal', function() {
            $('[data-kt-image-input]').each(function() {
                new KTImageInput(this);
            });
        });
    </script>

    @can(PermissionEnum::PAGE_SECTION_CREATE)
        <script src="{{ asset('backend/assets/js/modules/section/create/get-create.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/section/create/add.js') }}"></script>
    @endcan

    @can(PermissionEnum::PAGE_SECTION_UPDATE)
        <script src="{{ asset('backend/assets/js/modules/section/update/change-status.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/section/update/get-edit.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/section/update/update.js') }}"></script>
    @endcan

    @can(PermissionEnum::PAGE_SECTION_DELETE)
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

        <script src="{{ asset('backend/assets/js/modules/section/delete/get-delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/section/delete/delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/section/delete/get-trashed.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/section/delete/get-trashed-restore.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/section/delete/trashed-restore.js') }}"></script>
    @endcan

    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        <script src="{{ asset('backend/assets/js/modules/section/remove/get-trashed-remove.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/section/remove/trashed-remove.js') }}"></script>
    @endhasrole
@endsection
