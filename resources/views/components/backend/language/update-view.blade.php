<div class="card">
    <div class="card-body">
        <div class="col-md-12 fv-row">
            <div class="row cst-mobil-checkbox">
                @if (!empty($language?->createdBy?->id))
                    <div class="col-md-4 fv-row mb-4">
                        <div class="mb-2 border-dashed border-gray-300 rounded">
                            <div class="p-4 text-center">
                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Ekleyen</div>
                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                    {{ $language?->createdBy?->name }}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                    {{ $language?->createdBy?->email }}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                    {!! $language?->createdBy?->getRoleHtml() !!}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-7">
                                    {{ $language?->getCreatedAt() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
                @if (!empty($language?->updatedBy?->id))
                    <div class="col-md-4 fv-row mb-4">
                        <div class="mb-2 border-dashed border-gray-300 rounded">
                            <div class="p-4 text-center">
                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Güncelleyen</div>
                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                    {{ $language?->updatedBy?->name }}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                    {{ $language?->updatedBy?->email }}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                    {!! $language?->updatedBy?->getRoleHtml() !!}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-7">
                                    {{ $language?->getUpdatedAt() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
                @if (!empty($language?->deletedBy?->id))
                    <div class="col-md-4 fv-row mb-4">
                        <div class="mb-2 border-dashed border-gray-300 rounded">
                            <div class="p-4 text-center">
                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Silen</div>
                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                    {{ $language?->deletedBy?->name }}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                    {{ $language?->deletedBy?->email }}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                    {!! $language?->deletedBy?->getRoleHtml() !!}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-7">
                                    {{ $language?->getDeletedAt() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="accordion" id="kt_accordion_1">

            @if (!empty($language?->deleted_description))
                <div class="accordion-item">
                    <h2 class="accordion-header" id="kt_accordion_1_header_4">
                        <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_4" aria-expanded="false" aria-controls="kt_accordion_1_body_4">
                            Silinme Nedeni ?
                        </button>
                    </h2>

                    <div id="kt_accordion_1_body_4" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_4" data-bs-parent="#kt_accordion_1">
                        <div class="accordion-body">
                            <div class="col-md-12 fv-row">
                                {{ $language?->deleted_description }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
