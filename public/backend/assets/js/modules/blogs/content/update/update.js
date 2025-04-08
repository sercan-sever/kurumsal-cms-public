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
                let blog = data.blog;
                let content = data.blogContent;
                let image = data.image;
                let publishedAt = data.publishedAt;
                let statusHtml = data.status;
                let commentStatusHtml = data.commentStatus;

                updateRowTable(blog, content, image, publishedAt, statusHtml, commentStatusHtml);

                $('#update-blog-modal').modal('hide');
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

    function updateRowTable(blog, content, image, publishedAt, statusHtml, commentStatusHtml) {
        let datatable = $('#blog-lists').DataTable();

        // Mevcut sayfa indeksini al
        let page = datatable.page();

        // Mevcut satırı data-id ile bul
        let row = datatable.row(`[data-id="${(blog.id ?? '')}"]`);

        if (!row.node()) {
            showSwal('info', 'Güncellenicek Bir Satır Bulunamadı!!!', 'center');
            return;
        }

        // Satırdaki mevcut veriyi al
        let rowData = row.data();

        rowData[1] = `${(image ?? '')}`;// Görseli güncelle

        rowData[2] = `${(content.title ?? '')}`;
        rowData[3] = `${(publishedAt ?? '')}`;
        rowData[4] = `${(statusHtml ?? '')}`;
        rowData[5] = `${(commentStatusHtml ?? '')}`;
        rowData[6] = `${(blog.sorting ?? '1')}`;

        // Güncellenen verileri satıra geri ekle
        row.data(rowData).draw(false);

        datatable.order([6, 'desc']).draw(false);

        // Güncelleme sonrası eski sayfaya geri dön
        datatable.page(page).draw(false);
    }
});
