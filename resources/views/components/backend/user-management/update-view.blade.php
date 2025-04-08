@use(App\Enums\Images\ImageTypeEnum)
@use(App\Enums\Images\ImageSizeEnum)

<div class="card">
    <div class="card-body">
        <div class="accordion" id="kt_accordion_1">

            <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_2">
                    <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_2" aria-expanded="false" aria-controls="kt_accordion_1_body_2">
                        İşlemler
                    </button>
                </h2>

                <div id="kt_accordion_1_body_2" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_accordion_1">
                    <div class="accordion-body">
                        <div class="col-md-12 fv-row">
                            <div class="row cst-mobil-checkbox">
                                @if (!empty($user?->createdBy?->id))
                                    <div class="col-md-4 fv-row mb-4">
                                        <div class="mb-2 border-dashed border-gray-300 rounded">
                                            <div class="p-4 text-center">
                                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Ekleyen</div>
                                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                    {{ $user?->createdBy?->name }}
                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                    {{ $user?->createdBy?->email }}
                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                    {!! $user?->createdBy?->getRoleHtml() !!}
                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-7">
                                                    {{ $user?->getCreatedAt() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($user?->updatedBy?->id))
                                    <div class="col-md-4 fv-row mb-4">
                                        <div class="mb-2 border-dashed border-gray-300 rounded">
                                            <div class="p-4 text-center">
                                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Güncelleyen</div>
                                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                    {{ $user?->updatedBy?->name }}
                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                    {{ $user?->updatedBy?->email }}
                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                    {!! $user?->updatedBy?->getRoleHtml() !!}
                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-7">
                                                    {{ $user?->getUpdatedAt() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($user?->deletedBy?->id))
                                    <div class="col-md-4 fv-row mb-4">
                                        <div class="mb-2 border-dashed border-gray-300 rounded">
                                            <div class="p-4 text-center">
                                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Silen</div>
                                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                    {{ $user?->deletedBy?->name }}
                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                    {{ $user?->deletedBy?->email }}
                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                    {!! $user?->deletedBy?->getRoleHtml() !!}
                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-7">
                                                    {{ $user?->getDeletedAt() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (!empty($user?->bannedBy?->id))
                                    <div class="col-md-4 fv-row mb-4">
                                        <div class="mb-2 border-dashed border-gray-300 rounded">
                                            <div class="p-4 text-center">
                                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Banlayan</div>
                                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                    {{ $user?->bannedBy?->name }}
                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                    {{ $user?->bannedBy?->email }}
                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                    {!! $user?->bannedBy?->getRoleHtml() !!}
                                                </span>
                                                <span class="text-muted fw-semibold d-block fs-7">
                                                    {{ $user?->getBannedAt() }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (!empty($user?->deleted_description))
                <div class="accordion-item">
                    <h2 class="accordion-header" id="kt_accordion_1_header_4">
                        <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_4" aria-expanded="false" aria-controls="kt_accordion_1_body_4">
                            Silinme Nedeni ?
                        </button>
                    </h2>

                    <div id="kt_accordion_1_body_4" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_4" data-bs-parent="#kt_accordion_1">
                        <div class="accordion-body">
                            <div class="col-md-12 fv-row">
                                {{ $user?->deleted_description }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (!empty($user?->banned_description))
                <div class="accordion-item">
                    <h2 class="accordion-header" id="kt_accordion_1_header_5">
                        <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_5" aria-expanded="false" aria-controls="kt_accordion_1_body_5">
                            Banlama Nedeni ?
                        </button>
                    </h2>

                    <div id="kt_accordion_1_body_5" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_5" data-bs-parent="#kt_accordion_1">
                        <div class="accordion-body">
                            <div class="col-md-12 fv-row">
                                {{ $user?->banned_description }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <div class="separator separator-dashed mt-11 mb-11"></div>

        <div class="fv-row mb-7 text-center">

            <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $user?->getImage() }})">
                <div class="image-input-wrapper w-100px h-100px"></div>
            </div>

            <div class="text-muted fs-7">
                {!! $user?->getRoleHtml() !!}
            </div>
        </div>


        <div class="fv-row mb-7">
            <label class="required fs-5 fw-semibold mb-2">Kullanıcı Adı</label>
            <input type="text" name="name" id="update-name" class="form-control form-control-solid" placeholder="Kullanıcı Adı Girin *" value="{{ $user?->name }}" disabled />
        </div>

        <div class="row mb-7">
            <div class="col-md-6 fv-row mb-7">
                <label class="required fs-5 fw-semibold mb-2">E-Mail</label>
                <input type="text" name="email" id="update-email" class="form-control form-control-solid" placeholder="E-Mail Adresi Girin *" min="1" value="{{ $user?->email }}" disabled />
            </div>

            <div class="col-md-6 fv-row">
                <label class="fs-5 fw-semibold mb-2">Telefon Numarası</label>
                <input type="text" name="phone" id="update-phone" class="form-control form-control-solid" id="sorting" placeholder="Telefon Numarası Girin" value="{{ $user?->phone }}" minlength="10" maxlength="11" disabled />
            </div>
        </div>
    </div>
</div>
