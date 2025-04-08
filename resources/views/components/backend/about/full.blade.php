@use(App\Enums\Images\ImageTypeEnum)
@use(App\Enums\Images\ImageSizeEnum)

@php
    $outerLoop = $loop;
@endphp

@foreach ($about->allContent as $content)
    @continue($content?->language_id != $language->id)

    @if ($outerLoop->first)
        <div class="row cst-mobil-checkbox mb-7 cst-image">
            <div class="col-md-6 fv-row mb-7 text-center">
                <label class="d-block fw-semibold fs-6">Görsel</label>

                <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $about?->getImage() }})">
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
                        Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 700x500
                    </small>
                </div>
            </div>

            <div class="col-md-6 fv-row mb-7 text-center cst-other-image">
                <label class="d-block fw-semibold fs-6">Biz Kimiz? Görseli</label>

                <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $about?->getOtherImage() }})">
                    <div class="image-input-wrapper w-100px h-100px"></div>

                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                        <i class="bi bi-pencil-fill fs-7"></i>
                        <input type="file" name="other_image" accept="{{ ImageTypeEnum::MIME_ACCEPT->value }}" />
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
                        Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_05->value }} | W-H: 700x500
                    </small>
                </div>
            </div>
        </div>
    @endif

    <div class="fv-row mb-7">
        <label class="required fs-6 fw-semibold mb-2">Başlık ( {{ $language?->getCodeUppercase() }} )</label>
        <input type="text" name="{{ $language?->code }}[title]" value="{{ $content?->title }}" class="form-control form-control-solid" placeholder="Başlık *" required />
    </div>

    <div class="fv-row mb-7">
        <label class="required fs-6 fw-semibold mb-2">Biz Kimiz ? ( {{ $language?->getCodeUppercase() }} )</label>
        <textarea name="{{ $language?->code }}[short_description]" id="short_description_{{ $language?->id }}" class="form-control form-control-solid cst-description" rows="6" minlength="5" maxlength="4000" placeholder="Kısa Açıklama *">{{ $content?->short_description }}</textarea>
    </div>

    <div class="fv-row mb-7">
        <label class="required fs-6 fw-semibold mb-2">Hakkımızda ( {{ $language?->getCodeUppercase() }} )</label>
        <textarea name="{{ $language?->code }}[description]" id="editor_{{ $language?->id }}" class="form-control form-control-solid cst-description" rows="6" placeholder="Açıklama">{!! $content?->description !!}</textarea>
    </div>

    <div class="row">
        <div class="col-md-6 fv-row mb-4 cst-meta-keywords">
            <label class="required fs-6 fw-semibold mb-2">Misyon ( {{ $language?->getCodeUppercase() }} )</label>
            <textarea name="{{ $language?->code }}[mission]" id="mission_{{ $language?->id }}" class="form-control form-control-solid" rows="4" placeholder="Misyon *" required>{!! $content?->mission !!}</textarea>
        </div>
        <div class="col-md-6 fv-row mb-4 cst-meta-descriptions">
            <label class="required fs-6 fw-semibold mb-2">Vizyon ( {{ $language?->getCodeUppercase() }} )</label>
            <textarea name="{{ $language?->code }}[vision]" id="vision_{{ $language?->id }}" class="form-control form-control-solid" rows="4" placeholder="Vizyon *" required>{!! $content?->vision !!}</textarea>
        </div>
    </div>
@endforeach
