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
    @php
        $pageName = !empty($page?->content?->title) ? ' ( ' . $page?->content?->title . ' )' : '';
    @endphp
    @include('backend.includes.toolbar', [
        'title' => 'Sayfa Detay',
        'breadcrumbs' => [['name' => 'Sayfalar', 'url' => route('admin.pages')], ['name' => 'Sayfa Detay' . $pageName]],
    ])
@endsection


@section('content')
    <div class="row">
        <div class="col-md-6 mb-7">
            <div class="card card-bordered">
                <div class="card-header">
                    <div class="card-title">
                        <h2 class="fw-bold">Bölümler</h2>
                    </div>

                    <div class="card-toolbar">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <i class="fa-solid fa-magnifying-glass fs-5"></i>
                            </span>
                            <input type="text" id="search" class="form-control form-control-solid w-250px ps-15" placeholder="Bileşen Ara..." />
                        </div>
                    </div>
                </div>

                <div class="cst-draggable-body card-body overflow-scroll" style="max-height: 800px;">
                    <div class="row draggable-zone component-zone">
                        @php
                            $selectedSectionIds = $page?->sections->pluck('id')->toArray() ?? [];
                        @endphp

                        @foreach ($sections as $section)
                            @if ($section?->isActiveStatus())
                                @continue(in_array($section->id, $selectedSectionIds))

                                <div class="col-md-12 draggable mb-4" data-id="{{ $section?->id }}">
                                    <div class="border border-dashed border-gray-500 rounded px-7 py-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-2 cst-sortable">
                                                <img src="{{ $section?->getImage() }}" class="w-50px ms-n1 me-1">
                                            </div>
                                            <div class="col-md-8 cst-sortable">
                                                <div class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $section?->title }}</div>
                                            </div>
                                            <div class="col-md-2 d-flex">
                                                @can(PermissionEnum::PAGE_SECTION_VIEW)
                                                    <button type="button" class="btn btn-icon read-view-btn btn-color-gray-700 btn-active-color-primary justify-content-end">
                                                        <span class="svg-icon svg-icon-1">
                                                            <i class="fa-regular fa-eye fs-2"></i>
                                                        </span>
                                                    </button>
                                                @endcan
                                                <button type="button" class="btn btn-icon btn-color-gray-700 btn-active-color-success justify-content-end">
                                                    <span class="svg-icon svg-icon-1">
                                                        <i class="fa-regular fa-square-plus fs-1"></i>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">

            <div class="card card-bordered">
                <form id="update-form" class="form" data-action="{{ route('admin.pages.detail.update') }}">
                    @csrf

                    <input type="hidden" name="id" id="page" value="{{ $page->id }}">

                    <div class="card-header">
                        <div class="card-title">
                            <h2 class="fw-bold">Sayfa Detayı</h2>
                        </div>
                    </div>

                    <div class="card-body cst-draggable-body">
                        <div class="row draggable-zone page-detail-zone">
                            @if ($pageSectionPages?->isNotEmpty())
                                @foreach ($pageSectionPages as $content)
                                    @if ($content?->section?->isActiveStatus())
                                        <div class="col-md-12 draggable mb-4" data-id="{{ $content?->section?->id }}">
                                            <div class="border border-dashed border-gray-500 rounded px-7 py-3">
                                                <div class="row align-items-center">
                                                    <div class="col-md-2 cst-sortable">
                                                        <img src="{{ $content?->section?->getImage() }}" class="w-50px ms-n1 me-1">
                                                    </div>
                                                    <div class="col-md-8 cst-sortable">
                                                        <div class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $content?->section?->title }}</div>
                                                    </div>
                                                    <div class="col-md-2 d-flex">
                                                        @can(PermissionEnum::PAGE_SECTION_VIEW)
                                                            <button type="button" class="btn btn-icon read-view-btn btn-color-gray-700 btn-active-color-primary justify-content-end">
                                                                <span class="svg-icon svg-icon-1">
                                                                    <i class="fa-regular fa-eye fs-2"></i>
                                                                </span>
                                                            </button>
                                                        @endcan
                                                        <button type="button" class="btn btn-icon btn-color-gray-700 btn-active-color-danger justify-content-end">
                                                            <span class="svg-icon svg-icon-1">
                                                                <i class="fa-regular fa-trash-can fs-2"></i>
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="empty-message text-center text-danger fw-bold py-3">Bölüm Bulunamadı</div>
                            @endif
                        </div>
                    </div>

                    @can(PermissionEnum::PAGE_UPDATE)
                        <div class="card-footer d-flex flex-center">
                            <button type="submit" id="update-page-btn" class="btn btn-light-primary">
                                <span class="indicator-label">Güncelle</span>
                                <span class="indicator-progress">
                                    Lütfen Bekleyiniz...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    @endcan
                </form>
            </div>
        </div>
    </div>


    @can(PermissionEnum::PAGE_SECTION_VIEW)
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
    @endcan

@endsection


@section('js')
    <script src="{{ asset('backend/assets/plugins/custom/sortable/sortable.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('backend/assets/js/custom/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('backend/assets/js/modules/pages-detail/datatable/datatable.js') }}"></script>

    @can(PermissionEnum::PAGE_UPDATE)
        <script src="{{ asset('backend/assets/js/modules/pages-detail/update/update.js') }}"></script>
    @endcan

    @can(PermissionEnum::PAGE_SECTION_VIEW)
        <script src="{{ asset('backend/assets/js/modules/pages-detail/view/read-view.js') }}"></script>
    @endcan
@endsection
