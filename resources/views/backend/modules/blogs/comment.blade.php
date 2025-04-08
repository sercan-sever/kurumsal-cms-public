@extends('backend.layouts.app')

@use(App\Enums\Roles\RoleEnum)
@use(App\Enums\Permissions\PermissionEnum)
@use(App\Enums\Images\ImageTypeEnum)
@use(App\Enums\Images\ImageSizeEnum)

@section('css')
    <link href="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .nav-line-tabs .nav-item .nav-link.active {
            color: #000000;
        }
    </style>
@endsection

@section('toolbar')
    @include('backend.includes.toolbar', [
        'title' => 'Blog Yorumlar',
        'breadcrumbs' => [['name' => 'Blog'], ['name' => 'Yorumlar']],
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
                    <input type="text" data-kt-blog-comment-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Blog Adına Göre Ara..." />
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-blog-comment-table-toolbar="base">
                    @can(PermissionEnum::BLOG_COMMENT_DELETE)
                        <button type="button" id="trashed-blog-comment" class="btn btn-sm btn-light-danger me-3 mt-2" data-bs-toggle="modal" data-bs-target="#trashed-blog-comment-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-trash-can fs-5"></i>
                            </span>
                            Silinenler
                        </button>
                    @endcan
                    @can(PermissionEnum::BLOG_COMMENT_CONFIRM)
                        <button type="button" id="confirm-blog-comment" class="btn btn-sm btn-light-success me-3 mt-2" data-bs-toggle="modal" data-bs-target="#confirm-blog-comment-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-thumbs-up fs-5"></i>
                            </span>
                            Kabul Edilenler
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card-body pt-0 table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="blog-comment-lists">
                <thead>
                    <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="text-center min-w150">İşlemler</th>
                        <th class="text-center min-w150">Ad Soyad</th>
                        <th class="text-center min-w150">E-Posta</th>
                        <th class="text-center min-w150">Blog</th>
                        <th class="text-center min-w150">Blog Durum</th>
                        <th class="text-center min-w150">Blog Yorum Durum</th>
                        <th class="text-center min-w150">IP Adres</th>
                        <th class="text-center min-w150">Yorum</th>
                        <th class="text-center min-w100">Yorum Tarihi</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600 text-center">
                    @foreach ($unConfirmedComments as $comment)
                        <tr data-id="{{ $comment->id }}">
                            <td>
                                <button type="button" class="confirm-btn btn btn-icon btn-bg-light btn-light-success btn-sm me-1">
                                    <i class="fa-solid fa-thumbs-up"></i>
                                </button>
                                <button type="button" class="reject-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                                    <i class="fa-solid fa-comment-slash"></i>
                                </button>
                            </td>

                            <td>{{ $comment?->name }}</td>

                            <td>{{ $comment?->email }}</td>

                            <td>{!! getStringLimit($comment?->blog?->content?->title, 50) !!}</td>

                            <td>{!! $comment?->blog?->getStatusBadgeHtml() !!}</td>

                            <td>{!! $comment?->blog?->getCommentStatusBadgeHtml() !!}</td>

                            <td>{{ $comment?->ip_address }}</td>

                            <td>{{ getStringLimit($comment?->comment, 100) }}</td>

                            <td>{{ $comment?->getCreatedAt() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @can(PermissionEnum::BLOG_COMMENT_DELETE)
        {{-- Silinen Blog Yorum Listesi Başlangıç --}}
        <div class="modal fade" id="trashed-blog-comment-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Silinmiş Yorumlar</h2>
                        <div id="trashed-blog-comment-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body py-10 px-lg-10">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <i class="fa-solid fa-magnifying-glass fs-5"></i>
                            </span>
                            <input type="text" data-kt-blog-comment-trashed-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Blog Adına Göre Ara..." />
                        </div>

                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="trashed-blog-comment-lists">
                            <thead>
                                <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center min-w150">İşlemler</th>
                                    <th class="text-center min-w150">Ad Soyad</th>
                                    <th class="text-center min-w150">E-Posta</th>
                                    <th class="text-center min-w150">Blog</th>
                                    <th class="text-center min-w150">Silinme Nedeni</th>
                                    <th class="text-center min-w250">Yorum</th>
                                    <th class="text-center min-w150">IP Adres</th>
                                    <th class="text-center min-w150">Blog Durum</th>
                                    <th class="text-center min-w150">Blog Yorum Durum</th>
                                    <th class="text-center min-w150">Yorum Tarihi</th>
                                </tr>
                            </thead>

                            <tbody class="fw-semibold text-gray-600 text-center">
                                @foreach ($deleteComments as $comment)
                                    <tr data-id="{{ $comment->id }}">
                                        <td>
                                            <button type="button" class="view-delete-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
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

                                        <td>{{ $comment?->name }}</td>

                                        <td>{{ $comment?->email }}</td>

                                        <td>{{ getStringLimit($comment?->blog?->content?->title, 50) }}</td>

                                        <td>{{ $comment?->deleted_description }}</td>

                                        <td>{{ getStringLimit($comment?->comment, 100) }}</td>

                                        <td>{{ $comment?->ip_address }}</td>

                                        <td>{!! $comment?->blog?->getStatusBadgeHtml() !!}</td>

                                        <td>{!! $comment?->blog?->getCommentStatusBadgeHtml() !!}</td>

                                        <td>{{ $comment?->getCreatedAt() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-blog-comment-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Blog Yorum Listesi Bitiş --}}


        {{-- Blog Yorum Silme Modal Başlangıç --}}
        <div class="modal fade" id="delete-blog-comment-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-danger me-3 blog-comment-delete-btn" data-id="">Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Blog Yorum Silme Modal Bitiş --}}


        {{-- Silinen Blog Kategoriyi Geri Getirme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-restore-blog-comment-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-primary me-3 trashed-restore-blog-comment-delete-btn" data-id="">Geri Yükle</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Blog Kategoriyi Geri Getirme Modal Bitiş --}}
    @endcan


    @can(PermissionEnum::BLOG_COMMENT_CONFIRM)
        {{-- Onaylanan Blog Yorum Listesi Başlangıç --}}
        <div class="modal fade" id="confirm-blog-comment-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Kabul Edilmiş Yorumlar</h2>
                        <div id="confirm-blog-comment-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body py-10 px-lg-10">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <i class="fa-solid fa-magnifying-glass fs-5"></i>
                            </span>
                            <input type="text" data-kt-blog-comment-confirm-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Blog Adına Göre Ara..." />
                        </div>

                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="confirm-blog-comment-lists">
                            <thead>
                                <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center min-w150">İşlemler</th>
                                    <th class="text-center min-w150">Ad Soyad</th>
                                    <th class="text-center min-w150">E-Posta</th>
                                    <th class="text-center min-w150">Blog</th>
                                    <th class="text-center min-w250">Yorum</th>
                                    <th class="text-center min-w150">IP Adres</th>
                                    <th class="text-center min-w150">Blog Durum</th>
                                    <th class="text-center min-w150">Blog Yorum Durum</th>
                                    <th class="text-center min-w150">Yorum Tarihi</th>
                                </tr>
                            </thead>

                            <tbody class="fw-semibold text-gray-600 text-center">
                                @foreach ($confirmedComments as $comment)
                                    <tr data-id="{{ $comment->id }}">
                                        <td>
                                            <button type="button" class="view-confirm-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                                                <i class="fa-solid fa-pencil"></i>
                                            </button>
                                            <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>

                                        <td>{{ $comment?->name }}</td>

                                        <td>{{ $comment?->email }}</td>

                                        <td>{{ getStringLimit($comment?->blog?->content?->title, 50) }}</td>

                                        <td>{{ getStringLimit($comment?->comment, 100) }}</td>

                                        <td>{{ $comment?->ip_address }}</td>

                                        <td>{!! $comment?->blog?->getStatusBadgeHtml() !!}</td>

                                        <td>{!! $comment?->blog?->getCommentStatusBadgeHtml() !!}</td>

                                        <td>{{ $comment?->getCreatedAt() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="confirm-blog-comment-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Onaylanan Blog Yorum Listesi Bitiş --}}


        {{-- Blog Yorum Onaylama Başlangıç --}}
        <div class="modal fade" id="add-confirm-blog-comment-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Blog Yorum Onaylama
                        </h2>
                        <div id="add-confirm-blog-comment-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="add-confirm-blog-comment-modal-scroll" data-kt-scroll="false">


                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="add-confirm-blog-comment-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>

                        <button type="button" id="add-confirm-blog-comment-btn" class="btn btn-light-success" data-comment="">
                            <span class="indicator-label">Onayla</span>
                            <span class="indicator-progress">
                                Lütfen Bekleyiniz...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>

                        <button type="button" id="add-update-confirm-blog-comment-btn" class="btn btn-light-primary" data-comment="">
                            <span class="indicator-label">Düzenle ve Onayla</span>
                            <span class="indicator-progress">
                                Lütfen Bekleyiniz...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Blog Yorum Onaylama Bitiş --}}
    @endcan


    @can(PermissionEnum::BLOG_COMMENT_UPDATE)
        {{-- Blog Yorum Güncelleme Başlangıç --}}
        <div class="modal fade" id="update-blog-comment-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Blog Yorum Güncelleme
                        </h2>
                        <div id="update-blog-comment-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="update-blog-comment-modal-scroll" data-kt-scroll="false">

                            <div class="card">

                                <div class="card-body">

                                    <div class="col-md-12 fv-row mb-7">
                                        <div class="row cst-mobil-checkbox">
                                            <div class="col-md-4 fv-row mb-4">
                                                <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                                    <span class="form-check-label fs-6 fw-semibold mx-4">
                                                        Blog Durum :
                                                    </span>
                                                    <span class="badge badge-success p-3">Aktif</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 fv-row mb-4">
                                                <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                                    <span class="form-check-label fs-6 fw-semibold mx-4">
                                                        Blog Yorum Durum :
                                                    </span>
                                                    <span class="badge badge-danger p-3">Pasif</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 fv-row mb-4">
                                                <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                                    <span class="form-check-label fs-6 fw-semibold mx-4">
                                                        Blog Abonelik Durum :
                                                    </span>
                                                    <span class="badge badge-danger p-3">Pasif</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="separator separator-dashed mb-11"></div>

                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">Blog Ad ( TR )</label>
                                        <input type="text" class="form-control form-control-solid" placeholder="Blog Ad" value="Başlık Türkçe" disabled />
                                    </div>

                                    <div class="col-md-12 fv-row mb-7">
                                        <div class="row cst-mobil-checkbox">
                                            <div class="col-md-6 fv-row mb-4">
                                                <label class="fs-6 fw-semibold mb-2">Yorum Yapan IP</label>
                                                <input type="text" class="form-control form-control-solid" placeholder="Yorum Yapan IP" value="127.0.0.1" disabled />
                                            </div>
                                            <div class="col-md-6 fv-row mb-4">
                                                <label class="fs-6 fw-semibold mb-2">Toplam Yorum</label>
                                                <input type="text" class="form-control form-control-solid" placeholder="Toplam Yorum" value="127" disabled />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="fv-row mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Kullanıcı Yorumu</label>
                                        <textarea name="comment" id="update-comment" class="form-control form-control-solid cst-description" rows="10" placeholder="Yorum *">Phemex is currently enhancing wallet security measures following a report involving one of their hot wallets used for daily operations. Withdrawals are temporarily suspended for user safety, but trading services and core operations continue uninterrupted. Their commitment to transparency and security is underscored by their publication of proof-of-reserves and proof-of-solvency. Users' funds in cold wallets remain secure.</textarea>
                                    </div>

                                    <div class="fv-row mb-7">
                                        <label class="fs-6 fw-semibold mb-2">Yetkili Cevabı</label>
                                        <textarea name="comment" id="update-reply-comment" class="form-control form-control-solid cst-description" rows="10" placeholder="Yetkili Cevabı"></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="update-blog-comment-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>

                        <button type="submit" id="update-blog-comment-btn" form="update-form" class="btn btn-light-primary">
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
        {{-- Blog Yorum Güncelleme Listesi Bitiş --}}
    @endcan

    @can(PermissionEnum::BLOG_COMMENT_REJECT)
        {{-- Silinen Blog Kategoriyi Geri Getirme Modal Başlangıç --}}
        <div class="modal fade" id="reject-blog-comment-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <button type="button" class="btn btn-icon btn-light-danger mb-5 mt-5 fs-2">
                            <i class="fa-solid fa-comment-slash fs-3"></i>
                        </button>

                        <h3>Yorumu Reddetmek İstediğine Emin misin ?</h3>
                        <small class="text-danger fw-bold">( Bu İşlem Geri Alınamaz !!! )</small>
                    </div>

                    <div class="modal-footer justify-content-center border-0">
                        <button type="button" class="btn btn-light-primary me-3" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="button" class="btn btn-light-danger me-3 reject-blog-comment-delete-btn" data-id="">Reddet</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Blog Kategoriyi Geri Getirme Modal Bitiş --}}
    @endcan

    @can([PermissionEnum::BLOG_COMMENT_DELETE, PermissionEnum::BLOG_COMMENT_CONFIRM])
        {{-- Blog Yorum Görüntüleme Başlangıç --}}
        <div class="modal fade" id="view-blog-comment-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Blog Yorum Görüntüleme
                        </h2>
                        <div id="view-blog-comment-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="view-blog-comment-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="view-blog-comment-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Blog Yorum Görüntüleme Listesi Bitiş --}}
    @endcan


    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        {{-- Kalıcı Olarak Silme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-remove-blog-comment-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-danger me-3 trashed-remove-blog-comment-delete-btn" data-id="">Kalıcı Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Kalıcı Olarak Silme Modal Bitiş --}}
    @endhasrole
@endsection

@section('js')
    <script src="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/blogs/comment/datatable/datatable.js') }}"></script>

    @can(PermissionEnum::BLOG_COMMENT_CONFIRM)
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/confirmation/view-confirm.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/confirmation/get-confirm.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/confirmation/accept-confirm.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/confirmation/accept-update-confirm.js') }}"></script>
    @endcan

    @can(PermissionEnum::BLOG_COMMENT_UPDATE)
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/update/get-edit.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/update/update.js') }}"></script>
    @endcan

    @can(PermissionEnum::BLOG_COMMENT_REJECT)
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/reject/get-reject.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/reject/reject.js') }}"></script>
    @endcan

    @can(PermissionEnum::BLOG_COMMENT_DELETE)
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
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/delete/view-delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/delete/get-delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/delete/delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/delete/get-trashed-restore.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/delete/trashed-restore.js') }}"></script>
    @endcan

    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/remove/get-trashed-remove.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/blogs/comment/remove/trashed-remove.js') }}"></script>
    @endhasrole
@endsection
