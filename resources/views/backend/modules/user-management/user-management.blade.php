@extends('backend.layouts.app')

@use(App\Enums\Permissions\PermissionEnum)
@use(App\Enums\Images\ImageTypeEnum)
@use(App\Enums\Images\ImageSizeEnum)
@use(App\Enums\Roles\RoleEnum)

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
    @include('backend.includes.toolbar', ['title' => 'Yetkili Yönetimi', 'breadcrumbs' => [['name' => 'Yetkili Yönetimi']]])
@endsection

@section('content')
    <div class="card">
        <div class="cst-table-header card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                        <i class="fa-solid fa-magnifying-glass fs-5"></i>
                    </span>
                    <input type="text" data-kt-user-management-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Ada Göre Ara..." />
                </div>
            </div>

            @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <button type="button" id="trashed-user-management" class="btn btn-sm btn-light-danger me-3 mt-2" data-bs-toggle="modal" data-bs-target="#trashed-user-management-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-trash-can fs-5"></i>
                            </span>
                            Silinenler
                        </button>
                        <button type="button" id="banned-user-management" class="btn btn-sm btn-light-info me-3 mt-2" data-bs-toggle="modal" data-bs-target="#banned-user-management-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-ban fs-5"></i>
                            </span>
                            Banlananlar
                        </button>
                        <button type="button" id="add-user-management" class="btn btn-sm btn-bg-light btn-light-primary me-3 mt-2" data-bs-toggle="modal" data-bs-target="#add-user-management-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-user-shield fs-5"></i>
                            </span>
                            Yetkili Ekle
                        </button>
                    </div>
                </div>
            @endhasrole
        </div>

        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="user-management-lists">
                <thead>
                    <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="text-center min-w250">İŞLEMLER</th>
                        <th class="text-center min-w150">AD SOYAD</th>
                        <th class="text-center min-w150">E-MAIL</th>
                        <th class="text-center min-w150">TELEFON</th>
                        <th class="text-center min-w150">STATÜ</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600 text-center">
                    @foreach ($activeRoles as $role)
                        <tr data-id="{{ $role->id }}">
                            <td>
                                @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
                                    <button type="button" class="read-active-view-btn btn btn-icon btn-bg-light btn-light-warning btn-sm me-1 mb-1">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                @endhasrole

                                <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1 mb-1">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>

                                <button type="button" class="password-btn btn btn-icon btn-bg-light btn-light-success btn-sm me-1 mb-1">
                                    <i class="fa-solid fa-key"></i>
                                </button>

                                @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
                                    @if (!$role->isSuperAdmin() && $user->id !== $role->id)
                                        <button type="button" class="permission-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1 mb-1">
                                            <i class="fa-solid fa-shield-halved"></i>
                                        </button>

                                        <button type="button" class="ban-btn btn btn-icon btn-bg-light btn-light-dark btn-sm me-1 mb-1">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>

                                        <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1 mb-1">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @endif
                                @endhasrole

                            </td>

                            <td>{{ $role->name }}</td>

                            <td>{{ $role->email }}</td>
                            <td>{{ $role->phone }}</td>

                            <td>
                                {!! $role->getRoleHtml() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
        {{-- Yetkili Detayını Görüntüleme Modal Başlangıç --}}
        <div class="modal fade" id="view-user-management-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Yetkili İnceleme
                        </h2>
                        <div id="view-user-management-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="view-user-management-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="view-user-management-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Yetkili Detayını Görüntüleme Modal Bitiş --}}



        {{-- Ekleme Modal Başlangıç --}}
        <div class="modal fade" id="add-user-management-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <form id="create-form" class="form" data-action="{{ route('admin.user.management.create') }}">
                        @csrf

                        <div class="modal-header">
                            <h2 class="fw-bold">
                                Yetkili Ekle
                            </h2>
                            <div id="add-user-management-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="fa-regular fa-circle-xmark fs-2"></i>
                            </div>
                        </div>

                        <div class="modal-body scroll-y mx-5 my-7">
                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="add-user-management-modal-scroll" data-kt-scroll="false">

                                <div class="fv-row mb-7">
                                    <label class="d-block fw-semibold fs-6">Avatar</label>

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
                                            Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 100x100
                                        </small>
                                    </div>
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="required fs-5 fw-semibold mb-2">Kullanıcı Adı</label>
                                    <input type="text" name="name" class="form-control form-control-solid" placeholder="Kullanıcı Adı Girin *" min="1" required />
                                </div>

                                <div class="row">
                                    <div class="col-md-6 fv-row mb-7">
                                        <label class="required fs-5 fw-semibold mb-2">E-Mail</label>
                                        <input type="text" name="email" class="form-control form-control-solid" placeholder="E-Mail Adresi Girin *" min="1" required />
                                    </div>

                                    <div class="col-md-6 fv-row mb-7">
                                        <label class="fs-5 fw-semibold mb-2">Telefon Numarası</label>
                                        <input type="text" name="phone" class="form-control form-control-solid" id="sorting" placeholder="Telefon Numarası Girin" minlength="10" maxlength="11" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 fv-row mb-7">
                                        <label class="required fs-5 fw-semibold mb-2">Şifre</label>
                                        <div class="position-relative">
                                            <input id="passwordInput" class="form-control form-control-solid" type="password" placeholder="Şifre *" minlength="6" required name="password" autocomplete="off" />
                                            <span id="togglePassword" class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" style="cursor: pointer;">
                                                <i class="bi bi-eye-slash fs-2"></i>
                                                <i class="bi bi-eye fs-2 d-none"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 fv-row mb-7">
                                        <label class="required fs-5 fw-semibold mb-2">Şifre Tekrarı</label>
                                        <div class="position-relative">
                                            <input type="password" name="password_confirmation" class="form-control form-control-solid" id="confirmPasswordInput" placeholder="Şifreyi Tekrar Girin *" minlength="6" required autocomplete="off" />
                                            <span id="toggleConfirmPassword" class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" style="cursor: pointer;">
                                                <i class="bi bi-eye-slash fs-2"></i>
                                                <i class="bi bi-eye fs-2 d-none"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center cst-mobil-checkbox mb-10">
                                    <div class="col-md-12 fv-row mb-4 mt-2 px-7">
                                        <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                            <input type="checkbox" name="send_email" class="form-check-input" id="email-send">
                                            <span class="form-check-label fs-6 fw-semibold mx-4" for="email-send">
                                                Şifreyi E-Mail İle Gönder
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="fv-row">
                                    <label class="required fs-4 fw-semibold mb-2">Yetkili İzinleri</label>

                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5 cst-permission">
                                            <tbody class="text-gray-600 fw-semibold">
                                                @can(RoleEnum::SUPER_ADMIN)
                                                    <tr>
                                                        <td class="text-gray-800">
                                                            Hepsini İşaretle
                                                        </td>
                                                        <td>
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-9">
                                                                <input class="form-check-input" type="checkbox" id="user-management-roles-select-all" />
                                                                <span class="form-check-label" for="user-management-roles-select-all">Tümünü Seç</span>
                                                            </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-gray-800">
                                                            Admin
                                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Sisteme Tam Erişim Sağlar"></i>
                                                        </td>
                                                        <td>
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-9">
                                                                <input type="checkbox" name="admin" class="form-check-input" id="add-admin" />
                                                                <span class="form-check-label">Yönetici Ekle</span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td class="text-gray-800">
                                                            Hepsini İşaretle
                                                        </td>
                                                        <td>
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-9">
                                                                <input class="form-check-input" type="checkbox" id="user-management-roles-select-all" />
                                                                <span class="form-check-label" for="user-management-roles-select-all">Tümünü Seç</span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                @endcan
                                                @foreach (PermissionEnum::getValues() as $key => $values)
                                                    <tr>
                                                        @if (!empty($values[0]['view']))
                                                            <td class="text-gray-900 fw-bold">
                                                                {!! $values[0]['view'] ?? '' !!} {{ $key }}
                                                            </td>
                                                        @else
                                                            <td class="text-gray-800">
                                                                {{ $key }}
                                                            </td>
                                                        @endif

                                                        <td>
                                                            <div class="d-flex">
                                                                @foreach ($values as $value)
                                                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $value['value'] }}" />
                                                                        <span class="form-check-label">{{ $value['title'] }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer flex-center">
                            <button type="button" id="add-user-management-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                                Kapat
                            </button>

                            <button type="submit" id="add-user-management-btn" class="btn btn-light-primary">
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



        {{-- Yetkili İzinleri Güncelleme Modal Başlangıç --}}
        <div class="modal fade" id="permission-user-management-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <form id="update-permission-form" class="form" data-action="{{ route('admin.user.management.update.permission') }}">
                        @csrf

                        <input type="hidden" name="id" id="update-permission-id" required min="1">

                        <div class="modal-header">
                            <h2 class="fw-bold">
                                Yetkili İzinleri
                            </h2>
                            <div id="permission-user-management-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="fa-regular fa-circle-xmark fs-2"></i>
                            </div>
                        </div>

                        <div class="modal-body scroll-y mx-5 my-7">
                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="permission-user-management-modal-scroll" data-kt-scroll="false">

                                <div class="fv-row">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                                            <tbody class="text-gray-600 fw-semibold">
                                                @can(RoleEnum::SUPER_ADMIN)
                                                    <tr>
                                                        <td class="text-gray-800">
                                                            Hepsini İşaretle
                                                        </td>
                                                        <td>
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-9">
                                                                <input class="form-check-input" type="checkbox" id="user-management-permission-roles-select-all" />
                                                                <span class="form-check-label" for="user-management-permission-roles-select-all">Tümünü Seç</span>
                                                            </label>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="text-gray-800">
                                                            Admin
                                                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Sisteme Tam Erişim Sağlar"></i>
                                                        </td>
                                                        <td>
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-9">
                                                                <input type="checkbox" name="{{ RoleEnum::ADMIN->value }}" class="form-check-input" id="update-admin" />
                                                                <span class="form-check-label">Yönetici Ekle</span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td class="text-gray-800">
                                                            Hepsini İşaretle
                                                        </td>
                                                        <td>
                                                            <label class="form-check form-check-sm form-check-custom form-check-solid me-9">
                                                                <input class="form-check-input" type="checkbox" id="user-management-permission-roles-select-all" />
                                                                <span class="form-check-label" for="user-management-permission-roles-select-all">Tümünü Seç</span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                @endcan
                                                @foreach (PermissionEnum::getValues() as $key => $values)
                                                    <tr>
                                                        @if (!empty($values[0]['view']))
                                                            <td class="text-gray-900 fw-bold">
                                                                {!! $values[0]['view'] ?? '' !!} {{ $key }}
                                                            </td>
                                                        @else
                                                            <td class="text-gray-800">
                                                                {{ $key }}
                                                            </td>
                                                        @endif

                                                        <td>
                                                            <div class="d-flex">
                                                                @foreach ($values as $value)
                                                                    <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $value['value'] }}" />
                                                                        <span class="form-check-label">{{ $value['title'] }}</span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer flex-center">
                            <button type="button" id="permission-user-management-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                                Kapat
                            </button>

                            <button type="submit" id="permission-user-management-btn" class="btn btn-light-primary">
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
        {{-- Yetkili İzinleri Güncelleme Modal Bitiş --}}



        {{-- Banlanmış Yetkili Modal Başlangıç --}}
        <div class="modal fade" id="banned-user-management-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Banlanan Yetkililer</h2>
                        <div id="banned-user-management-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body py-10 px-lg-10">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <i class="fa-solid fa-magnifying-glass fs-5"></i>
                            </span>
                            <input type="text" data-kt-customer-banned-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Ada Göre Ara..." />
                        </div>

                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="banned-user-management-lists">
                            <thead>
                                <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center min-w125">İŞLEMLER</th>
                                    <th class="text-center min-w125">AVATAR</th>
                                    <th class="text-center min-w200">AD</th>
                                    <th class="text-center min-w250">BANLAMA NEDENİ</th>
                                    <th class="text-center min-w200">E-MAIL</th>
                                    <th class="text-center min-w100">STATÜ</th>
                                </tr>
                            </thead>

                            <tbody class="fw-semibold text-gray-600 text-center">
                                @foreach ($bannedRoles as $role)
                                    <tr data-id="{{ $role->id }}">
                                        <td>
                                            <button type="button" class="read-banned-view-btn btn btn-icon btn-bg-light btn-light-warning btn-sm me-1">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="banned-restore-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                                                <i class="fa-solid fa-rotate-right"></i>
                                            </button>
                                        </td>

                                        <td>
                                            <div class="symbol symbol-50px me-3">
                                                <img src="{{ $role?->getImage() }}">
                                            </div>
                                        </td>

                                        <td>{{ $role?->name }}</td>

                                        <td>{{ $role?->banned_description }}</td>

                                        <td>{{ $role?->email }}</td>

                                        <td>
                                            {!! $role?->getRoleHtml() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="banned-user-management-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Banlanmış Yetkili Modal Bitiş --}}

        {{-- Banlanmış Yetkiliyi Getirme Modal Başlangıç --}}
        <div class="modal fade" id="banned-restore-user-management-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <button type="button" class="btn btn-icon btn-light-info mb-5 mt-5 fs-2">
                            <i class="fa-solid fa-rotate-right fs-3"></i>
                        </button>

                        <h3>Aktifleştirmek İstediğine Emin misin ?</h3>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-light-danger me-3" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="button" class="btn btn-light-primary me-3 banned-restore-user-management-delete-btn" data-id="">Geri Yükle</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Banlanmış Yetkiliyi Getirme Modal Bitiş --}}

        {{-- Banlama Modal Başlangıç --}}
        <div class="modal fade" id="ban-user-management-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <button type="button" class="btn btn-icon btn-light-danger mb-5 mt-5 fs-2">
                            <i class="fa-solid fa-ban fs-3"></i>
                        </button>

                        <h3>Banlamak İstediğine Emin misin ?</h3>

                        <div class="fv-row mt-7">
                            <textarea name="banned_description" id="banned-description" class="form-control form-control-solid" rows="4" placeholder="Banlama Nedeni *" minlength="5" maxlength="200"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-light-primary me-3" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="button" class="btn btn-light-danger me-3 user-management-ban-btn" data-id="">Banla</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Banlama Modal Bitiş --}}



        {{-- Silinen Yetkililer Modal Başlangıç --}}
        <div class="modal fade" id="trashed-user-management-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Silinen Yetkililer</h2>
                        <div id="trashed-user-management-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
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

                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="trashed-user-management-lists">
                            <thead>
                                <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="text-center min-w125">İŞLEMLER</th>
                                    <th class="text-center min-w125">AVATAR</th>
                                    <th class="text-center min-w200">AD</th>
                                    <th class="text-center min-w250">SİLİNME NEDENİ</th>
                                    <th class="text-center min-w200">E-MAIL</th>
                                    <th class="text-center min-w100">STATÜ</th>
                                </tr>
                            </thead>

                            <tbody class="fw-semibold text-gray-600 text-center">
                                @foreach ($deletedRoles as $role)
                                    <tr data-id="{{ $role->id }}">
                                        <td>
                                            <button type="button" class="read-deleted-view-btn btn btn-icon btn-bg-light btn-light-warning btn-sm me-1">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="trashed-restore-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                                                <i class="fa-solid fa-recycle"></i>
                                            </button>
                                        </td>

                                        <td>
                                            <div class="symbol symbol-50px me-3">
                                                <img src="{{ $role?->getImage() }}">
                                            </div>
                                        </td>

                                        <td>{{ $role?->name }}</td>

                                        <td>{{ $role?->deleted_description }}</td>

                                        <td>{{ $role?->email }}</td>

                                        <td>
                                            {!! $role?->getRoleHtml() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="trashed-user-management-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinen Yetkililer Modal Bitiş --}}

        {{-- Yetkili Silme Modal Başlangıç --}}
        <div class="modal fade" id="delete-user-management-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-danger me-3 user-management-delete-btn" data-id="">Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Yetkili Silme Modal Bitiş --}}

        {{-- Silinmiş Yetkiliyi Getirme Modal Başlangıç --}}
        <div class="modal fade" id="trashed-restore-user-management-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <button type="button" class="btn btn-light-primary me-3 trashed-restore-user-management-delete-btn" data-id="">Geri Yükle</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silinmiş Yetkiliyi Getirme Modal Bitiş --}}

        {{-- Güncelleme Modal Başlangıç --}}
        <div class="modal fade" id="update-user-management-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form id="update-form" class="form" data-action="{{ route('admin.user.management.update') }}">
                        @csrf

                        <input type="hidden" name="id" id="update-id" required min="1">

                        <div class="modal-header">
                            <h2 class="fw-bold">
                                Yetkili Güncelle
                            </h2>
                            <div id="update-user-management-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="fa-regular fa-circle-xmark fs-2"></i>
                            </div>
                        </div>

                        <div class="modal-body scroll-y mx-5 my-7">
                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="update-user-management-modal-scroll" data-kt-scroll="false">

                                <div class="fv-row mb-7 text-center">
                                    <label class="d-block fw-semibold fs-6">Avatar</label>

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
                                            Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 100x100
                                        </small>
                                    </div>
                                </div>


                                <div class="fv-row mb-7">
                                    <label class="required fs-5 fw-semibold mb-2">Kullanıcı Adı</label>
                                    <input type="text" name="name" id="update-name" class="form-control form-control-solid" placeholder="Kullanıcı Adı Girin *" />
                                </div>

                                <div class="row mb-7">
                                    <div class="col-md-6 fv-row mb-7">
                                        <label class="required fs-5 fw-semibold mb-2">E-Mail</label>
                                        <input type="text" name="email" id="update-email" class="form-control form-control-solid" placeholder="E-Mail Adresi Girin *" min="1" required />
                                    </div>

                                    <div class="col-md-6 fv-row">
                                        <label class="fs-5 fw-semibold mb-2">Telefon Numarası</label>
                                        <input type="text" name="phone" id="update-phone" class="form-control form-control-solid" id="sorting" placeholder="Telefon Numarası Girin" minlength="10" maxlength="11" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer flex-center">
                            <button type="button" id="update-user-management-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                                Kapat
                            </button>

                            <button type="submit" id="update-user-management-btn" class="btn btn-light-primary">
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

        {{-- Şifre Güncelleme Modal Başlangıç --}}
        <div class="modal fade" id="change-password-user-management-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form id="update-password-form" class="form" data-action="{{ route('admin.user.management.update.password') }}">
                        @csrf

                        <input type="hidden" name="id" id="update-password-id" required min="1">

                        <div class="modal-header">
                            <h2 class="fw-bold">
                                Yetkili Şifre Güncelleme
                            </h2>
                            <div id="change-password-user-management-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="fa-regular fa-circle-xmark fs-2"></i>
                            </div>
                        </div>

                        <div class="modal-body scroll-y mx-5 my-7">
                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="change-password-user-management-modal-scroll" data-kt-scroll="false">

                                <div class="row mb-7">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-semibold mb-2">Şifre</label>
                                        <div class="position-relative mb-3">
                                            <input id="updatePasswordInput" class="form-control form-control-solid" type="password" placeholder="Şifre *" minlength="6" required name="password" autocomplete="off" />
                                            <span id="toggleUpdatePassword" class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" style="cursor: pointer;">
                                                <i class="bi bi-eye-slash fs-2"></i>
                                                <i class="bi bi-eye fs-2 d-none"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-5 fw-semibold mb-2">Şifre Tekrarı</label>
                                        <div class="position-relative mb-3">
                                            <input id="updateConfirmPasswordInput" class="form-control form-control-solid" type="password" placeholder="Şifreyi Tekrar Girin *" minlength="6" required name="password_confirmation" autocomplete="off" />
                                            <span id="toggleUpdateConfirmPassword" class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" style="cursor: pointer;">
                                                <i class="bi bi-eye-slash fs-2"></i>
                                                <i class="bi bi-eye fs-2 d-none"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center cst-mobil-checkbox">
                                    <div class="col-md-12 fv-row mb-4 mt-2 px-7">
                                        <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                            <input type="checkbox" name="send_email" class="form-check-input" id="update-email-send">
                                            <span class="form-check-label fs-6 fw-semibold mx-4" for="update-email-send">
                                                Şifreyi E-Mail İle Gönder
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer flex-center">
                            <button type="button" id="change-password-user-management-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                                Kapat
                            </button>

                            <button type="submit" id="change-password-user-management-btn" class="btn btn-light-primary">
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
        {{-- Şifre Güncelleme Modal Bitiş --}}
    @endhasrole
@endsection


@section('js')
    <script src="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/user-management/datatable/datatable.js') }}"></script>

    <script src="{{ asset('backend/assets/js/modules/user-management/management/management.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/user-management/create/add.js') }}"></script>

    <script src="{{ asset('backend/assets/js/modules/user-management/update/get.js') }}"></script>

    @hasrole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])
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

            $('#banned-description').maxlength({
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

        <script src="{{ asset('backend/assets/js/modules/user-management/view/read-active-view.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/user-management/view/read-deleted-view.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/user-management/view/read-banned-view.js') }}"></script>

        <script src="{{ asset('backend/assets/js/modules/user-management/update/update-permission.js') }}"></script>

        <script src="{{ asset('backend/assets/js/modules/user-management/delete/delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/user-management/delete/trashed-restore.js') }}"></script>

        <script src="{{ asset('backend/assets/js/modules/user-management/banned/get-permission-banned-delete.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/user-management/banned/banned.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/user-management/banned/banned-restore.js') }}"></script>
    @endhasrole

    <script src="{{ asset('backend/assets/js/modules/user-management/update/update.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/user-management/update/update-password.js') }}"></script>
@endsection
