$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click", ".banner-delete-btn", async function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let deleted_description = $('#deleted-description').val();

        if (empty(id)) {

            showSwal('info', 'Banner Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        if (empty(deleted_description)) {

            showSwal('info', 'Silme Nedeni Zorunludur !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/banner/delete', { id: id, deleted_description: deleted_description });

            const data = response.data;

            if (data.success) {
                let banner = data.banner;
                let content = data.bannerContent;
                let image = data.image;

                let datatable = $('#banner-lists').DataTable();

                // İlgili satırı direkt olarak seç
                let rowToDelete = $('#banner-lists tbody tr').filter(function () {
                    return $(this).data('id') == banner.id;
                });

                if (!empty(rowToDelete)) {
                    // DataTable referansı ile satırı sil
                    let row = datatable.row(rowToDelete);
                    row.remove().draw(false);

                    trashedTableAdd(banner, content, image);
                }
            }

            $('#delete-banner-modal').modal('hide');

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

            $('#delete-language-modal').modal('hide');

            waitHideAndButtonActive();
        }
    });

    function trashedTableAdd(banner, content, image) {
        let datatable = $('#trashed-banner-lists').DataTable();

        let buttons = `<button type="button" class="read-trashed-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="trashed-restore-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                            <i class="fa-solid fa-recycle"></i>
                        </button>
                        <button type="button" class="trashed-remove-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                            <i class="fas fa-dumpster-fire"></i>
                        </button>`;

        let imageHtml = `${(image ?? '')}`;

        let title = `${(content.title ?? '')}`;

        let description = `${(banner.deleted_description ?? '')}`;

        let sorting = `${(banner.sorting ?? '1')}`;

        // Yeni satırı ekle ve referans al
        let rowNode = datatable.row.add([
            buttons,
            imageHtml,
            title,
            description,
            sorting,
        ]).draw(false).node(); // Tabloyu güncelle ve DOM düğümünü al

        // Eklenen satıra 'data-id' niteliği ekle
        $(rowNode).attr('data-id', banner.id ?? '');

        datatable.order([4, 'asc']).draw(false);
    }
});
