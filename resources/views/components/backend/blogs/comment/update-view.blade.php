@if (!empty($update))
    <form id="update-form" class="form" data-action="{{ route('admin.blog.comment.update') }}">
        @csrf

        <input type="hidden" name="id" value="{{ $blogComment->id }}">
@endif

<div class="card">
    <div class="card-body">

        @if (empty($update))
            <div class="accordion" id="kt_accordion_1">

                <div class="accordion-item">
                    <h2 class="accordion-header" id="kt_accordion_1_header_3">
                        <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_3" aria-expanded="false" aria-controls="kt_accordion_1_body_3">
                            Durumlar
                        </button>
                    </h2>
                    <div id="kt_accordion_1_body_3" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_3" data-bs-parent="#kt_accordion_1">
                        <div class="accordion-body">
                            <div class="col-md-12 fv-row">
                                <div class="row cst-mobil-checkbox">
                                    <div class="col-md-4 fv-row mb-4">
                                        <div class="d-flex flex-stack mb-2">
                                            <div class="d-flex align-items-center justify-content-center flex-row-fluid flex-wrap">
                                                <div class="me-4 text-center">
                                                    <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Blog Durum</div>
                                                    <span class="text-muted fw-semibold d-block fs-7">
                                                        {!! $blogComment?->blog?->getStatusBadgeHtml() !!}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 fv-row mb-4">
                                        <div class="d-flex flex-stack mb-2">
                                            <div class="d-flex align-items-center justify-content-center flex-row-fluid flex-wrap">
                                                <div class="me-4 text-center">
                                                    <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Blog Yorum Durum</div>
                                                    <span class="text-muted fw-semibold d-block fs-7">
                                                        {!! $blogComment?->blog?->getCommentStatusBadgeHtml() !!}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if (empty($confirm))
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
                                        @if (!empty($blogComment?->createdBy?->id))
                                            <div class="col-md-4 fv-row mb-4">
                                                <div class="mb-2 border-dashed border-gray-300 rounded">
                                                    <div class="p-4 text-center">
                                                        <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Ekleyen</div>
                                                        <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                            {{ $blogComment?->createdBy?->name }}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                            {{ $blogComment?->createdBy?->email }}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                            {!! $blogComment?->createdBy?->getRoleHtml() !!}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-7">
                                                            {{ $blogComment->getCreatedAt() }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if (!empty($blogComment?->confirmedBy?->id))
                                            <div class="col-md-4 fv-row mb-4">
                                                <div class="mb-2 border-dashed border-gray-300 rounded">
                                                    <div class="p-4 text-center">
                                                        <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Onaylayan</div>
                                                        <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                            {{ $blogComment?->confirmedBy?->name }}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                            {{ $blogComment?->confirmedBy?->email }}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                            {!! $blogComment?->confirmedBy?->getRoleHtml() !!}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-7">
                                                            {{ $blogComment->getUpdatedAt() }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if (!empty($blogComment?->replyCommentBy?->id))
                                            <div class="col-md-4 fv-row mb-4">
                                                <div class="mb-2 border-dashed border-gray-300 rounded">
                                                    <div class="p-4 text-center">
                                                        <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Cevaplayan</div>
                                                        <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                            {{ $blogComment?->replyCommentBy?->name }}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                            {{ $blogComment?->replyCommentBy?->email }}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                            {!! $blogComment?->replyCommentBy?->getRoleHtml() !!}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-7">
                                                            {{ $blogComment->getUpdatedAt() }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if (!empty($blogComment?->updatedBy?->id))
                                            <div class="col-md-4 fv-row mb-4">
                                                <div class="mb-2 border-dashed border-gray-300 rounded">
                                                    <div class="p-4 text-center">
                                                        <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Güncelleyen</div>
                                                        <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                            {{ $blogComment?->updatedBy?->name }}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                            {{ $blogComment?->updatedBy?->email }}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                            {!! $blogComment?->updatedBy?->getRoleHtml() !!}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-7">
                                                            {{ $blogComment->getUpdatedAt() }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if (!empty($blogComment?->deletedBy?->id))
                                            <div class="col-md-4 fv-row mb-4">
                                                <div class="mb-2 border-dashed border-gray-300 rounded">
                                                    <div class="p-4 text-center">
                                                        <div class="text-gray-800 text-hover-primary fs-6 fw-bold mb-2">Silen</div>
                                                        <span class="text-gray-700 fw-semibold fs-6 mb-2">
                                                            {{ $blogComment?->deletedBy?->name }}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-6 mb-2 mt-2">
                                                            {{ $blogComment?->deletedBy?->email }}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-7 mb-2">
                                                            {!! $blogComment?->deletedBy?->getRoleHtml() !!}
                                                        </span>
                                                        <span class="text-muted fw-semibold d-block fs-7">
                                                            {{ $blogComment->getDeletedAt() }}
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
                @endif

                @if (!empty($blogComment?->deleted_description))
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="kt_accordion_1_header_4">
                            <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_4" aria-expanded="false" aria-controls="kt_accordion_1_body_4">
                                Silinme Nedeni ?
                            </button>
                        </h2>
                        <div id="kt_accordion_1_body_4" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_4" data-bs-parent="#kt_accordion_1">
                            <div class="accordion-body">
                                <div class="col-md-12 fv-row">
                                    {{ $blogComment?->deleted_description }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="separator separator-dashed mt-11 mb-11"></div>
        @endif

        <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2">Blog Ad ( {{ $defaultLanguage?->getCodeUppercase() }} )</label>
            <input type="text" class="form-control form-control-solid" placeholder="Blog Ad" value="{{ $blogComment?->blog?->content?->title }}" disabled />
        </div>

        <div class="col-md-12 fv-row mb-7">
            <div class="row cst-mobil-checkbox">
                <div class="col-md-6 fv-row mb-4">
                    <label class="fs-6 fw-semibold mb-2">Ad Soyad</label>
                    <input type="text" class="form-control form-control-solid" placeholder="Ad Soyad" value="{{ $blogComment?->name }}" disabled />
                </div>

                <div class="col-md-6 fv-row mb-4">
                    <label class="fs-6 fw-semibold mb-2">E-Posta Adresi</label>
                    <input type="text" class="form-control form-control-solid" placeholder="E-Posta Adresi" value="{{ $blogComment?->email }}" disabled />
                </div>

                <div class="col-md-6 fv-row mb-4">
                    <label class="fs-6 fw-semibold mb-2">Yorum Yapan IP</label>
                    <input type="text" class="form-control form-control-solid" placeholder="Yorum Yapan IP" value="{{ $blogComment?->ip_address }}" disabled />
                </div>

                <div class="col-md-6 fv-row mb-4">
                    <label class="fs-6 fw-semibold mb-2">Toplam Yorum</label>
                    <input type="text" class="form-control form-control-solid" placeholder="Toplam Yorum" value="{{ $blogComment?->blog?->comments?->count() }}" disabled />
                </div>
            </div>
        </div>

        <div class="fv-row mb-7">
            <label class="required fs-6 fw-semibold mb-2">Kullanıcı Yorumu</label>
            <textarea name="comment" id="comment" class="form-control form-control-solid cst-description" rows="10" placeholder="Yorum *" minlength="1" maxlength="1000" @disabled(!empty($disable))>{{ $blogComment?->comment }}</textarea>
        </div>

        <div class="fv-row mb-7">
            <label class="fs-6 fw-semibold mb-2">Yetkili Cevabı</label>
            <textarea name="reply_comment" id="reply-comment" class="form-control form-control-solid cst-description" rows="10" placeholder="Yetkili Cevabı" minlength="1" maxlength="2000" @disabled(!empty($disable))>{{ $blogComment?->reply_comment }}</textarea>
        </div>
    </div>
</div>

@if (!empty($update))
    </form>
@endif

<script>
    $('#comment').maxlength({
        alwaysShow: true,
        warningClass: "badge badge-danger",
        limitReachedClass: "badge badge-success"
    }).on('focus input', function() {
        let length = $(this).val().length;

        if (length >= 1) {
            $(".bootstrap-maxlength").removeClass("badge-danger").addClass("badge-success"); // Yeşil yap
        } else {
            $(".bootstrap-maxlength").removeClass("badge-success").addClass("badge-danger"); // Kırmızı yap
        }
    });

    $('#reply-comment').maxlength({
        alwaysShow: true,
        warningClass: "badge badge-danger",
        limitReachedClass: "badge badge-success"
    }).on('focus input', function() {
        let length = $(this).val().length;

        if (length >= 1) {
            $(".bootstrap-maxlength").removeClass("badge-danger").addClass("badge-success"); // Yeşil yap
        } else {
            $(".bootstrap-maxlength").removeClass("badge-success").addClass("badge-danger"); // Kırmızı yap
        }
    });
</script>
