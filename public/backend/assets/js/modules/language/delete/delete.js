$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('hidden.bs.modal', '#delete-language-modal', function () {
        $('#delete-language-modal .language-delete-btn').data('id', '');
        $('#delete-language-modal #deleted-description').val('');
    });

    $(document).on("click", ".language-delete-btn", async function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let deleted_description = $('#deleted-description').val();

        if (empty(id)) {

            showSwal('info', 'Dil Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        if (empty(deleted_description)) {

            showSwal('info', 'Silme Nedeni Zorunludur !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/languages/delete', { id: id, deleted_description: deleted_description });

            const data = response.data;

            if (data.success) {
                let language = data.language;
                let image = data.image;

                let datatable = $('#language-lists').DataTable();

                // İlgili satırı direkt olarak seç
                let rowToDelete = $('#language-lists tbody tr').filter(function () {
                    return $(this).data('id') == language.id;
                });

                if (!empty(rowToDelete)) {
                    // DataTable referansı ile satırı sil
                    let row = datatable.row(rowToDelete);
                    row.remove().draw(false);

                    trashedTableAdd(language, image);
                }

                $('#delete-language-modal').modal('hide');
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

    function trashedTableAdd(language, image) {
        let datatable = $('#trashed-language-lists').DataTable();

        let buttons = `<button type="button" class="read-delete-view-btn btn btn-icon btn-bg-light btn-light-warning btn-sm me-1 mb-1">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="trashed-restore-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                            <i class="fa-solid fa-recycle"></i>
                        </button>`;

        let imageHtml = `${(image ?? '')}`;

        let name = `${(language.name ?? '')}`;

        let code = `${(language.code ?? '')}`;

        // Yeni satırı ekle ve referans al
        let rowNode = datatable.row.add([
            buttons,
            imageHtml,
            name,
            code,
        ]).draw(false).node(); // Tabloyu güncelle ve DOM düğümünü al

        // Eklenen satıra 'data-id' niteliği ekle
        $(rowNode).attr('data-id', language.id ?? '');

        datatable.order([2, 'asc']).draw(false);
    }
});
