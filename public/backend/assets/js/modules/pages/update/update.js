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
                let page = data.page;
                let title = data.title;
                let topPage = data.topPage;
                let isTopPage = data.isTopPage;
                let menuHtml = data.menu;
                let statusHtml = data.status;
                let breadcrumbHtml = data.breadcrumb;

                updateRowTable(page, title, topPage, isTopPage, menuHtml, statusHtml, breadcrumbHtml);

                $('#update-page-modal').modal('hide');
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

    function updateRowTable(page, title, topPageHtml, isTopPageHtml, menuHtml, statusHtml, breadcrumbHtml) {
        let datatable = $('#page-lists').DataTable();

        // Mevcut sayfa indeksini al
        let pages = datatable.page();

        // Mevcut satırı data-id ile bul
        let row = datatable.row(`[data-id="${(page.id ?? '')}"]`);

        if (!row.node()) {
            showSwal('info', 'Güncellenicek Bir Satır Bulunamadı!!!', 'center');
            return;
        }

        // Satırdaki mevcut veriyi al
        let rowData = row.data();

        rowData[0] = `<button type="button" class="read-view-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        ${!isTopPageHtml ? `<button type="button" class="page-builder-btn btn btn-icon btn-bg-light btn-light-success btn-sm me-1">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </button>` : ''}
                        <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                            <i class="fa-solid fa-trash"></i>
                        </button>`;

        rowData[1] = `${(title ?? '')}`;
        rowData[2] = `${(topPageHtml ?? '')}`;
        rowData[3] = `${(menuHtml ?? '')}`;
        rowData[4] = `${(statusHtml ?? '')}`;
        rowData[5] = `${(breadcrumbHtml ?? '')}`;
        rowData[6] = `${(page.sorting ?? '1')}`;

        // Güncellenen verileri satıra geri ekle
        row.data(rowData).draw(false);

        datatable.order([6, 'asc']).draw(false);

        // Güncelleme sonrası eski sayfaya geri dön
        datatable.page(pages).draw(false);
    }
});
