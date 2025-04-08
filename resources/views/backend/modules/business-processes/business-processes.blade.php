@extends('backend.layouts.app')

@use(App\Enums\Roles\RoleEnum)
@use(App\Enums\Permissions\PermissionEnum)

@section('css')
    <link href="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('toolbar')
    @include('backend.includes.toolbar', ['title' => 'Hizmet Süreçlerimiz', 'breadcrumbs' => [['name' => 'Hizmet Süreçlerimiz']]])
@endsection

@section('content')
    <div class="card">
        <div class="cst-table-header card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <i class="fa-solid fa-magnifying-glass fs-5"></i>
                    </span>
                    <input type="text" data-kt-business-processes-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Başlığa Göre Ara..." />
                </div>
            </div>

            <div class="card-toolbar">
                <div class="d-flex justify-content-end" data-kt-business-processes-table-toolbar="base">
                    @can(PermissionEnum::BUSINESS_PROCESSES_DELETE)
                        <button type="button" id="trashed-business-processes" class="btn btn-sm btn-light-danger me-3 mt-2" data-bs-toggle="modal" data-bs-target="#trashed-business-processes-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-trash-can fs-5"></i>
                            </span>
                            Silinenler
                        </button>
                    @endcan

                    @can(PermissionEnum::BUSINESS_PROCESSES_CREATE)
                        <button type="button" id="add-business-processes" class="btn btn-sm btn-bg-light btn-light-primary me-3 mt-2" data-bs-toggle="modal" data-bs-target="#add-business-processes-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-business-time fs-5"></i>
                            </span>
                            Hizmet Süreç Ekle
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="business-processes-lists">
                <thead>
                    <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="text-center min-w150">İşlemler</th>
                        <th class="text-center min-w150">Üst Başlık</th>
                        <th class="text-center min-w150">Başlık</th>
                        <th class="text-center min-w250">Açıklama</th>
                        <th class="text-center min-w100">Durum</th>
                        <th class="text-center min-w100">Sıralama</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600 text-center">
                    @foreach ($businessProcesses as $processes)
                        <tr data-id="{{ $processes->id }}">
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

                            <td>{{ $processes?->content?->header }}</td>

                            <td>{{ $processes?->content?->title }}</td>

                            <td>{{ getStringLimit($processes?->content?->description ?? '', 25) }}</td>

                            <td>{!! $processes->getStatusInput() !!}</td>

                            <td>{{ $processes?->sorting }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Hizmet Süreç Detayını Görüntüleme Modal Başlangıç --}}
    <div class="modal fade" id="view-business-processes-modal" tabindex="-1" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">
                        Hizmet Süreç İnceleme
                    </h2>
                    <div id="view-business-processes-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-regular fa-circle-xmark fs-2"></i>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <div class="d-flex flex-column" id="view-business-processes-modal-scroll" data-kt-scroll="false">

                    </div>
                </div>

                <div class="modal-footer flex-center">
                    <button type="button" id="view-business-processes-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Hizmet Süreç Detayını Görüntüleme Modal Bitiş --}}

    @can(PermissionEnum::BUSINESS_PROCESSES_CREATE)
        {{-- Hizmet Süreç Ekleme Modal Başlangıç --}}
        <div class="modal fade" id="add-business-processes-modal" aria-hidden="true" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <form id="create-form" class="form" data-action="{{ route('admin.business.processes.create') }}">
                        @csrf

                        <div class="modal-header">
                            <h2 class="fw-bold">
                                Hizmet Süreç Ekle
                            </h2>
                            <div id="add-business-processes-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="fa-regular fa-circle-xmark fs-2"></i>
                            </div>
                        </div>

                        <div class="modal-body p-0">
                            <div class="d-flex flex-column" id="add-business-processes-modal-scroll" data-kt-scroll="false">

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

                                                            <div class="row mb-7">
                                                                <div class="fv-row col-md-6">
                                                                    <label class="required fs-6 fw-semibold mb-2">Üst Başlık ( {{ $language?->getCodeUppercase() }} )</label>
                                                                    <input type="text" name="{{ $language?->code }}[header]" class="form-control form-control-solid" placeholder="Üst Başlık *" required />
                                                                </div>
                                                                <div class="fv-row col-md-6">
                                                                    <label class="required fs-6 fw-semibold mb-2">Başlık ( {{ $language?->getCodeUppercase() }} )</label>
                                                                    <input type="text" name="{{ $language?->code }}[title]" class="form-control form-control-solid" placeholder="Başlık *" required />
                                                                </div>
                                                            </div>

                                                            <div class="fv-row mb-7">
                                                                <label class="required fs-6 fw-semibold mb-2">Açıklama ( {{ $language?->getCodeUppercase() }} )</label>
                                                                <textarea name="{{ $language?->code }}[description]" id="description_{{ $language?->id }}" class="form-control form-control-solid cst-description" rows="6" placeholder="Açıklama *" minlength="5" maxlength="2000" required></textarea>
                                                            </div>

                                                            @if ($loop->first)
                                                                <div class="row cst-mobil-checkbox">
                                                                    <div class="col-md-12">
                                                                        <label class="required fs-6 fw-semibold mb-2">Sıralama</label>
                                                                    </div>
                                                                    <div class="col-md-4 fv-row mb-4">
                                                                        <input type="number" name="sorting" class="form-control form-control-solid" id="sorting" placeholder="Sıralama" min="1" required />
                                                                    </div>

                                                                    <div class="col-md-4 fv-row mb-4 mt-2 px-7">
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
                            <button type="button" id="add-business-processes-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                                Kapat
                            </button>

                            <button type="submit" id="add-business-processes-btn" class="btn btn-light-primary">
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
        {{-- Hizmet Süreç Ekleme Modal Bitiş --}}
    @endcan


    @can(PermissionEnum::BUSINESS_PROCESSES_UPDATE)
        {{-- Hizmet Süreç Güncelleme Modal Başlangıç --}}
        <div class="modal fade" id="update-business-processes-modal" tabindex="-1" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Hizmet Süreç Güncelle
                        </h2>
                        <div id="update-business-processes-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="update-business-processes-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="update-business-processes-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>

                        <button type="submit" form="update-form" id="update-business-processes-btn" class="btn btn-light-primary">
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


    @can(PermissionEnum::BUSINESS_PROCESSES_DELETE)
        {{-- Silinen Hizmet Süreç Listesi Başlangıç --}}
        <div class="modal fade" id="trashed-business-processes-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Silinmiş Hizmetler</h2>
                        <div id="trashed-business-processes-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body py-10 px-lg-10">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <i class="fa-solid fa-magnifying-glass fs-5"></i>
                            </span>
                            <input type="text" data-kt-business-processes-trashed-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Başlığa Göre Ara..." />
                        </div>

                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="trashed-business-processes-lists">
                            <thead>
                                <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center min-w150">İşlemler</th>
                                    <th class="text-center min-w150">Üst Başlık</th>
                                    <th class="text-center min-w150">Başlık</th>
                                    <th class="text-center min-w250">Silinme Nedeni</th>
                                    <th class="text-center min-w100">Sıralama</th>
                                </tr>
                            </thead>

                            <tbody class="fw-semibold text-gray-600 text-center">
                                @foreach ($deletedBusinessProcesses as $processes)
                                    <tr data-id="{{ $processes->id }}">
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

                                        <td>{{ $processes?->content?->header }}</td>

                                        <td>{{ $processes?->content?->title }}</td>

                                        <td>{{ $processes?->deleted_description }}</td>

                                        <td>{{ $processes?->sorting }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-business-processes-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Hizmet Süreç Listesi Bitiş --}}

        {{-- Hizmet Süreç Silme Modal Başlangıç --}}
        <div class="modal fade" id="delete-business-processes-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-danger me-3 business-processes-delete-btn" data-id="">Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Hizmet Süreç Silme Modal Bitiş --}}

        {{-- Silinen Hizmet Süreç Detayını Görüntüleme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-view-business-processes-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Silinen Hizmet
                        </h2>
                        <div id="trashed-view-business-processes-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="trashed-view-business-processes-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-view-business-processes-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Hizmet Süreç Detayını Görüntüleme Modal Bitiş --}}

        {{-- Silinen Hizmet Sürecini Geri Getirme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-restore-business-processes-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-primary me-3 trashed-restore-business-processes-delete-btn" data-id="">Geri Yükle</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Hizmet Sürecini Geri Getirme Modal Bitiş --}}
    @endcan


    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        {{-- Kalıcı Olarak Silme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-remove-business-processes-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-focus="false" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-danger me-3 trashed-remove-business-processes-delete-btn" data-id="">Kalıcı Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Kalıcı Olarak Silme Modal Bitiş --}}
    @endhasrole
@endsection


@section('js')
    <script src="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/business-processes/datatable/datatable.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/business-processes/view/read-view.js') }}"></script>

    @can(PermissionEnum::BUSINESS_PROCESSES_CREATE)
        <script src="{{ asset('backend/assets/js/modules/business-processes/create/add.js') }}"></script>

        <script>
            @foreach ($languages as $al)
                $('#description_{{ $al?->id }}').maxlength({
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
            @endforeach
        </script>
    @endcan

    @can(PermissionEnum::BUSINESS_PROCESSES_UPDATE)
        <script src="{{ asset('backend/assets/js/modules/business-processes/update/change-status.js') }}"></script>

        <script src="{{ asset('backend/assets/js/modules/business-processes/update/get-edit.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/business-processes/update/update.js') }}"></script>
    @endcan

    @can(PermissionEnum::BUSINESS_PROCESSES_DELETE)
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
        <script src="{{ asset('backend/assets/js/modules/business-processes/delete/get-delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/business-processes/delete/delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/business-processes/delete/get-trashed.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/business-processes/delete/get-trashed-restore.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/business-processes/delete/trashed-restore.js') }}"></script>
    @endcan

    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        <script src="{{ asset('backend/assets/js/modules/business-processes/remove/get-trashed-remove.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/business-processes/remove/trashed-remove.js') }}"></script>
    @endhasrole
@endsection
