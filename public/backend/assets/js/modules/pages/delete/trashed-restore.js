$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click", ".trashed-restore-page-delete-btn", async function (e) {
        e.preventDefault();

        let id = $(this).data('id');

        if (empty(id)) {

            showSwal('info', 'Silinen Sayfa Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/pages/trashed/restore', { id: id });

            const data = response.data;

            if (data.success) {
                let page = data.page;
                let title = data.title;
                let topPage = data.topPage;
                let menuHtml = data.menu;
                let statusHtml = data.status;
                let breadcrumbHtml = data.breadcrumb;

                let datatable = $('#trashed-page-lists').DataTable();

                // İlgili satırı direkt olarak seç
                let rowToDelete = $('#trashed-page-lists tbody tr').filter(function () {
                    return $(this).data('id') == page.id;
                });

                if (!empty(rowToDelete)) {
                    // DataTable referansı ile satırı sil
                    let row = datatable.row(rowToDelete);
                    row.remove().draw(false);
                }

                tableAdd(page, title, topPage, menuHtml, statusHtml, breadcrumbHtml);

                $(this).data('id', '');
                $('#trashed-restore-page-modal').modal('hide');
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

            waitHideAndButtonActive();
        }
    });


    function tableAdd(page, title, topPageHtml, menuHtml, statusHtml, breadcrumbHtml) {
        let datatable = $('#page-lists').DataTable();

        let buttons = `<button type="button" class="read-view-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button type="button" class="page-builder-btn btn btn-icon btn-bg-light btn-light-success btn-sm me-1">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </button>
                        <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                            <i class="fa-solid fa-trash"></i>
                        </button>`;

        let contentTitle = `${(title ?? '')}`;

        let topPage = `${(topPageHtml ?? '')}`;

        let menu = `${(menuHtml ?? '')}`;

        let status = `${(statusHtml ?? '')}`;

        let breadcrumb = `${(breadcrumbHtml ?? '')}`;

        let sorting = `${(page.sorting ?? '1')}`;

        // Yeni satırı ekle ve referans al
        let rowNode = datatable.row.add([
            buttons,
            contentTitle,
            topPage,
            menu,
            status,
            breadcrumb,
            sorting,
        ]).draw(false).node(); // Tabloyu güncelle ve DOM düğümünü al

        // Eklenen satıra 'data-id' niteliği ekle
        $(rowNode).attr('data-id', page.id ?? '');

        datatable.order([6, 'asc']).draw(false);
    }

});
