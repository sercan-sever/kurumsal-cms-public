$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click", ".business-processes-delete-btn", async function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let deleted_description = $('#deleted-description').val();

        if (empty(id)) {

            showSwal('info', 'Süreç Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        if (empty(deleted_description)) {

            showSwal('info', 'Silme Nedeni Zorunludur !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/business-processes/delete', { id: id, deleted_description: deleted_description });

            const data = response.data;

            if (data.success) {
                let businessProcesses = data.businessProcesses;
                let content = data.content;

                let datatable = $('#business-processes-lists').DataTable();

                // İlgili satırı direkt olarak seç
                let rowToDelete = $('#business-processes-lists tbody tr').filter(function () {
                    return $(this).data('id') == businessProcesses.id;
                });

                if (!empty(rowToDelete)) {
                    // DataTable referansı ile satırı sil
                    let row = datatable.row(rowToDelete);
                    row.remove().draw(false);

                    trashedTableAdd(businessProcesses, content);
                }
            }

            $('#delete-business-processes-modal').modal('hide');

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

    function trashedTableAdd(businessProcesses, content) {
        let datatable = $('#trashed-business-processes-lists').DataTable();

        let buttons = `<button type="button" class="read-trashed-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="trashed-restore-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                            <i class="fa-solid fa-recycle"></i>
                        </button>
                        <button type="button" class="trashed-remove-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                            <i class="fas fa-dumpster-fire"></i>
                        </button>`;

        let header = `${(content.header ?? '')}`;

        let title = `${(content.title ?? '')}`;

        let description = `${(businessProcesses.deleted_description ?? '')}`;

        let sorting = `${(businessProcesses.sorting ?? '1')}`;

        // Yeni satırı ekle ve referans al
        let rowNode = datatable.row.add([
            buttons,
            header,
            title,
            description,
            sorting,
        ]).draw(false).node(); // Tabloyu güncelle ve DOM düğümünü al

        // Eklenen satıra 'data-id' niteliği ekle
        $(rowNode).attr('data-id', businessProcesses.id ?? '');

        datatable.order([4, 'asc']).draw(false);
    }
});
