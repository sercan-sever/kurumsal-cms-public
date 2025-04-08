$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click", ".trashed-restore-service-delete-btn", async function (e) {
        e.preventDefault();

        let id = $(this).data('id');

        if (empty(id)) {

            showSwal('info', 'Silinen Hizmet Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/service/trashed/restore', { id: id });

            const data = response.data;

            if (data.success) {
                let service = data.service;
                let title = data.title;
                let image = data.image;
                let statusHtml = data.status;

                let datatable = $('#trashed-service-lists').DataTable();

                // İlgili satırı direkt olarak seç
                let rowToDelete = $('#trashed-service-lists tbody tr').filter(function () {
                    return $(this).data('id') == service.id;
                });

                if (!empty(rowToDelete)) {
                    // DataTable referansı ile satırı sil
                    let row = datatable.row(rowToDelete);
                    row.remove().draw(false);
                }

                tableAdd(service, title, image, statusHtml);

                $(this).data('id', '');
                $('#trashed-restore-service-modal').modal('hide');
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


    function tableAdd(service, title, image, statusHtml) {
        let datatable = $('#service-lists').DataTable();

        let buttons = `<button type="button" class="read-view-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                            <i class="fa-solid fa-trash"></i>
                        </button>`;

        let imageHtml = `${(image ?? '')}`;

        let contentTitle = `${(title ?? '')}`;

        let status = `${(statusHtml ?? '')}`;

        let sorting = `${(service.sorting ?? '1')}`;

        // Yeni satırı ekle ve referans al
        let rowNode = datatable.row.add([
            buttons,
            imageHtml,
            contentTitle,
            status,
            sorting,
        ]).draw(false).node(); // Tabloyu güncelle ve DOM düğümünü al

        // Eklenen satıra 'data-id' niteliği ekle
        $(rowNode).attr('data-id', service.id ?? '');

        datatable.order([4, 'asc']).draw(false);
    }

});
