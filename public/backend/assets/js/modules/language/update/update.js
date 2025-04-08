$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(document).on("submit", "#update-form", async function (e) {
        e.preventDefault();

        let url = $(this).data('action');
        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post(url, new FormData(this));

            const data = response.data;

            if (data.success) {
                let language = data.language;

                // Önceki varsayılanı pasife çek
                if (data.defaultActive) {
                    $('#language-lists span.badge').removeClass('badge-success').addClass('badge-danger').html('<i class="fa-solid text-white fa-xmark"></i>');
                }

                updateRowTable(language, data.image, data.status, data.default);

                resetUpdateForm();
            }

            waitHideAndButtonActive();

            showToast(data.message, data.success ? "#4fbe87" : "#DC3546");

        } catch (error) {
            const response = error.response;

            // Hata durumunu kontrol et
            if (response && response.status === 422) {
                $.each(response.data.errors, function (key, value) {
                    showToast(value[0], "#DC3546");
                });
            } else if (response && response.data.message) {
                showToast(response.data.message, "#DC3546");
            } else {
                showToast(error.message || 'Beklenmeyen Bir Hata Oluştu.', "#DC3546");
            }

            if (!$('#update-status').is(':checked')) {
                $('#update-status').prop('checked', !$('#update-status').is(':checked'));
            }

            if (!$('#update-default').is(':checked')) {
                $('#update-default').prop('checked', !$('#update-default').is(':checked'));
            }

            waitHideAndButtonActive();
        }
    });

    function updateRowTable(language, image, status, defaultIcon) {
        let datatable = $('#language-lists').DataTable();

        // Mevcut sayfa indeksini al
        let page = datatable.page();

        // Mevcut satırı data-id ile bul
        let row = datatable.row(`[data-id="${(language.id ?? '')}"]`);

        if (!row.node()) {
            showSwal('info', 'Güncellenicek Bir Satır Bulunamadı!!!', 'center');
            return;
        }

        // Satırdaki mevcut veriyi al
        let rowData = row.data();

        rowData[1] = `${(image ?? '')}`; // Görseli güncelle

        rowData[2] = `${(language.name ?? '')}`; // Dil ismini güncelle
        rowData[3] = `${(language.code ?? '')}`; // Kodu güncelle
        rowData[4] = `${(status ?? '')}`; // Durumu güncelle
        rowData[5] = `${(defaultIcon ?? '')}`; // Varsayılan ikonunu güncelle
        rowData[6] = `${(language.sorting ?? '1')}`; // Sıralama bilgisini güncelle

        // Güncellenen verileri satıra geri ekle
        row.data(rowData).draw(false);

        datatable.order([6, 'asc']).draw(false);

        // Güncelleme sonrası eski sayfaya geri dön
        datatable.page(page).draw(false);
    }

    function resetUpdateForm() {
        $('#update-language-modal').modal('hide');

        $('#update-form').trigger('reset');

        // image değeri siliniyor
        var $cancelElement = $('[data-kt-image-input-action="cancel"]');
        $cancelElement.trigger('click');
    }
});
