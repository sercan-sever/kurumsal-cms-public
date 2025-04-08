@extends('backend.layouts.app')

@use(App\Enums\Roles\RoleEnum)
@use(App\Enums\Permissions\PermissionEnum)
@use(App\Enums\Images\ImageTypeEnum)
@use(App\Enums\Images\ImageSizeEnum)

@section('css')
    <link href="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('toolbar')
    @include('backend.includes.toolbar', [
        'title' => 'Blog Aboneler',
        'breadcrumbs' => [['name' => 'Blog'], ['name' => 'Aboneler']],
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
                    <input type="text" data-kt-blog-subscribe-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="E-Mail Göre Ara..." />
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-blog-subscribe-table-toolbar="base">
                    @can(PermissionEnum::BLOG_SUBSCRIBER_DELETE)
                        <button type="button" id="trashed-blog-subscribe" class="btn btn-sm btn-light-danger me-11 mt-2" data-bs-toggle="modal" data-bs-target="#trashed-blog-subscribe-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-trash-can fs-5"></i>
                            </span>
                            Silinenler
                        </button>
                    @endcan

                    @can(PermissionEnum::SETTINGS_VIEW)
                        @can(PermissionEnum::SUBSCRIBE_CHANGE_STATUS)
                            <div class="form-check form-switch form-check-custom form-check-solid cst-status mt-2">
                                <input type="checkbox" name="status" class="form-check-input" id="subscribe-status" @checked($subscribeSetting?->isActiveStatus())>
                                <span class="form-check-label fs-6 fw-semibold mx-4" for="subscribe-status">
                                    Abone Aktiflik
                                </span>
                            </div>
                        @endcan
                    @endcan
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="blog-subscribe-lists">
                <thead>
                    <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="text-center min-w150">İşlemler</th>
                        <th class="text-center min-w250">E-Mail</th>
                        <th class="text-center min-w150">Dil</th>
                        <th class="text-center min-w100">IP Adresi</th>
                        <th class="text-center min-w100">Durum</th>
                        <th class="text-center min-w100">Kayıt Tarihi</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600 text-center">
                    @foreach ($blogSubscribes as $subscribe)
                        <tr data-id="{{ $subscribe->id }}">
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

                            <td>{{ $subscribe->email }}</td>

                            <td>
                                {!! $subscribe?->language?->getImageHtml() !!}
                            </td>

                            <td>{{ $subscribe?->ip_address }}</td>

                            <td>{!! $subscribe?->getStatusInput() !!}</td>

                            <td>{{ $subscribe?->getCreatedAt() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Blog Abone Detay Görüntüleme Modal Başlangıç --}}
    <div class="modal fade" id="view-blog-subscribe-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">
                        Blog Abone İnceleme
                    </h2>
                    <div id="view-blog-subscribe-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-regular fa-circle-xmark fs-2"></i>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <div class="d-flex flex-column" id="view-blog-subscribe-modal-scroll" data-kt-scroll="false">

                    </div>
                </div>

                <div class="modal-footer flex-center">
                    <button type="button" id="view-blog-subscribe-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Blog Abone Detay Görüntüleme Modal Bitiş --}}


    @can(PermissionEnum::BLOG_SUBSCRIBER_UPDATE)
        {{-- Blog Abone Güncelleme Modal Başlangıç --}}
        <div class="modal fade" id="update-blog-subscribe-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Blog Abone Güncelle
                        </h2>
                        <div id="update-blog-subscribe-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="update-blog-subscribe-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="update-blog-subscribe-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>

                        <button type="submit" form="update-form" id="update-blog-subscribe-btn" class="btn btn-light-primary">
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
        {{-- Blog Abone Güncelleme Modal Bitiş --}}
    @endcan

    @can(PermissionEnum::BLOG_SUBSCRIBER_DELETE)
        {{-- Silinen Blog Abone Listesi Başlangıç --}}
        <div class="modal fade" id="trashed-blog-subscribe-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Silinmiş Aboneler</h2>
                        <div id="trashed-blog-subscribe-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body py-10 px-lg-10">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <i class="fa-solid fa-magnifying-glass fs-5"></i>
                            </span>
                            <input type="text" data-kt-blog-subscribe-trashed-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="E-Mail Göre Ara..." />
                        </div>

                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="trashed-blog-subscribe-lists">
                            <thead>
                                <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center min-w150">İşlemler</th>
                                    <th class="text-center min-w250">Silinme Nedeni</th>
                                    <th class="text-center min-w250">E-Mail</th>
                                    <th class="text-center min-w150">Dil</th>
                                    <th class="text-center min-w100">IP Adresi</th>
                                    <th class="text-center min-w100">Kayıt Tarihi</th>
                                </tr>
                            </thead>

                            <tbody class="fw-semibold text-gray-600 text-center">
                                @foreach ($deleteBlogSubscribes as $subscribe)
                                    <tr data-id="{{ $subscribe->id }}">
                                        <td>
                                            <button type="button" class="read-trashed-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="trashed-restore-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                                                <i class="fa-solid fa-recycle"></i>
                                            </button>
                                            <button type="button" class="trashed-remove-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                                                <i class="fas fa-dumpster-fire"></i>
                                            </button>
                                        </td>

                                        <td>{{ $subscribe?->deleted_description }}</td>

                                        <td>{{ $subscribe->email }}</td>

                                        <td>
                                            {!! $subscribe?->language?->getImageHtml() !!}
                                        </td>

                                        <td>{{ $subscribe?->ip_address }}</td>

                                        <td>{{ $subscribe?->getCreatedAt() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-blog-subscribe-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Blog Abone Listesi Bitiş --}}


        {{-- Blog Abone Silme Modal Başlangıç --}}
        <div class="modal fade" id="delete-blog-subscribe-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-danger me-3 blog-subscribe-delete-btn" data-id="">Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Blog Abone Silme Modal Bitiş --}}


        {{-- Silinen Blog Abonenin Detayını Görüntüleme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-view-blog-subscribe-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Silinen Blog Abone
                        </h2>
                        <div id="trashed-view-blog-subscribe-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="trashed-view-blog-subscribe-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-view-blog-subscribe-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Blog Abonenin Detayını Görüntüleme Modal Bitiş --}}


        {{-- Silinen Blog Aboneyi Geri Getirme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-restore-blog-subscribe-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-primary me-3 trashed-restore-blog-subscribe-delete-btn" data-id="">Geri Yükle</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Blog Aboneyi Geri Getirme Modal Bitiş --}}
    @endcan


    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        {{-- Kalıcı Olarak Silme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-remove-blog-subscribe-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-danger me-3 trashed-remove-blog-subscribe-delete-btn" data-id="">Kalıcı Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Kalıcı Olarak Silme Modal Bitiş --}}
    @endhasrole
@endsection

@section('js')
    <script src="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/blogs/subscribe/datatable/datatable.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/blogs/subscribe/view/read-view.js') }}"></script>

    @can(PermissionEnum::SETTINGS_VIEW)
        @can(PermissionEnum::SUBSCRIBE_CHANGE_STATUS)
            <script src="{{ asset('backend/assets/js/modules/settings/subscribe/update.js') }}"></script>
        @endcan
    @endcan

    @can(PermissionEnum::BLOG_SUBSCRIBER_UPDATE)
        <script src="{{ asset('backend/assets/js/modules/blogs/subscribe/update/change-status.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/subscribe/update/get-edit.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/subscribe/update/update.js') }}"></script>
    @endcan

    @can(PermissionEnum::BLOG_SUBSCRIBER_DELETE)
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
        <script src="{{ asset('backend/assets/js/modules/blogs/subscribe/delete/get-delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/subscribe/delete/delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/subscribe/delete/get-trashed.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/subscribe/delete/get-trashed-restore.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/subscribe/delete/trashed-restore.js') }}"></script>
    @endcan

    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        <script src="{{ asset('backend/assets/js/modules/blogs/subscribe/remove/get-trashed-remove.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/subscribe/remove/trashed-remove.js') }}"></script>
    @endhasrole
@endsection
