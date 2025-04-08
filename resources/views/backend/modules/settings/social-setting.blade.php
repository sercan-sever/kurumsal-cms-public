@extends('backend.layouts.app')

@use(App\Enums\Permissions\PermissionEnum)

@section('toolbar')
    @include('backend.includes.toolbar', [
        'viewButton' => true,
        'viewButtonId' => $social?->id,
        'title' => 'Sosyal Medya Ayarları',
        'breadcrumbs' => [
            [
                'name' => 'Tüm Ayarlar',
                'url' => route('admin.setting'),
            ],
            [
                'name' => 'Sosyal Medya Ayarları',
            ],
        ],
    ])
@endsection

@section('css')
@endsection


@section('content')
    <div class="card">
        <form class="form" data-action="{{ route('admin.setting.social.update') }}" id="update-social-form">
            @csrf

            <input type="hidden" name="setting_id" value="{{ $setting?->id }}">

            <div class="card-header card-header-stretch">
                <div class="card-title">
                    Sosyal Medya Hesapları
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 fv-row mb-4">
                        <label class="fs-6 fw-semibold mb-2">Facebook</label>
                        <input type="url" name="facebook" class="form-control form-control-solid" placeholder="Facebook Adresi" value="{{ $social?->facebook }}" />
                    </div>

                    <div class="col-md-6 fv-row mb-4">
                        <label class="fs-6 fw-semibold mb-2">X ( Twitter )</label>
                        <input type="url" name="twitter" class="form-control form-control-solid" placeholder="X (Twitter) Adresi" value="{{ $social?->twitter }}" />
                    </div>

                    <div class="col-md-6 fv-row mb-4">
                        <label class="fs-6 fw-semibold mb-2">Instagram</label>
                        <input type="url" name="instagram" class="form-control form-control-solid" placeholder="Instagram Adresi" value="{{ $social?->instagram }}" />
                    </div>

                    <div class="col-md-6 fv-row mb-4">
                        <label class="fs-6 fw-semibold mb-2">LinkedIn</label>
                        <input type="url" name="linkedin" class="form-control form-control-solid" placeholder="LinkedIn Adresi" value="{{ $social?->linkedin }}" />
                    </div>

                    <div class="col-md-6 fv-row mb-4">
                        <label class="fs-6 fw-semibold mb-2">Pinterest</label>
                        <input type="url" name="pinterest" class="form-control form-control-solid" placeholder="Pinterest Adresi" value="{{ $social?->pinterest }}" />
                    </div>

                    <div class="col-md-6 fv-row mb-4">
                        <label class="fs-6 fw-semibold mb-2">Youtube</label>
                        <input type="url" name="youtube" class="form-control form-control-solid" placeholder="Youtube Adresi" value="{{ $social?->youtube }}" />
                    </div>

                    <div class="col-md-6 fv-row mb-4">
                        <label class="fs-6 fw-semibold mb-2">Whatsapp</label>
                        <input type="text" name="whatsapp" class="form-control form-control-solid" placeholder="Whatsapp Numarası" value="{{ $social?->whatsapp }}" />
                    </div>
                </div>
            </div>

            @can(PermissionEnum::SOCIAL_UPDATE)
                <div class="card-footer text-center">
                    <button type="submit" id="update-social-btn" class="btn btn-light-primary">
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

        {{-- Sosyal Medya Ayarları Son Güncelleme Görüntüleme Modal Başlangıç --}}
        <div class="modal fade" id="view-social-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h2 class="fw-bold">
                            Son Güncelleme
                        </h2>
                        <div id="view-social-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="view-social-modal-scroll" data-kt-scroll="false">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center flex-row-fluid flex-wrap">
                                    <div class="col-md-10 fv-row mb-4 mt-4">
                                        <div class="mb-2 border-dashed border-gray-300 rounded">
                                            <div class="p-4 text-center">
                                                <span class="text-gray-800 text-hover-primary fs-4 fw-bold updated-name">

                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-6 mb-4 mt-4 updated-email">

                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-7 mb-4 mt-4 updated-statu">

                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-7 mb-4 mt-4 updated-date">

                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer flex-center border-0">
                        <button type="button" id="view-social-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Sosyal Medya Ayarları Son Güncelleme Görüntüleme Modal Bitiş --}}
@endsection


@section('js')
<script src="{{ asset('backend/assets/js/modules/settings/social/view/read-view.js') }}"></script>

    @can(PermissionEnum::SOCIAL_UPDATE)
        <script src="{{ asset('backend/assets/js/modules/settings/social/update/update.js') }}"></script>
    @endcan
@endsection
