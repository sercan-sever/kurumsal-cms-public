<div class="card">
    <div class="card-body">
        <div class="col-md-12 fv-row">
            <div class="row cst-mobil-checkbox">
                @if (!empty($translationContent?->createdBy?->id))
                    <div class="col-md-6 fv-row mb-4">
                        <div class="mb-2 border-dashed border-gray-300 rounded">
                            <div class="p-4 text-center">
                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Ekleyen</div>
                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                    {{ $translationContent?->createdBy?->name }}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                    {{ $translationContent?->createdBy?->email }}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                    {!! $translationContent?->createdBy?->getRoleHtml() !!}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-7">
                                    {{ $translationContent?->getCreatedAt() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
                @if (!empty($translationContent?->updatedBy?->id))
                    <div class="col-md-6 fv-row mb-4">
                        <div class="mb-2 border-dashed border-gray-300 rounded">
                            <div class="p-4 text-center">
                                <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Güncelleyen</div>
                                <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                    {{ $translationContent?->updatedBy?->name }}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                    {{ $translationContent?->updatedBy?->email }}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                    {!! $translationContent?->updatedBy?->getRoleHtml() !!}
                                </span>
                                <span class="text-muted fw-semibold d-block fs-7">
                                    {{ $translationContent?->getUpdatedAt() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
