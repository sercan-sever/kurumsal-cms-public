@use(App\Enums\Images\ImageTypeEnum)
@use(App\Enums\Images\ImageSizeEnum)

@if (empty($disabled))
    <form id="create-form" class="form" data-action="{{ route('admin.pages.section.create.dynamic.image') }}">
        @csrf
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

                            @if ($loop->first)
                                <div class="row cst-mobil-checkbox mb-7">
                                    <div class="col-md-6 fv-row mb-7 text-center">
                                        <label class="required d-block fw-semibold fs-6">Görsel</label>

                                        <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true">
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
                                                Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 500x500
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-6 fv-row mb-7 text-center">
                                        <label class="required d-block fw-semibold fs-6">Kapak Görseli</label>

                                        <div class="bgi-no-repeat bgi-position-center bgi-size-cover image-input image-input-empty image-input-outline image-input-placeholder m-5" data-kt-image-input="true">
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
                                                Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 1360x768
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-semibold mb-2">Bölüm Başlık</label>
                                    <input type="text" name="title" class="form-control form-control-solid" placeholder="Bölüm Başlık *" minlength="1" required />
                                </div>
                            @endif

                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">İçerik ( {{ $language?->getCodeUppercase() }} )</label>
                                <textarea name="{{ $language?->code }}[description]" id="create_image_description_{{ $language?->id }}" class="form-control form-control-solid cst-description ckeditor" rows="6" placeholder="İçerik *" @disabled(!empty($disabled))></textarea>
                            </div>

                            @if ($loop->first)
                                <div class="row cst-mobil-checkbox">
                                    <div class="col-md-12 fv-row">
                                        <label class="required fs-6 fw-semibold mb-2">Sıralama</label>
                                    </div>
                                    <div class="col-md-4 fv-row mb-4">
                                        <input type="number" name="sorting" value="{{ $sorting ?? '1' }}" class="form-control form-control-solid" id="sorting" placeholder="Sıralama" min="1" required />
                                    </div>
                                    <div class="col-md-2 fv-row mb-4 mt-2 px-7">
                                        <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                            <input type="checkbox" name="status" class="form-check-input" id="update-status" checked>
                                            <span class="form-check-label fs-6 fw-semibold mx-4" for="status">
                                                Aktif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
        CKEDITOR.replace('create_image_description_{{ $al->id }}', {
            extraPlugins: 'widget,widgetselection',

            filebrowserImageBrowseUrl: "{{ url('laravel-filemanager?type=Images') }}",
            filebrowserImageUploadUrl: "{{ url('laravel-filemanager/upload?type=Images&_token=') }}{{ csrf_token() }}",
            filebrowserBrowseUrl: "{{ url('laravel-filemanager?type=Files') }}",
            filebrowserUploadUrl: "{{ url('laravel-filemanager/upload?type=Files&_token=') }}{{ csrf_token() }}",

            height: 500,
            removePlugins: 'uploadimage',
        });
    @endforeach

    $('#page').selectpicker();
</script>
