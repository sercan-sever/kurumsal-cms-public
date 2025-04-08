<h5 class="mb-7 d-flex align-items-center">
    <span class="bullet bg-danger w-15px me-3"></span> E-Posta Adresleri ( {{ $language?->getCodeUppercase() }} )
</h5>

<div class="row mb-7">
    <div class="col-md-6 fv-row mb-4">
        <label class="required fs-6 fw-semibold mb-2">E-Posta Başlık 1</label>
        <input type="text" name="{{ $language?->code }}[email_title_one]" class="form-control form-control-solid" placeholder="E-Posta Başlık 1 *" min="1" required />
    </div>
    <div class="col-md-6 fv-row mb-4">
        <label class="required fs-6 fw-semibold mb-2">E-Posta Adres 1</label>
        <input type="text" name="{{ $language?->code }}[email_address_one]" class="form-control form-control-solid" placeholder="E-Posta Adres 1 *" min="1" required />
    </div>
</div>
<div class="row mb-7">
    <div class="col-md-6 fv-row mb-4">
        <label class="fs-6 fw-semibold mb-2">E-Posta Başlık 2</label>
        <input type="text" name="{{ $language?->code }}[email_title_two]" class="form-control form-control-solid" placeholder="E-Posta Başlık 2" min="1" />
    </div>
    <div class="col-md-6 fv-row mb-4">
        <label class="fs-6 fw-semibold mb-2">E-Posta Adres 2</label>
        <input type="text" name="{{ $language?->code }}[email_address_two]" class="form-control form-control-solid" placeholder="E-Posta Adres 2" min="1" />
    </div>
</div>


<div class="separator separator-dashed mb-11"></div>

<h5 class="mb-7 d-flex align-items-center">
    <span class="bullet bg-danger w-15px me-3"></span> Telefon Numaraları ( {{ $language?->getCodeUppercase() }} )
</h5>

<div class="row mb-7">
    <div class="col-md-6 fv-row mb-4">
        <label class="fs-6 fw-semibold mb-2">Telefon Başlık 1</label>
        <input type="text" name="{{ $language?->code }}[phone_title_one]" class="form-control form-control-solid" placeholder="Telefon Başlık 1" min="1" />
    </div>
    <div class="col-md-6 fv-row mb-4">
        <label class="fs-6 fw-semibold mb-2">Telefon Numarası 1</label>
        <input type="text" name="{{ $language?->code }}[phone_number_one]" class="form-control form-control-solid" placeholder="Telefon Numarası 1" min="1" />
    </div>
</div>
<div class="row mb-7">
    <div class="col-md-6 fv-row mb-4">
        <label class="fs-6 fw-semibold mb-2">Telefon Başlık 2</label>
        <input type="text" name="{{ $language?->code }}[phone_title_two]" class="form-control form-control-solid" placeholder="Telefon Başlık 2" min="1" />
    </div>
    <div class="col-md-6 fv-row mb-4">
        <label class="fs-6 fw-semibold mb-2">Telefon Numarası 2</label>
        <input type="text" name="{{ $language?->code }}[phone_number_two]" class="form-control form-control-solid" placeholder="Telefon Numarası 2" min="1" />
    </div>
</div>


<div class="separator separator-dashed mb-11"></div>

<h5 class="mb-7 d-flex align-items-center">
    <span class="bullet bg-danger w-15px me-3"></span> İletişim Adresleri ( {{ $language?->getCodeUppercase() }} )
</h5>

<div class="fv-row mb-7">
    <label class="required fs-6 fw-semibold mb-2">Adres Başlık 1</label>
    <input type="text" name="{{ $language?->code }}[address_title_one]" class="form-control form-control-solid" placeholder="Adres Başlık 1 *" min="1" required />
</div>
<div class="row mb-7">
    <div class="col-md-6 fv-row mb-4">
        <label class="required fs-6 fw-semibold mb-2">Adres Tarifi 1</label>
        <textarea name="{{ $language?->code }}[address_content_one]" class="form-control form-control-solid" rows="4" name="meta_keywords" placeholder="Adres Tarifi 1 *" min="1" required></textarea>
    </div>
    <div class="col-md-6 fv-row mb-4">
        <label class="required fs-6 fw-semibold mb-2">Adres İframe 1</label>
        <textarea name="{{ $language?->code }}[address_iframe_one]" class="form-control form-control-solid" rows="4" name="meta_description" placeholder="Adres İframe 1 *" min="1" required></textarea>
    </div>
</div>

<div class="fv-row mb-7">
    <label class="fs-6 fw-semibold mb-2">Adres Başlık 2</label>
    <input type="text" name="{{ $language?->code }}[address_title_two]" class="form-control form-control-solid" placeholder="Adres Başlık 2" min="1" />
</div>
<div class="row mb-7">
    <div class="col-md-6 fv-row mb-4">
        <label class="fs-6 fw-semibold mb-2">Adres Tarifi 2</label>
        <textarea name="{{ $language?->code }}[address_content_two]" class="form-control form-control-solid" rows="4" name="meta_keywords" placeholder="Adres Tarifi 2" min="1"></textarea>
    </div>
    <div class="col-md-6 fv-row mb-4">
        <label class="fs-6 fw-semibold mb-2">Adres İframe 2</label>
        <textarea name="{{ $language?->code }}[address_iframe_two]" class="form-control form-control-solid" rows="4" name="meta_description" placeholder="Adres İframe 2" min="1"></textarea>
    </div>
</div>
