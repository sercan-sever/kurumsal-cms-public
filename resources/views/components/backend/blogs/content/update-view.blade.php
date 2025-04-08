@use(App\Enums\Images\ImageTypeEnum)
@use(App\Enums\Images\ImageSizeEnum)

@if (empty($disabled))
    <form id="update-form" class="form" data-action="{{ route('admin.blog.update') }}">
        @csrf

        <input type="hidden" name="id" value="{{ $blog->id }}">
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

                            @foreach ($blog->allContent as $content)
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
                                                                @if (!empty($blog?->createdBy?->id))
                                                                    <div class="col-md-4 fv-row mb-4">
                                                                        <div class="mb-2 border-dashed border-gray-300 rounded">
                                                                            <div class="p-4 text-center">
                                                                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Ekleyen</div>
                                                                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                                                    {{ $blog?->createdBy?->name }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                                                    {{ $blog?->createdBy?->email }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                                                    {!! $blog?->createdBy?->getRoleHtml() !!}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7">
                                                                                    {{ $blog->getCreatedAt() }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if (!empty($blog?->updatedBy?->id))
                                                                    <div class="col-md-4 fv-row mb-4">
                                                                        <div class="mb-2 border-dashed border-gray-300 rounded">
                                                                            <div class="p-4 text-center">
                                                                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Güncelleyen</div>
                                                                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                                                    {{ $blog?->updatedBy?->name }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                                                    {{ $blog?->updatedBy?->email }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                                                    {!! $blog?->updatedBy?->getRoleHtml() !!}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7">
                                                                                    {{ $blog->getUpdatedAt() }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if (!empty($blog?->deletedBy?->id))
                                                                    <div class="col-md-4 fv-row mb-4">
                                                                        <div class="mb-2 border-dashed border-gray-300 rounded">
                                                                            <div class="p-4 text-center">
                                                                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Silen</div>
                                                                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                                                    {{ $blog?->deletedBy?->name }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                                                    {{ $blog?->deletedBy?->email }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                                                    {!! $blog?->deletedBy?->getRoleHtml() !!}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7">
                                                                                    {{ $blog->getDeletedAt() }}
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

                                            @if (!empty($blog?->deleted_description))
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="kt_accordion_1_header_4">
                                                        <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_4" aria-expanded="false" aria-controls="kt_accordion_1_body_4">
                                                            Silinme Nedeni ?
                                                        </button>
                                                    </h2>

                                                    <div id="kt_accordion_1_body_4" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_4" data-bs-parent="#kt_accordion_1">
                                                        <div class="accordion-body">
                                                            <div class="col-md-12 fv-row">
                                                                {{ $blog?->deleted_description }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="separator separator-dashed mt-11 mb-11"></div>
                                    @endif

                                    @if (empty($disabled))
                                        <div class="fv-row mb-7">
                                            <label class="d-block fw-semibold fs-6">Görsel</label>

                                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $blog->getImage() }})">
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
                                                    Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 1920x1080
                                                </small>
                                            </div>
                                        </div>
                                    @else
                                        <div class="fv-row mb-7">
                                            <label class="d-block fw-semibold fs-6">Görsel</label>

                                            <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true" style="background-image: url({{ $blog->getImage() }})">
                                                <div class="image-input-wrapper w-100px h-100px"></div>
                                            </div>

                                            <div class="text-muted fs-7">
                                                <small class="text-danger">
                                                    Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 1920x1080
                                                </small>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-semibold mb-2">Başlık ( {{ $language?->getCodeUppercase() }} )</label>
                                    <input type="text" name="{{ $language?->code }}[title]" value="{{ $content?->title }}" class="form-control form-control-solid" placeholder="Başlık *" required @disabled(!empty($disabled)) />
                                </div>


                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-semibold mb-2">Açıklama ( {{ $language?->getCodeUppercase() }} )</label>
                                    <textarea name="{{ $language?->code }}[description]" id="update_editor_{{ $language?->id }}" class="form-control form-control-solid cst-description" rows="2" placeholder="Açıklama" @disabled(!empty($disabled))>{!! $content?->description !!}</textarea>
                                </div>

                                @if ($outerLoop->first)
                                    @php
                                        $selectedCategoryIds = $blog?->categories->pluck('id')->toArray() ?? [];
                                        $selectedTagIds = $blog?->tags->pluck('id')->toArray() ?? [];
                                    @endphp

                                    <div class="row">
                                        <div class="col-md-6 fv-row mb-4">
                                            <label class="required fs-6 fw-semibold mb-2">Kategoriler</label>
                                            <select name="categories[]" class="form-control form-select-solid" id="update-categories" data-placeholder="Kategoriler *" data-live-search="true" multiple="multiple" data-max-options="3" required @disabled(!empty($disabled))>
                                                @foreach ($blogCategories as $category)
                                                    <option value="{{ $category->id }}" @selected(in_array($category->id, $selectedCategoryIds))>{{ $category?->content?->title }}</option>
                                                @endforeach
                                            </select>

                                            <div class="text-muted fs-7 pt-1 px-2">
                                                <small class="text-danger">
                                                    Min: 1 | Max: 3
                                                </small>
                                            </div>
                                        </div>

                                        <div class="col-md-6 fv-row mb-4">
                                            <label class="required fs-6 fw-semibold mb-2">Etiketler</label>
                                            <select name="tags[]" class="form-control form-select-solid" id="update-tags" data-placeholder="Etiketler *" data-live-search="true" multiple="multiple" data-max-options="4" required @disabled(!empty($disabled))>
                                                @foreach ($blogTags as $tag)
                                                    <option value="{{ $tag->id }}" @selected(in_array($tag->id, $selectedTagIds))>{{ $tag?->content?->title }}</option>
                                                @endforeach
                                            </select>

                                            <div class="text-muted fs-7 pt-1 px-2">
                                                <small class="text-danger">
                                                    Min: 1 | Max: 4
                                                </small>
                                            </div>
                                        </div>

                                        <div class="col-md-6 fv-row mb-4 cst-publish">
                                            <label class="required fs-6 fw-semibold mb-2">Paylaşım Tarihi</label>
                                            <input type="text" class="form-control form-control-solid cst-date" name="published_at" placeholder="Paylaşım Tarihi ve Saati Seçiniz *" id="update-date" value="{{ $blog->getPublishedAt() }}" data-locale="tr" data-min-date="{{ now()->format('Y-m-d H:i') }}" data-max-date="{{ now()->addDays(7)->format('Y-m-d H:i') }}" @disabled(!empty($disabled)) />
                                        </div>

                                        <div class="col-md-6 fv-row mb-4">
                                            <label class="required fs-6 fw-semibold mb-2">Sıralama</label>
                                            <input type="number" name="sorting" value="{{ $blog?->sorting }}" class="form-control form-control-solid" id="sorting" placeholder="Sıralama" min="1" required @disabled(!empty($disabled)) />
                                        </div>

                                        <div class="col-md-6 fv-row mt-4">
                                            <div class="row cst-mobil-checkbox justify-content-start">
                                                <div class="col-md-4 fv-row mb-4">
                                                    <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                                        <input type="checkbox" name="status" class="form-check-input" id="status" @checked($blog?->isActiveStatus()) @disabled(!empty($disabled))>
                                                        <span class="form-check-label fs-6 fw-semibold mx-4" for="status">
                                                            Aktif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 fv-row mb-4">
                                                    <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                                        <input type="checkbox" name="comment_status" class="form-check-input" id="comment-status" @checked($blog?->isActiveCommentStatus()) @disabled(!empty($disabled))>
                                                        <span class="form-check-label fs-6 fw-semibold mx-4" for="comment-status">
                                                            Yorum
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif


                                <div class="separator separator-dashed mt-7 mb-11"></div>

                                <h5 class="mb-7 d-flex align-items-center">
                                    <span class="bullet bg-danger w-15px me-3"></span> Meta Etiketleri ( {{ $language?->getCodeUppercase() }} )
                                </h5>

                                <div class="row">
                                    <div class="col-md-6 fv-row mb-4">
                                        <label class=" fs-6 fw-semibold mb-2">Meta Anaharat Kelimeler</label>
                                        <textarea name="{{ $language?->code }}[meta_keywords]" id="update_meta_keywords_{{ $language->id }}" class="form-control form-control-solid" rows="4" minlength="20" maxlength="150" placeholder="Meta Anaharat Kelimeler" @disabled(!empty($disabled))>{{ $content?->meta_keywords }}</textarea>
                                    </div>
                                    <div class="col-md-6 fv-row mb-4">
                                        <label class=" fs-6 fw-semibold mb-2">Meta Açıklama</label>
                                        <textarea name="{{ $language?->code }}[meta_descriptions]" id="update_meta_descriptions_{{ $language->id }}" class="form-control form-control-solid" rows="4" minlength="50" maxlength="160" placeholder="Meta Açıklama" @disabled(!empty($disabled))>{{ $content?->meta_descriptions }}</textarea>
                                    </div>
                                </div>
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

    $('#update-categories').selectpicker();
    $('#update-tags').selectpicker();


    $(document).ready(function() {
        var publishDate = "{{ $blog->getPublishedAt() }}";
        // var publishDate = $("#update-date").val();

        @if (empty($disabled))
            var picker = $("#update-date").flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                disableMobile: true,
                allowInput: false,
                time_24hr: true,
                minuteIncrement: 1,
                locale: "tr",
                defaultDate: publishDate,
                minDate: $("#update-date").data("min-date"),
                maxDate: $("#update-date").data("max-date"),
                appendTo: document.querySelector("#update-blog-modal .modal-body .cst-publish"),
                static: true,
                onChange: function(selectedDates, dateStr, instance) {
                    if (dateStr) {
                        if (new Date(dateStr) < new Date($("#update-date").data("min-date"))) {
                            $("#update-date").val(publishDate);
                        }
                    } else {
                        $("#update-date").val(publishDate);
                    }
                },
            });
        @endif
    });
</script>
