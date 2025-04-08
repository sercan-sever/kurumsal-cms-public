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
                let faq = data.faq;
                let title = data.title;
                let statusHtml = data.status;

                updateRowTable(faq, title, statusHtml);

                $('#update-faq-modal').modal('hide');
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

    function updateRowTable(faq, title, statusHtml) {
        let datatable = $('#faq-lists').DataTable();

        // Mevcut sayfa indeksini al
        let page = datatable.page();

        // Mevcut satırı data-id ile bul
        let row = datatable.row(`[data-id="${(faq.id ?? '')}"]`);

        if (!row.node()) {
            showSwal('info', 'Güncellenicek Bir Satır Bulunamadı!!!', 'center');
            return;
        }

        // Satırdaki mevcut veriyi al
        let rowData = row.data();

        rowData[1] = `${(title ?? '')}`;
        rowData[2] = `${(statusHtml ?? '')}`;
        rowData[3] = `${(faq.sorting ?? '1')}`;

        // Güncellenen verileri satıra geri ekle
        row.data(rowData).draw(false);

        datatable.order([3, 'asc']).draw(false);

        // Güncelleme sonrası eski sayfaya geri dön
        datatable.page(page).draw(false);
    }
});
