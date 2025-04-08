<div class="fv-row mb-7">
    <label class="required fs-6 fw-semibold mb-2">Site Başlığı ( {{ $language?->getCodeUppercase() }} )</label>
    <input type="text" name="{{ $language?->code }}[title]" class="form-control form-control-solid" placeholder="Site Başlığı *" min="1" required />
</div>
<div class="row">
    <div class="col-md-6 fv-row mb-4">
        <label class="required fs-6 fw-semibold mb-2">Meta Anaharat Kelimeler ( {{ $language?->getCodeUppercase() }} )</label>
        <textarea name="{{ $language?->code }}[meta_keywords]" id="meta_keywords_{{ $language?->id }}" class="form-control form-control-solid" rows="4" placeholder="Meta Anaharat Kelimeler *" minlength="20" maxlength="150" required></textarea>
    </div>
    <div class="col-md-6 fv-row mb-4">
        <label class="required fs-6 fw-semibold mb-2">Meta Açıklama ( {{ $language?->getCodeUppercase() }} )</label>
        <textarea name="{{ $language?->code }}[meta_descriptions]" id="meta_descriptions_{{ $language?->id }}" class="form-control form-control-solid" rows="4" placeholder="Meta Açıklama *" minlength="50" maxlength="160" required></textarea>
    </div>
</div>
