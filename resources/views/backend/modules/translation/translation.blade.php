@extends('backend.layouts.app')

@use(App\Enums\Roles\RoleEnum)
@use(App\Enums\Permissions\PermissionEnum)

@section('css')
    <link href="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('toolbar')
    @include('backend.includes.toolbar', [
        'title' => 'Dil Yönetimi',
        'breadcrumbs' => [
            [
                'name' => 'Dil Yönetimi',
                'url' => route('admin.language'),
            ],
            [
                'name' => 'Çeviriler ( ' . $translation?->language?->name . ' )',
            ],
        ],
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
                    <input type="text" data-kt-translation-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Arama Yapın..." />
                </div>
            </div>

            @hasrole([RoleEnum::SUPER_ADMIN])
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <button type="button" id="add-translation" class="btn btn-sm btn-bg-light btn-light-primary me-3" data-bs-toggle="modal" data-bs-target="#add-translation-modal">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-language fs-5"></i>
                            </span>
                            Çeviri Ekle
                        </button>
                    </div>
                </div>
            @endhasrole
        </div>

        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="translation-lists">
                <thead>
                    <tr class="text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="text-center min-w150">İşlemler</th>
                        <th class="text-center min-w150">Grup</th>
                        <th class="text-center min-w150">Anahtar</th>
                        <th class="min-w250">Değer</th>
                    </tr>
                </thead>

                <tbody class="fw-semibold text-gray-600 text-center">
                    @foreach ($translation->contents as $content)
                        <tr data-id="{{ $content->id }}">
                            <td>
                                <button type="button" class="read-view-btn btn btn-icon btn-bg-light btn-light-primary btn-sm m-2">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-info btn-sm m-2">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                @hasrole([RoleEnum::SUPER_ADMIN])
                                    @if ($content->isDeletable())
                                        <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm m-2">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @endif
                                @endhasrole
                            </td>

                            <td>{{ $content->group }}</td>

                            <td>{{ $content->key }}</td>

                            <td class="text-start">{{ $content->value }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- Çeviriler Detayını Görüntüleme Modal Başlangıç --}}
    <div class="modal fade" id="view-translation-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">
                        Son Güncelleme
                    </h2>
                    <div id="view-translation-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-regular fa-circle-xmark fs-2"></i>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <div class="d-flex flex-column" id="view-translation-modal-scroll" data-kt-scroll="false">

                    </div>
                </div>

                <div class="modal-footer flex-center">
                    <button type="button" id="view-translation-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Çeviriler Detayını Görüntüleme Modal Bitiş --}}


    @hasrole([RoleEnum::SUPER_ADMIN])
        {{-- Ekleme Modal Başlangıç --}}
        <div class="modal fade" id="add-translation-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form class="form" data-action="{{ route('admin.translation.content.create') }}" id="create-form">
                        @csrf

                        <input type="hidden" name="translation_id" value="{{ $translation->id }}">

                        <div class="modal-header">
                            <h2 class="fw-bold">Çeviri Ekle
                                @if (!empty($translation?->language?->code))
                                    ( {{ $translation?->language?->getCodeUppercase() }} )
                                @endif
                            </h2>
                            <div id="add-translation-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="fa-regular fa-circle-xmark fs-2"></i>
                            </div>
                        </div>

                        <div class="modal-body py-10 px-lg-10">
                            <div class="scroll-y me-n7 pe-7" id="add-translation-modal-scroll" data-kt-scroll="false">

                                <div class="row mb-7">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-semibold mb-2">Grup Adı</label>
                                        <input type="text" name="group" class="form-control form-control-solid" placeholder="Grup Adı *" min="1" required />
                                    </div>

                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-semibold mb-2">Anahtar</label>
                                        <input type="text" name="key" class="form-control form-control-solid" placeholder="Anahtar *" min="1" required />
                                    </div>
                                </div>

                                <div class="fv-row">
                                    <label class="required fs-6 fw-semibold mb-2">Değer</label>
                                    <textarea name="value" class="form-control form-control-solid" rows="4" name="application" placeholder="Değer *" min="1" required></textarea>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer flex-center">
                            <button type="button" id="add-translation-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                                Kapat
                            </button>

                            <button type="submit" id="add-translation-btn" class="btn btn-light-primary">
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

        {{-- Silme Modal Başlangıç --}}
        <div class="modal fade" id="delete-translation-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <button type="button" class="btn btn-icon btn-light-danger mb-5 mt-5 fs-2">
                            <i class="fa-solid fa-trash-can fs-3"></i>
                        </button>

                        <h3>Silmek İstediğine Emin misin ?</h3>
                        <small class="text-danger">( Bu İşlem Geri Alınamaz !!! )</small>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-light-primary me-3" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="button" class="btn btn-light-danger me-3 translation-delete-btn" data-id="">Sil</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Silme Modal Bitiş --}}
    @endhasrole


    @can(PermissionEnum::STATIC_TEXT_UPDATE)
        {{-- Güncelleme Modal Başlangıç --}}
        <div class="modal fade" id="update-translation-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <form class="form" data-action="{{ route('admin.translation.content.update') }}" id="update-form">
                        @csrf

                        <input type="hidden" name="id" id="update-id">

                        <input type="hidden" name="translation_id" id="update-translation-id">

                        <div class="modal-header">
                            <h2 class="fw-bold">Çeviri Güncelle
                                @if (!empty($translation?->language?->code))
                                ( {{ $translation?->language?->getCodeUppercase() }} )
                                @endif
                            </h2>
                            <div id="update-translation-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <i class="fa-regular fa-circle-xmark fs-2"></i>
                            </div>
                        </div>

                        <div class="modal-body py-10 px-lg-10">
                            <div class="scroll-y me-n7 pe-7" id="update-translation-modal-scroll" data-kt-scroll="false">

                                <div class="row mb-7">
                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-semibold mb-2">Grup Adı</label>
                                        <input type="text" name="group" class="form-control form-control-solid" id="update-group" placeholder="Grup Adı *" min="1" required />
                                    </div>

                                    <div class="col-md-6 fv-row">
                                        <label class="required fs-6 fw-semibold mb-2">Anahtar</label>
                                        <input type="text" name="key" class="form-control form-control-solid" id="update-key" placeholder="Anahtar *" min="1" required />
                                    </div>
                                </div>

                                <div class="fv-row">
                                    <label class="required fs-6 fw-semibold mb-2">Değer</label>
                                    <textarea name="value" class="form-control form-control-solid" id="update-value" rows="4" name="application" placeholder="Değer *" min="1" required></textarea>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer flex-center">
                            <button type="button" id="update-translation-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                                Kapat
                            </button>

                            <button type="submit" id="update-translation-btn" class="btn btn-light-primary">
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
    @endcan

@endsection

@section('js')
    <script src="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/translation/datatable/datatable.js') }}"></script>

    <script src="{{ asset('backend/assets/js/modules/translation/view/read-view.js') }}"></script>

    @hasrole([RoleEnum::SUPER_ADMIN])
        <script src="{{ asset('backend/assets/js/modules/translation/create/add.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/translation/delete/delete.js') }}"></script>
    @endhasrole

    @can(PermissionEnum::STATIC_TEXT_READ)

    @endcan

    @can(PermissionEnum::STATIC_TEXT_UPDATE)
        <script src="{{ asset('backend/assets/js/modules/translation/update/update.js') }}"></script>
        <script src="{{ asset('backend/assets/js/modules/translation/update/get.js') }}"></script>
    @endcan
@endsection
