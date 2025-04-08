@use(App\Enums\Pages\Menu\PageMenuEnum)
@use(App\Enums\Pages\Page\SubPageDesignEnum)

@use(App\Enums\Images\ImageTypeEnum)
@use(App\Enums\Images\ImageSizeEnum)

<div class="card">
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

    <div class="card-body p-0">
        <div class="tab-content">
            @foreach ($languages as $language)
                <div id="body-{{ $language->id }}" class="card-body p-0 tab-pane fade show {{ $loop->first ? 'active' : '' }}" role="tabpanel" aria-labelledby="tab-{{ $language->id }}">
                    <div class="card">
                        <div class="card-body">

                            @if ($loop->first)
                                <div class="fv-row mb-7">
                                    <label class="d-block fw-semibold fs-6">Sayfa Yolu Görseli</label>

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
                                            Mime: {{ ImageTypeEnum::MIME_ACCEPT->value }} | KB: {{ ImageSizeEnum::SIZE_1->value }} | W-H: 1920x450
                                        </small>
                                    </div>
                                </div>
                            @endif

                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Başlık ( {{ $language?->getCodeUppercase() }} )</label>
                                <input type="text" name="{{ $language?->code }}[title]" class="form-control form-control-solid" placeholder="Başlık *" />
                            </div>

                            @if ($loop->first)
                                <div class="row cst-mobil-checkbox">
                                    <div class="col-md-6 mb-7">
                                        <label class="fs-6 fw-semibold mb-2">Üst Sayfa</label>
                                        <select name="top_page" class="form-control form-select-solid" id="top-page" data-live-search="true">
                                            <option value="">- Boş -</option>
                                            @foreach ($topMenus as $menu)
                                                <option value="{{ $menu?->id }}">{{ $menu?->content?->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Menü Gösterimi</label>
                                        <select name="menu" class="form-control form-select-solid" id="menu" data-live-search="true" required>
                                            @foreach (PageMenuEnum::getValues() as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Alt Sayfa Detay Dizayn</label>
                                        <select name="design" class="form-control form-select-solid" id="design" data-live-search="true" required>
                                            @foreach (SubPageDesignEnum::getValues() as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-7">
                                        <label class="required fs-6 fw-semibold mb-2">Sıralama</label>
                                        <input type="number" name="sorting" value="{{ $maxSorting ?? '1' }}" class="form-control form-control-solid" id="sorting" placeholder="Sıralama" min="1" required />
                                    </div>
                                    <div class="col-md-2 mb-7 mt-2 px-7">
                                        <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                            <input type="checkbox" name="status" class="form-check-input" id="status" checked>
                                            <span class="form-check-label fs-6 fw-semibold mx-4" for="status">
                                                Aktif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-7 mt-2 px-7">
                                        <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                            <input type="checkbox" name="breadcrumb" class="form-check-input" id="breadcrumb" checked>
                                            <span class="form-check-label fs-6 fw-semibold mx-4" for="breadcrumb">
                                                Sayfa Yolu
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="separator separator-dashed mt-7 mb-12"></div>

                            <h5 class="mb-7 d-flex align-items-center">
                                <span class="bullet bg-danger w-15px me-3"></span> Meta Etiketleri ( {{ $language?->getCodeUppercase() }} )
                            </h5>

                            <div class="row">
                                <div class="col-md-6 fv-row mb-4 cst-meta-keywords">
                                    <label class=" fs-6 fw-semibold mb-2">Meta Anaharat Kelimeler</label>
                                    <textarea name="{{ $language?->code }}[meta_keywords]" id="meta_keywords_{{ $language?->id }}" class="form-control form-control-solid" rows="4" placeholder="Meta Anaharat Kelimeler" minlength="20" maxlength="150"></textarea>
                                </div>
                                <div class="col-md-6 fv-row mb-4 cst-meta-descriptions">
                                    <label class=" fs-6 fw-semibold mb-2">Meta Açıklama</label>
                                    <textarea name="{{ $language?->code }}[meta_descriptions]" id="meta_descriptions_{{ $language?->id }}" class="form-control form-control-solid" rows="4" placeholder="Meta Açıklama" minlength="50" maxlength="160"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<script>
    @foreach ($languages as $al)
        $('#meta_keywords_{{ $al->id }}').maxlength({
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

        $('#meta_descriptions_{{ $al->id }}').maxlength({
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

    // $.fn.selectpicker.Constructor.BootstrapVersion = '5';

    $('#top-page').selectpicker();
    $('#menu').selectpicker();
    $('#design').selectpicker();
</script>
