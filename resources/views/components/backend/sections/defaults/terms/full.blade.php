@use(App\Enums\Images\ImageTypeEnum)
@use(App\Enums\Images\ImageSizeEnum)

@php
    $outerLoop = $loop;
@endphp

@foreach ($section->allContent as $content)
    @continue($content?->language_id != $language->id)

    @if ($outerLoop->first)
        @if (!empty($disabled))
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
                                    @if (!empty($section?->createdBy?->id))
                                        <div class="col-md-6 fv-row mb-4">
                                            <div class="mb-2 border-dashed border-gray-300 rounded">
                                                <div class="p-4 text-center">
                                                    <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Ekleyen</div>
                                                    <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                        {{ $section?->createdBy?->name }}
                                                    </span>
                                                    <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                        {{ $section?->createdBy?->email }}
                                                    </span>
                                                    <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                        {!! $section?->createdBy?->getRoleHtml() !!}
                                                    </span>
                                                    <span class="text-muted fw-semibold d-block fs-7">
                                                        {{ $section->getCreatedAt() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (!empty($section?->updatedBy?->id))
                                        <div class="col-md-6 fv-row mb-4">
                                            <div class="mb-2 border-dashed border-gray-300 rounded">
                                                <div class="p-4 text-center">
                                                    <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Güncelleyen</div>
                                                    <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                        {{ $section?->updatedBy?->name }}
                                                    </span>
                                                    <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                        {{ $section?->updatedBy?->email }}
                                                    </span>
                                                    <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                        {!! $section?->updatedBy?->getRoleHtml() !!}
                                                    </span>
                                                    <span class="text-muted fw-semibold d-block fs-7">
                                                        {{ $section->getUpdatedAt() }}
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
            </div>

            <div class="separator separator-dashed mt-11 mb-11"></div>
        @endif

        <div class="fv-row mb-7">
            <label class="d-block fw-semibold fs-6">Görsel</label>

            @if (empty($disabled))
                <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $section->getImage() }})">
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
            @else
                <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $section->getImage() }})">
                    <div class="image-input-wrapper w-100px h-100px"></div>
                </div>
            @endif

            <div class="text-muted fs-7">
                <small class="text-danger">
                    Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 500x500
                </small>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-7">
                <label class="required fs-6 fw-semibold mb-2">Bölüm Başlık</label>
                <input type="text" name="title" value="{{ $section?->title }}" class="form-control form-control-solid" placeholder="Bölüm Başlık *" required @disabled(!empty($disabled)) />
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 mb-7">
            <label class="required fs-6 fw-semibold mb-2">Başlık ( {{ $language?->getCodeUppercase() }} )</label>
            <input type="text" name="{{ $language?->code }}[heading]" value="{{ $content?->heading }}" class="form-control form-control-solid" placeholder="Başlık *" required @disabled(!empty($disabled)) />
        </div>
        <div class="col-md-12 mb-7">
            <label class="required fs-6 fw-semibold mb-2">İçerik ( {{ $language?->getCodeUppercase() }} )</label>
            <textarea name="{{ $language?->code }}[description]" id="description_{{ $language?->id }}" class="form-control form-control-solid cst-description" rows="6" placeholder="İçerik *" @disabled(!empty($disabled))>{!! $content?->description !!}</textarea>
        </div>
    </div>

    @if ($outerLoop->first)
        <div class="row cst-mobil-checkbox">
            <div class="col-md-12 fv-row">
                <label class="required fs-6 fw-semibold mb-2">Sıralama</label>
            </div>
            <div class="col-md-4 fv-row mb-4">
                <input type="number" name="sorting" value="{{ $section?->sorting ?? '1' }}" class="form-control form-control-solid" id="sorting" placeholder="Sıralama" min="1" required @disabled(!empty($disabled)) />
            </div>
            <div class="col-md-2 fv-row mb-4 mt-2 px-7">
                <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                    <input type="checkbox" name="status" class="form-check-input" id="update-status" @checked($section?->isActiveStatus()) @disabled(!empty($disabled))>
                    <span class="form-check-label fs-6 fw-semibold mx-4" for="status">
                        Aktif
                    </span>
                </div>
            </div>
        </div>
    @endif
@endforeach


<script>
    @foreach ($languages as $al)
        if (CKEDITOR.instances['description_{{ $al->id }}']) {
            CKEDITOR.instances['description_{{ $al->id }}'].destroy();
        }
        CKEDITOR.replace('description_{{ $al->id }}', {
            height: 500,
        });
    @endforeach
</script>
