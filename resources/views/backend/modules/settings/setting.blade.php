@extends('backend.layouts.app')

@use(App\Enums\Permissions\PermissionEnum)

@section('css')
    <link href="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('toolbar')
    @include('backend.includes.toolbar', ['title' => 'Ayarlar', 'breadcrumbs' => [['name' => 'Ayarlar']]])
@endsection

@section('content')
    <div class="row g-6 mb-6 g-xl-9 mb-xl-9">
        @can([PermissionEnum::GENERAL_VIEW])
            <div class="col-md-3">
                <div class="card border border-dashed border-gray-300 rounded">
                    <div class="card-body d-flex flex-center flex-column py-9 px-5">
                        <div class="symbol symbol-65px symbol-circle mb-5">
                            <span class="symbol-label fs-2x fw-semibold bg-light-info">
                                <i class="fa-solid fa-screwdriver-wrench text-danger fs-2"></i>
                            </span>
                        </div>

                        <a href="{{ route('admin.setting.general') }}" class="fs-4 text-gray-800 text-hover-primary fw-bold mb-6">Genel</a>
                        <a href="{{ route('admin.setting.general') }}" class="btn btn-sm btn-light-primary">
                            Detaya Git
                            <span class="svg-icon svg-icon-3 m-0">
                                <i class="fa-solid fa-arrow-right mx-3 p-0"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can([PermissionEnum::ADDRESS_VIEW])
            <div class="col-md-3">
                <div class="card border border-dashed border-gray-300 rounded">
                    <div class="card-body d-flex flex-center flex-column py-9 px-5">
                        <div class="symbol symbol-65px symbol-circle mb-5">
                            <span class="symbol-label fs-2x fw-semibold bg-light-info">
                                <i class="fa-solid fa-map-location-dot text-danger fs-2"></i>
                            </span>
                        </div>

                        <a href="{{ route('admin.setting.address') }}" class="fs-4 text-gray-800 text-hover-primary fw-bold mb-6">Adres</a>
                        <a href="{{ route('admin.setting.address') }}" class="btn btn-sm btn-light-primary">
                            Detaya Git
                            <span class="svg-icon svg-icon-3 m-0">
                                <i class="fa-solid fa-arrow-right mx-3 p-0"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can([PermissionEnum::SOCIAL_VIEW])
            <div class="col-md-3">
                <div class="card border border-dashed border-gray-300 rounded">
                    <div class="card-body d-flex flex-center flex-column py-9 px-5">
                        <div class="symbol symbol-65px symbol-circle mb-5">
                            <span class="symbol-label fs-2x fw-semibold bg-light-info">
                                <i class="fa-solid fa-share-nodes text-danger fs-2"></i>
                            </span>
                        </div>

                        <a href="{{ route('admin.setting.social') }}" class="fs-4 text-gray-800 text-hover-primary fw-bold mb-6">Sosyal Medya</a>
                        <a href="{{ route('admin.setting.social') }}" class="btn btn-sm btn-light-primary">
                            Detaya Git
                            <span class="svg-icon svg-icon-3 m-0">
                                <i class="fa-solid fa-arrow-right mx-3 p-0"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can([PermissionEnum::LOGO_VIEW])
            <div class="col-md-3">
                <div class="card border border-dashed border-gray-300 rounded">
                    <div class="card-body d-flex flex-center flex-column py-9 px-5">
                        <div class="symbol symbol-65px symbol-circle mb-5">
                            <span class="symbol-label fs-2x fw-semibold bg-light-info">
                                <i class="fa-solid fa-image text-danger fs-2"></i>
                            </span>
                        </div>

                        <a href="{{ route('admin.setting.logo') }}" class="fs-4 text-gray-800 text-hover-primary fw-bold mb-6">Logo</a>
                        <a href="{{ route('admin.setting.logo') }}" class="btn btn-sm btn-light-primary">
                            Detaya Git
                            <span class="svg-icon svg-icon-3 m-0">
                                <i class="fa-solid fa-arrow-right mx-3 p-0"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can([PermissionEnum::PLUGIN_VIEW])
            <div class="col-md-3">
                <div class="card border border-dashed border-gray-300 rounded">
                    <div class="card-body d-flex flex-center flex-column py-9 px-5">
                        <div class="symbol symbol-65px symbol-circle mb-5">
                            <span class="symbol-label fs-2x fw-semibold bg-light-info">
                                <i class="fa-solid fa-puzzle-piece text-danger fs-2"></i>
                            </span>
                        </div>

                        <a href="{{ route('admin.setting.plugin') }}" class="fs-4 text-gray-800 text-hover-primary fw-bold mb-6">Eklenti</a>
                        <a href="{{ route('admin.setting.plugin') }}" class="btn btn-sm btn-light-primary">
                            Detaya Git
                            <span class="svg-icon svg-icon-3 m-0">
                                <i class="fa-solid fa-arrow-right mx-3 p-0"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        @endcan

        @can([PermissionEnum::EMAIL_VIEW])
            <div class="col-md-3">
                <div class="card border border-dashed border-gray-300 rounded">
                    <div class="card-body d-flex flex-center flex-column py-9 px-5">
                        <div class="symbol symbol-65px symbol-circle mb-5">
                            <span class="symbol-label fs-2x fw-semibold bg-light-info">
                                <i class="fa-solid fa-envelope-circle-check text-danger fs-2"></i>
                            </span>
                        </div>

                        <a href="{{ route('admin.setting.email') }}" class="fs-4 text-gray-800 text-hover-primary fw-bold mb-6">E-Mail</a>
                        <a href="{{ route('admin.setting.email') }}" class="btn btn-sm btn-light-primary">
                            Detaya Git
                            <span class="svg-icon svg-icon-3 m-0">
                                <i class="fa-solid fa-arrow-right mx-3 p-0"></i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        @endcan
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endsection
