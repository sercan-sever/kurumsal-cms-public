@extends('backend.layouts.app')

@use(App\Enums\Roles\RoleEnum)
@use(App\Enums\Permissions\PermissionEnum)

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
        'title' => 'Sayfalar',
        'breadcrumbs' => [['name' => 'Sayfalar']],
    ])
@endsection


@section('content')
    <div class="card">
        <div class="cst-table-header card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <i class="fa-solid fa-magnifying-glass fs-5"></i>
                    </span>
                    <input type="text" data-kt-page-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Başlığa Göre Ara..." />
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-page-table-toolbar="base">
                    @can(PermissionEnum::PAGE_DELETE)
                        <button type="button" id="trashed-page" class="btn btn-sm btn-light-danger me-3 mt-2" data-bs-toggle="modal" data-bs-target="#trashed-page-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-trash-can fs-5"></i>
                            </span>
                            Silinenler
                        </button>
                    @endcan

                    @can(PermissionEnum::PAGE_CREATE)
                        <button type="button" id="add-page" class="btn btn-sm btn-bg-light btn-light-primary me-3 mt-2">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-pager fs-5"></i>
                            </span>
                            Sayfa Ekle
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="page-lists">
                <thead>
                    <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="text-center min-w150">İşlemler</th>
                        <th class="text-center min-w200">Başlık</th>
                        <th class="text-center min-w100">Üst Sayfa</th>
                        <th class="text-center min-w125">Menü Gösterimi</th>
                        <th class="text-center min-w100">Durum</th>
                        <th class="text-center min-w150">Sayfa Yolu Durum</th>
                        <th class="text-center min-w100">Sıralama</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600 text-center">
                    @foreach ($pages as $page)
                        <tr data-id="{{ $page->id }}">
                            <td>
                                <button type="button" class="read-view-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                @if (empty($page?->top_page))
                                    <button type="button" class="page-builder-btn btn btn-icon btn-bg-light btn-light-success btn-sm me-1">
                                        <i class="fa-solid fa-screwdriver-wrench"></i>
                                    </button>
                                @endif
                                <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>

                            <td>{{ $page?->content?->title }}</td>

                            <td>{!! $page?->top_page_name !!}</td>

                            <td>{{ $page?->menu?->label() }}</td>

                            <td>{!! $page?->getStatusInput() !!}</td>

                            <td>{!! $page?->getBreadcrumbInput() !!}</td>

                            <td>{{ $page?->sorting }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- Sayfa Detayını Görüntüleme Modal Başlangıç --}}
    <div class="modal fade" id="view-page-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">
                        Sayfa İnceleme
                    </h2>
                    <div id="view-page-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-regular fa-circle-xmark fs-2"></i>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <div class="d-flex flex-column" id="view-page-modal-scroll" data-kt-scroll="false">

                    </div>
                </div>

                <div class="modal-footer flex-center">
                    <button type="button" id="view-page-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Sayfa Detayını Görüntüleme Modal Bitiş --}}


    @can(PermissionEnum::PAGE_CREATE)
        {{-- Sayfa Ekleme Modal Başlangıç --}}
        <div class="modal fade" id="add-page-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form id="create-form" class="form" data-action="{{ route('admin.pages.create') }}">
                        @csrf

                        <div class="modal-header">
                            <h2 class="fw-bold">
                                Sayfa Ekle
                            </h2>
                            <div id="add-page-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="fa-regular fa-circle-xmark fs-2"></i>
                            </div>
                        </div>

                        <div class="modal-body p-0">
                            <div class="d-flex flex-column" id="add-page-modal-scroll" data-kt-scroll="false">


                            </div>
                        </div>

                        <div class="modal-footer flex-center">
                            <button type="button" id="add-page-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                                Kapat
                            </button>

                            <button type="submit" id="add-page-btn" class="btn btn-light-primary">
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
        {{-- Sayfa Ekleme Modal Bitiş --}}
    @endcan


    @can(PermissionEnum::PAGE_UPDATE)
        {{-- Sayfa Güncelleme Modal Başlangıç --}}
        <div class="modal fade" id="update-page-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Sayfa Güncelle
                        </h2>
                        <div id="update-page-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="update-page-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="update-page-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>

                        <button type="submit" form="update-form" id="update-page-btn" class="btn btn-light-primary">
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
        {{-- Sayfa Güncelleme Modal Bitiş --}}
    @endcan


    @can(PermissionEnum::PAGE_DELETE)
        {{-- Silinen Sayfa Listesi Başlangıç --}}
        <div class="modal fade" id="trashed-page-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Silinmiş Sayfalar</h2>
                        <div id="trashed-page-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body py-10 px-lg-10">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <i class="fa-solid fa-magnifying-glass fs-5"></i>
                            </span>
                            <input type="text" data-kt-page-trashed-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Başlığa Göre Ara..." />
                        </div>

                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="trashed-page-lists">
                            <thead>
                                <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center min-w150">İşlemler</th>
                                    <th class="text-center min-w150">Başlık</th>
                                    <th class="text-center min-w150">Üst Sayfa</th>
                                    <th class="text-center min-w125">Menü Gösterimi</th>
                                    <th class="text-center min-w150">Silinme Nedeni</th>
                                    <th class="text-center min-w150">Sıralama</th>
                                </tr>
                            </thead>

                            <tbody class="fw-semibold text-gray-600 text-center">
                                @foreach ($deletePages as $page)
                                    <tr data-id="{{ $page->id }}">
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

                                        <td>{{ $page?->content?->title }}</td>

                                        <td>{!! $page?->top_page_name !!}</td>

                                        <td>{{ $page?->menu?->label() }}</td>

                                        <td>{{ $page?->deleted_description }}</td>

                                        <td>{{ $page?->sorting }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-page-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Sayfa Listesi Bitiş --}}

        {{-- Sayfa Silme Modal Başlangıç --}}
        <div class="modal fade" id="delete-page-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-danger me-3 page-delete-btn" data-id="">Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Sayfa Silme Modal Bitiş --}}

        {{-- Silinen Sayfanın Detayını Görüntüleme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-view-page-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Silinen Sayfa
                        </h2>
                        <div id="trashed-view-page-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="trashed-view-page-modal-scroll" data-kt-scroll="false" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#trashed-view-page-modal-header" data-kt-scroll-wrappers="#trashed-view-page-modal-scroll" data-kt-scroll-offset="300px">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-view-page-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Sayfanın Detayını Görüntüleme Modal Bitiş --}}

        {{-- Silinen Sayfayı Geri Getirme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-restore-page-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-primary me-3 trashed-restore-page-delete-btn" data-id="">Geri Yükle</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Sayfayı Geri Getirme Modal Bitiş --}}
    @endcan


    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        {{-- Kalıcı Olarak Silme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-remove-page-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-danger me-3 trashed-remove-page-delete-btn" data-id="">Kalıcı Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Kalıcı Olarak Silme Modal Bitiş --}}
    @endhasrole
@endsection

@section('js')
    <script src="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/pages/datatable/datatable.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/pages/view/read-view.js') }}"></script>

    <script>
        $(document).on('shown.bs.modal', function() {
            $('[data-kt-image-input]').each(function() {
                new KTImageInput(this);
            });
        });
    </script>

    @can(PermissionEnum::PAGE_CREATE)
        <script src="{{ asset('backend/assets/js/modules/pages/create/get-create.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/pages/create/add.js') }}"></script>
    @endcan

    @can(PermissionEnum::PAGE_UPDATE)
        <script src="{{ asset('backend/assets/js/modules/pages/update/change-status.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/pages/update/get-edit.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/pages/update/update.js') }}"></script>
    @endcan

    @can(PermissionEnum::PAGE_SECTION_VIEW)
        <script src="{{ asset('backend/assets/js/modules/pages-detail/view/detail-check.js') }}"></script>
    @endcan


    @can(PermissionEnum::PAGE_DELETE)
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

        <script src="{{ asset('backend/assets/js/modules/pages/delete/get-delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/pages/delete/delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/pages/delete/get-trashed.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/pages/delete/get-trashed-restore.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/pages/delete/trashed-restore.js') }}"></script>
    @endcan

    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        <script src="{{ asset('backend/assets/js/modules/pages/remove/get-trashed-remove.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/pages/remove/trashed-remove.js') }}"></script>
    @endhasrole
@endsection
