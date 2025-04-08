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
                let section = data.section;
                let category = data.category;
                let image = data.image;
                let statusHtml = data.status;

                updateRowTable(section, category, image, statusHtml);

                $('#update-section-modal').modal('hide');
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

            waitHideAndButtonActive();
        }
    });

    function updateRowTable(section, category, image, statusHtml) {
        let datatable = $('#section-lists').DataTable();

        // Mevcut sayfa indeksini al
        let page = datatable.page();

        // Mevcut satırı data-id ile bul
        let row = datatable.row(`[data-id="${(section.id ?? '')}"]`);

        if (!row.node()) {
            showSwal('info', 'Güncellenicek Bir Satır Bulunamadı!!!', 'center');
            return;
        }

        // Satırdaki mevcut veriyi al
        let rowData = row.data();

        rowData[1] = `${(image ?? '')}`;
        rowData[2] = `${(section.title ?? '')}`;
        rowData[3] = `${(category ?? '')}`;
        rowData[4] = `${(statusHtml ?? '')}`;
        rowData[5] = `${(section.sorting ?? '1')}`;

        // Güncellenen verileri satıra geri ekle
        row.data(rowData).draw(false); // false ekleyerek sayfanın sıfırlanmasını engelle

        // Sayfa sıralamasını koru
        datatable.order([5, 'asc']).draw(false);

        // Güncelleme sonrası eski sayfaya geri dön
        datatable.page(page).draw(false);
    }
});
