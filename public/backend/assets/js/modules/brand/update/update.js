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
                let brand = data.brand;
                let content = data.brandContent;
                let image = data.image;
                let statusHtml = data.status;

                updateRowTable(brand, content, image, statusHtml);

                $('#update-brand-modal').modal('hide');
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

    function updateRowTable(brand, content, image, statusHtml) {
        let datatable = $('#brand-lists').DataTable();

        // Mevcut sayfa indeksini al
        let page = datatable.page();

        // Mevcut satırı data-id ile bul
        let row = datatable.row(`[data-id="${(brand.id ?? '')}"]`);

        if (!row.node()) {
            showSwal('info', 'Güncellenicek Bir Satır Bulunamadı!!!', 'center');
            return;
        }

        // Satırdaki mevcut veriyi al
        let rowData = row.data();

        rowData[1] = `${(image ?? '')}`; // Görseli güncelle

        rowData[2] = `${(content.title ?? '')}`;
        rowData[3] = `${(statusHtml ?? '')}`;
        rowData[4] = `${(brand.sorting ?? '1')}`;

        // Güncellenen verileri satıra geri ekle
        row.data(rowData).draw(false);

        datatable.order([4, 'asc']).draw(false);

        // Güncelleme sonrası eski sayfaya geri dön
        datatable.page(page).draw(false);
    }
});
