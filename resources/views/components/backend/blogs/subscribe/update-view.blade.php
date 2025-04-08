@if (empty($disabled))
    <form id="update-form" class="form" data-action="{{ route('admin.blog.subscribe.update') }}">
        @csrf

        <input type="hidden" name="id" value="{{ $blogSubscribe->id }}">
@endif

<div class="card">
    <div class="card-body">

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
                                    @if (!empty($blogSubscribe?->updatedBy?->id))
                                        <div class="col-md-6 fv-row mb-4">
                                            <div class="mb-2 border-dashed border-gray-300 rounded">
                                                <div class="p-4 text-center">
                                                    <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Güncelleyen</div>
                                                    <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                        {{ $blogSubscribe?->updatedBy?->name }}
                                                    </span>
                                                    <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                        {{ $blogSubscribe?->updatedBy?->email }}
                                                    </span>
                                                    <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                        {!! $blogSubscribe?->updatedBy?->getRoleHtml() !!}
                                                    </span>
                                                    <span class="text-muted fw-semibold d-block fs-7">
                                                        {{ $blogSubscribe->getUpdatedAt() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if (!empty($blogSubscribe?->deletedBy?->id))
                                        <div class="col-md-6 fv-row mb-4">
                                            <div class="mb-2 border-dashed border-gray-300 rounded">
                                                <div class="p-4 text-center">
                                                    <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Silen</div>
                                                    <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                        {{ $blogSubscribe?->deletedBy?->name }}
                                                    </span>
                                                    <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                        {{ $blogSubscribe?->deletedBy?->email }}
                                                    </span>
                                                    <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                        {!! $blogSubscribe?->deletedBy?->getRoleHtml() !!}
                                                    </span>
                                                    <span class="text-muted fw-semibold d-block fs-7">
                                                        {{ $blogSubscribe->getDeletedAt() }}
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

                @if (!empty($blogSubscribe?->deleted_description))
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="kt_accordion_1_header_4">
                            <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_4" aria-expanded="false" aria-controls="kt_accordion_1_body_4">
                                Silinme Nedeni ?
                            </button>
                        </h2>
                        <div id="kt_accordion_1_body_4" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_4" data-bs-parent="#kt_accordion_1">
                            <div class="accordion-body">
                                <div class="col-md-12 fv-row">
                                    {{ $blogSubscribe?->deleted_description }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="separator separator-dashed mt-11 mb-11"></div>
        @endif

        <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2">Abone E-Mail</label>
            <input type="text" class="form-control form-control-solid" placeholder="Abone Email *" value="{{ $blogSubscribe?->email }}" disabled />
        </div>

        <div class="fv-row mb-7">
            <label class="required fs-6 fw-semibold mb-2">Abone Olunan Dil</label>
            <select name="language" class="form-control form-select-solid" id="update-language" data-live-search="true" data-placeholder="Abone Olunan Dil *" required @disabled(!empty($disabled))>
                @foreach ($languages as $language)
                    <option value="{{ $language->id }}" data-content="<span class='symbol symbol-20px'><img class='rounded-1 me-4' src='{{ $language?->getImage() }}'>{{ $language?->name }}</span>" @selected($language->id == $blogSubscribe?->language?->id)>{{ $language?->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-12 fv-row mt-4">
            <div class="row cst-mobil-checkbox">
                <div class="col-md-12">
                    <label class="fs-6 fw-semibold mb-2">IP Adresi</label>
                </div>
                <div class="col-md-8 fv-row mb-4">
                    <input type="text" class="form-control form-control-solid" placeholder="IP Adresi" value="{{ $blogSubscribe?->ip_address }}" disabled />
                </div>

                <div class="col-md-4 fv-row mb-4 mt-2 px-7">
                    <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                        <input type="checkbox" name="status" class="form-check-input" id="update-status" @checked($blogSubscribe?->isActiveStatus()) @disabled(!empty($disabled))>
                        <span class="form-check-label fs-6 fw-semibold mx-4" for="update-status">
                            Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2">Kayıt Tarihi</label>
            <input type="text" class="form-control form-control-solid" placeholder="Kayıt Tarihi" value="{{ $blogSubscribe?->getCreatedAt() }}" disabled />
        </div>
    </div>
</div>

@if (empty($disabled))
    </form>
@endif


<script>
    var optionFormat = function(item) {
        if (!item.id) {
            return item.text;
        }

        var span = document.createElement('span');
        var imgUrl = item.element.getAttribute('data-kt-select2-country');
        var template = '';

        template += '<img src="' + imgUrl + '" class="rounded-circle h-20px me-2" alt="image"/>';
        template += item.text;

        span.innerHTML = template;

        return $(span);
    }

    $('#update-language').selectpicker();
</script>
