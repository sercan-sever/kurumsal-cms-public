@if (empty($disabled))
    <form id="update-form" class="form" data-action="{{ route('admin.business.processes.update') }}">
        @csrf

        <input type="hidden" name="id" value="{{ $businessProcesses->id }}">
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

                            @foreach ($businessProcesses->allContent as $content)
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
                                                                @if (!empty($businessProcesses?->createdBy?->id))
                                                                    <div class="col-md-4 fv-row mb-4">
                                                                        <div class="mb-2 border-dashed border-gray-300 rounded">
                                                                            <div class="p-4 text-center">
                                                                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Ekleyen</div>
                                                                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                                                    {{ $businessProcesses?->createdBy?->name }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                                                    {{ $businessProcesses?->createdBy?->email }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                                                    {!! $businessProcesses?->createdBy?->getRoleHtml() !!}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7">
                                                                                    {{ $businessProcesses->getCreatedAt() }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if (!empty($businessProcesses?->updatedBy?->id))
                                                                    <div class="col-md-4 fv-row mb-4">
                                                                        <div class="mb-2 border-dashed border-gray-300 rounded">
                                                                            <div class="p-4 text-center">
                                                                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Güncelleyen</div>
                                                                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                                                    {{ $businessProcesses?->updatedBy?->name }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                                                    {{ $businessProcesses?->updatedBy?->email }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                                                    {!! $businessProcesses?->updatedBy?->getRoleHtml() !!}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7">
                                                                                    {{ $businessProcesses->getUpdatedAt() }}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                @if (!empty($businessProcesses?->deletedBy?->id))
                                                                    <div class="col-md-4 fv-row mb-4">
                                                                        <div class="mb-2 border-dashed border-gray-300 rounded">
                                                                            <div class="p-4 text-center">
                                                                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Silen</div>
                                                                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                                                    {{ $businessProcesses?->deletedBy?->name }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                                                    {{ $businessProcesses?->deletedBy?->email }}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                                                    {!! $businessProcesses?->deletedBy?->getRoleHtml() !!}
                                                                                </span>
                                                                                <span class="text-muted fw-semibold d-block fs-7">
                                                                                    {{ $businessProcesses->getDeletedAt() }}
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

                                            @if (!empty($businessProcesses?->deleted_description))
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="kt_accordion_1_header_4">
                                                        <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_4" aria-expanded="false" aria-controls="kt_accordion_1_body_4">
                                                            Silinme Nedeni ?
                                                        </button>
                                                    </h2>

                                                    <div id="kt_accordion_1_body_4" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_4" data-bs-parent="#kt_accordion_1">
                                                        <div class="accordion-body">
                                                            <div class="col-md-12 fv-row">
                                                                {{ $businessProcesses?->deleted_description }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="separator separator-dashed mt-11 mb-11"></div>
                                    @endif
                                @endif

                                <div class="row mb-7">
                                    <div class="fv-row col-md-6">
                                        <label class="required fs-6 fw-semibold mb-2">Üst Başlık ( {{ $language?->getCodeUppercase() }} )</label>
                                        <input type="text" name="{{ $language?->code }}[header]" value="{{ $content?->header }}" class="form-control form-control-solid" placeholder="Üst Başlık *" required @disabled(!empty($disabled)) />
                                    </div>
                                    <div class="fv-row col-md-6">
                                        <label class="required fs-6 fw-semibold mb-2">Başlık ( {{ $language?->getCodeUppercase() }} )</label>
                                        <input type="text" name="{{ $language?->code }}[title]" value="{{ $content?->title }}" class="form-control form-control-solid" placeholder="Başlık *" required @disabled(!empty($disabled)) />
                                    </div>
                                </div>

                                <div class="fv-row mb-7">
                                    <label class="required fs-6 fw-semibold mb-2">Açıklama ( {{ $language?->getCodeUppercase() }} )</label>
                                    <textarea name="{{ $language?->code }}[description]" id="update_description_{{ $language?->id }}" class="form-control form-control-solid cst-description" rows="6" placeholder="Açıklama *" minlength="5" maxlength="2000" required @disabled(!empty($disabled))>{!! $content?->description !!}</textarea>
                                </div>

                                @if ($outerLoop->first)
                                    <div class="row cst-mobil-checkbox">
                                        <div class="col-md-12">
                                            <label class="required fs-6 fw-semibold mb-2">Sıralama</label>
                                        </div>
                                        <div class="col-md-4 fv-row mb-4">
                                            <input type="number" name="sorting" value="{{ $businessProcesses?->sorting }}" class="form-control form-control-solid" id="update-sorting" placeholder="Sıralama" min="1" required @disabled(!empty($disabled)) />
                                        </div>

                                        <div class="col-md-4 fv-row mb-4 mt-2 px-7">
                                            <div class="form-check form-switch form-check-custom form-check-solid cst-status">
                                                <input type="checkbox" name="status" class="form-check-input" id="update-status" @checked($businessProcesses?->isActiveStatus()) @disabled(!empty($disabled))>
                                                <span class="form-check-label fs-6 fw-semibold mx-4" for="update-status">
                                                    Aktif
                                                </span>
                                            </div>
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
        $('#update_description_{{ $al?->id }}').maxlength({
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
    @endforeach
</script>
