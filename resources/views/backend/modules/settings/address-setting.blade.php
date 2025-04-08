@extends('backend.layouts.app')

@use(App\Enums\Permissions\PermissionEnum)

@section('toolbar')
    @include('backend.includes.toolbar', [
        'viewButton' => true,
        'viewButtonId' => $address?->id,
        'title' => 'Adres Ayarları',
        'breadcrumbs' => [
            [
                'name' => 'Tüm Ayarlar',
                'url' => route('admin.setting'),
            ],
            [
                'name' => 'Adres Ayarları',
            ],
        ],
    ])
@endsection


@section('css')
@endsection


@section('content')
    <div class="card">
        <form class="form" data-action="{{ route('admin.setting.address.update') }}" id="update-address-form">
            @csrf

            <input type="hidden" name="setting_id" value="{{ $setting?->id }}">

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

            <div class="card-body">
                <div class="tab-content">
                    @foreach ($languages as $language)
                        <div id="body-{{ $language->id }}" class="card-body p-0 tab-pane fade show {{ $loop->first ? 'active' : '' }}" role="tabpanel" aria-labelledby="tab-{{ $language->id }}">
                            <div class="card">

                                <div class="card-body">

                                    @if (!empty($address))
                                        <x-backend.settings.address.full :language="$language" :address="$address" />
                                    @else
                                        <x-backend.settings.address.empty :language="$language" />
                                    @endif

                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @can(PermissionEnum::ADDRESS_UPDATE)
                <div class="card-footer text-center">
                    <button type="submit" id="update-address-btn" class="btn btn-light-primary">
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

    {{-- Adres Ayarları Son Güncelleme Görüntüleme Modal Başlangıç --}}
    <div class="modal fade" id="view-address-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h2 class="fw-bold">
                        Son Güncelleme
                    </h2>
                    <div id="view-address-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="fa-regular fa-circle-xmark fs-2"></i>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <div class="d-flex flex-column" id="view-address-modal-scroll" data-kt-scroll="false">
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
                    <button type="button" id="view-address-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                        Kapat
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- Adres Ayarları Son Güncelleme Görüntüleme Modal Bitiş --}}
@endsection


@section('js')
    <script src="{{ asset('backend/assets/js/modules/settings/address/view/read-view.js') }}"></script>

    @can(PermissionEnum::ADDRESS_UPDATE)
        <script src="{{ asset('backend/assets/js/modules/settings/address/update/update.js') }}"></script>
    @endcan
@endsection
