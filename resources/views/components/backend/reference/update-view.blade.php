@use(App\Enums\Permissions\PermissionEnum)
@use(App\Enums\Images\ImageTypeEnum)
@use(App\Enums\Images\ImageSizeEnum)

@if (empty($disabled))
    <form id="update-form" class="form" data-action="{{ route('admin.reference.update') }}">
        @csrf

        <input type="hidden" name="id" value="{{ $reference->id }}">
@endif

<div class="card">
    <div class="card-header card-header-stretch">
        <div class="card-toolbar m-0">
            <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bold" role="tablist">
                @foreach ($languages as $language)
                    <li class="nav-item" role="presentation">
                        <a id="update-tab-{{ $language->id }}" class="nav-link justify-content-center text-active-gray-800 {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" role="tab" href="#update-body-{{ $language->id }}">
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
                <div id="update-body-{{ $language->id }}" class="card-body p-0 tab-pane fade show {{ $loop->first ? 'active' : '' }}" role="tabpanel" aria-labelledby="update-tab-{{ $language->id }}">
                    <div class="card">
                        <div class="card-body">
                            @php
                                $outerLoop = $loop;
                            @endphp

                            @foreach ($reference->allContent as $content)
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
                                                                @if (!empty($reference?->createdBy?->id))
                                                                    <div class="col-md-4 fv-row mb-4">
                                                                        <div class="mb-2 border-dashed border-gray-300 rounded">
                                                                            <div class="p-4 text-center">
                                                                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Ekleyen</div>
                                                                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                                                    {{ $reference?->createdBy?->name }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                                                    {{ $reference?->createdBy?->email }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                                                    {!! $reference?->createdBy?->getRoleHtml() !!}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7">
                                                                                    {{ $reference->getCreatedAt() }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if (!empty($reference?->updatedBy?->id))
                                                                    <div class="col-md-4 fv-row mb-4">
                                                                        <div class="mb-2 border-dashed border-gray-300 rounded">
                                                                            <div class="p-4 text-center">
                                                                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Güncelleyen</div>
                                                                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                                                    {{ $reference?->updatedBy?->name }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                                                    {{ $reference?->updatedBy?->email }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                                                    {!! $reference?->updatedBy?->getRoleHtml() !!}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7">
                                                                                    {{ $reference->getUpdatedAt() }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if (!empty($reference?->deletedBy?->id))
                                                                    <div class="col-md-4 fv-row mb-4">
                                                                        <div class="mb-2 border-dashed border-gray-300 rounded">
                                                                            <div class="p-4 text-center">
                                                                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Silen</div>
                                                                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                                                    {{ $reference?->deletedBy?->name }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                                                    {{ $reference?->deletedBy?->email }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                                                    {!! $reference?->deletedBy?->getRoleHtml() !!}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7">
                                                                                    {{ $reference->getDeletedAt() }}
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

                                            @if (!empty($reference?->deleted_description))
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="kt_accordion_1_header_4">
                                                        <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_4" aria-expanded="false" aria-controls="kt_accordion_1_body_4">
                                                            Silinme Nedeni ?
                                                        </button>
                                                    </h2>

                                                    <div id="kt_accordion_1_body_4" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_4" data-bs-parent="#kt_accordion_1">
                                                        <div class="accordion-body">
                                                            <div class="col-md-12 fv-row">
                                                                {{ $reference?->deleted_description }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="separator separator-dashed mt-11 mb-11"></div>
                                    @endif


                                    <div class="row cst-mobil-checkbox mb-7">
                                        <div class="col-md-6 fv-row mb-7 text-center">
                                            <label class="d-block fw-semibold fs-6">Görsel</label>

                                            @if (empty($disabled))
                                                <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $reference->getImage() }})">
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
                                                <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $reference->getImage() }})">
                                                    <div class="image-input-wrapper w-100px h-100px"></div>
                                                </div>
                                            @endif

                                            <div class="text-muted fs-7">
                                                <small class="text-danger">
                                                    Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 1920x1080
                                                </small>
                                            </div>
                                        </div>

                                        <div class="col-md-6 fv-row mb-7 text-center">
                                            <label class="d-block fw-semibold fs-6">Kapak Görsel</label>

                                            @if (empty($disabled))
                                                <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $reference->getOtherImage() }})">
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
                                            @else
                                                <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $reference->getOtherImage() }})">
                                                    <div class="image-input-wrapper w-100px h-100px"></div>
                                                </div>
                                            @endif

                                            <div class="text-muted fs-7">
                                                <small class="text-danger">
                                                    Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_05->value }} | W-H: 700x500
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="row mb-7">
                                    <div class="fv-row col-md-12">
                                        <label class="required fs-6 fw-semibold mb-2">Referans ( {{ $language?->getCodeUppercase() }} )</label>
                                        <input type="text" name="{{ $language?->code }}[title]" value="{{ $content?->title }}" class="form-control form-control-solid" placeholder="Referans *" required @disabled(!empty($disabled)) />
                                    </div>
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-semibold mb-2">Kısa Açıklama ( {{ $language?->getCodeUppercase() }} )</label>
                                    <textarea name="{{ $language?->code }}[short_description]" id="update_short_description_{{ $language?->id }}" class="form-control form-control-solid cst-description" rows="4" minlength="5" maxlength="2000" placeholder="Kısa Açıklama *" @disabled(!empty($disabled))>{{ $content?->short_description }}</textarea>
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-semibold mb-2">Açıklama ( {{ $language?->getCodeUppercase() }} )</label>
                                    <textarea name="{{ $language?->code }}[description]" id="update_editor_{{ $language?->id }}" class="form-control form-control-solid cst-description" rows="6" placeholder="Açıklama" @disabled(!empty($disabled))>{!! $content?->description !!}</textarea>
                                </div>

                                @if ($outerLoop->first)
                                    @php
                                        $selectedServiceIds = $reference?->services->pluck('id')->toArray() ?? [];
                                    @endphp

                                    <div class="row cst-mobil-checkbox">
                                        <div class="col-md-6 fv-row mb-7">
                                            <label class="required fs-6 fw-semibold mb-2">Müşteri</label>
                                            <select name="brand_id" class="form-control form-select-solid" id="update-brand" data-placeholder="Müşeri *" data-live-search="true" required @disabled(!empty($disabled))>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}" @selected($brand->id == $reference?->brand_id)>{{ $brand?->content?->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 fv-row mb-7">
                                            <label class="required fs-6 fw-semibold mb-2">Hizmetler</label>
                                            <select name="services[]" class="form-control form-select-solid" id="update-services" data-placeholder="Hizmetler *" data-live-search="true" multiple="multiple" required @disabled(!empty($disabled))>
                                                @foreach ($services as $service)
                                                    <option value="{{ $service->id }}" @selected(in_array($service->id, $selectedServiceIds))>{{ $service?->content?->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 fv-row mb-7">
                                            <label class="required fs-6 fw-semibold mb-2">Tamamlanma Tarihi</label>
                                            <input type="text" class="form-control form-control-solid cst-date" name="completion_date" placeholder="Tamamlanma Tarihi Seçin *" id="update-date" value="{{ $reference?->getCompletionDate() }}" data-locale="tr" required @disabled(!empty($disabled)) />
                                        </div>

                                        <div class="col-md-6 fv-row mb-7">
                                            <label for="update-multiple-reference-image" class="fs-6 fw-semibold mb-2">Referans Görselleri </label>
                                            <input class="form-control" name="reference_images[]" type="file" id="update-multiple-reference-image" accept="{{ ImageTypeEnum::MIME_ACCEPT->value }}" multiple @disabled(!empty($disabled))>

                                            <div class="text-muted fs-7 pt-2">
                                                <small class="text-danger">
                                                    Max: 6 | Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_3->value }} | W-H: 700x700
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 fv-row mb-7">
                                            <label class="required fs-6 fw-semibold mb-2">Sıralama</label>
                                            <input type="number" name="sorting" value="{{ $reference?->sorting ?? '1' }}" class="form-control form-control-solid" id="sorting" placeholder="Sıralama" min="1" required @disabled(!empty($disabled)) />
                                        </div>
                                        <div class="col-md-2 fv-row px-7 align-content-center">
                                            <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                                <input type="checkbox" name="status" class="form-check-input" id="update-status" @checked($reference?->isActiveStatus()) @disabled(!empty($disabled))>
                                                <span class="form-check-label fs-6 fw-semibold mx-4" for="status">
                                                    Aktif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="separator separator-dashed mt-7 mb-11"></div>

                                <h5 class="mb-7 d-flex align-items-center">
                                    <span class="bullet bg-danger w-15px me-3"></span> Meta Etiketleri ( {{ $language?->getCodeUppercase() }} )
                                </h5>

                                <div class="row">
                                    <div class="col-md-6 fv-row mb-4 cst-meta-keywords">
                                        <label class=" fs-6 fw-semibold mb-2">Meta Anaharat Kelimeler</label>
                                        <textarea name="{{ $language?->code }}[meta_keywords]" id="update_meta_keywords_{{ $language?->id }}" class="form-control form-control-solid" rows="4" placeholder="Meta Anaharat Kelimeler" minlength="20" maxlength="150" @disabled(!empty($disabled))>{{ $content?->meta_keywords }}</textarea>
                                    </div>
                                    <div class="col-md-6 fv-row mb-4 cst-meta-descriptions">
                                        <label class=" fs-6 fw-semibold mb-2">Meta Açıklama</label>
                                        <textarea name="{{ $language?->code }}[meta_descriptions]" id="update_meta_descriptions_{{ $language?->id }}" class="form-control form-control-solid" rows="4" placeholder="Meta Açıklama" minlength="50" maxlength="160" @disabled(!empty($disabled))>{{ $content?->meta_descriptions }}</textarea>
                                    </div>
                                </div>

                                @if ($reference->allImage->isNotEmpty() && $outerLoop->first)
                                    <div class="separator separator-dashed mt-11 mb-11"></div>

                                    <h5 class="mb-7 d-flex align-items-center">
                                        <span class="bullet bg-danger w-15px me-3"></span> Referans Görselleri
                                    </h5>

                                    <div class="scroll h-300px px-5">
                                        <div class="row cst-mobil-checkbox">
                                            @foreach ($reference->allImage as $image)
                                                <div class="col-md-2 fv-row mb-7 cst-reference-images" data-image="{{ $image->id }}">
                                                    <div class="image-input image-input-empty image-input-outline image-input-placeholder w-100px h-100px m-5" data-kt-image-input="true" style="background-image: url({{ $image->getImage() }})">

                                                        <div class="image-input-wrapper w-100px h-100px"></div>

                                                        @can(PermissionEnum::REFERENCE_DELETE)
                                                            @if (empty($disabled))
                                                                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow cst-remove-btn" data-reference="{{ $reference->id }}" data-image="{{ $image->id }}" data-sorting="{{ $image?->sorting ?? 0 }}">
                                                                    <i class="bi bi-x fs-2"></i>
                                                                </span>
                                                            @endif
                                                        @endcan
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


@if (empty($disabled))
    </form>
@endif


<script>
    @foreach ($languages as $al)
        CKEDITOR.replace('update_editor_{{ $al->id }}', {
            extraPlugins: 'widget,widgetselection',

            filebrowserImageBrowseUrl: "{{ url('laravel-filemanager?type=Images') }}",
            filebrowserImageUploadUrl: "{{ url('laravel-filemanager/upload?type=Images&_token=') }}{{ csrf_token() }}",
            filebrowserBrowseUrl: "{{ url('laravel-filemanager?type=Files') }}",
            filebrowserUploadUrl: "{{ url('laravel-filemanager/upload?type=Files&_token=') }}{{ csrf_token() }}",

            height: 500,
            removePlugins: 'uploadimage',
        });

        $('#update_short_description_{{ $al->id }}').maxlength({
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

        $('#update_meta_keywords_{{ $al->id }}').maxlength({
            alwaysShow: true,
            warningClass: "badge badge-danger",
            limitReachedClass: "badge badge-success"
        }).on('focus input', function() {
            let length = $(this).val().length;

            if (length >= 20) {
                $(".bootstrap-maxlength").removeClass("badge-danger").addClass("badge-success"); // Yeşil yap
            } else {
                $(".bootstrap-maxlength").removeClass("badge-success").addClass("badge-danger"); // Kırmızı yap
            }
        });

        $('#update_meta_descriptions_{{ $al->id }}').maxlength({
            alwaysShow: true,
            warningClass: "badge badge-danger",
            limitReachedClass: "badge badge-success"
        }).on('focus input', function() {
            let length = $(this).val().length;

            if (length >= 50) {
                $(".bootstrap-maxlength").removeClass("badge-danger").addClass("badge-success"); // Yeşil yap
            } else {
                $(".bootstrap-maxlength").removeClass("badge-success").addClass("badge-danger"); // Kırmızı yap
            }
        });
    @endforeach

    $("#update-multiple-reference-image").on("change", function() {
        let allowedTypes = {!! json_encode(ImageTypeEnum::getMimeType()) !!};

        let maxFiles = 6;
        let maxSize = {{ ImageSizeEnum::SIZE_3->value }} * 1024 * 1024;
        let files = this.files;

        if (files.length > maxFiles) {
            showSwal('warning', 'En fazla ' + maxFiles + ' dosya seçebilirsiniz!', 'center');
            $(this).val(""); // Seçimi temizle
            return;
        }

        for (let i = 0; i < files.length; i++) {
            if (!allowedTypes.includes(files[i].type)) {
                showSwal('warning', "Geçersiz dosya türü: " + files[i].name, 'center');
                $(this).val(""); // Seçimi temizle
                return;
            }

            if (files[i].size > maxSize) {
                showSwal('warning', files[i].name + " çok büyük! Maksimum " + maxSize + " MB olabilir.", 'center');
                $(this).val(""); // Seçimi temizle
                return;
            }
        }
    });

    $("#update-date").flatpickr({
        dateFormat: "Y-m-d",
        disableMobile: true, // Mobilde varsayılan date picker yerine Flatpickr'ı kullan
    });

    $('#update-services').selectpicker();
    $('#update-brand').selectpicker();
</script>
