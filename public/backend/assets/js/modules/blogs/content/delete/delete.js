$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click", ".blog-delete-btn", async function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let deleted_description = $('#deleted-description').val();

        if (empty(id)) {

            showSwal('info', 'Blog Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        if (empty(deleted_description)) {

            showSwal('info', 'Silme Nedeni Zorunludur !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/blogs/delete', { id: id, deleted_description: deleted_description });

            const data = response.data;

            if (data.success) {
                let blog = data.blog;
                let content = data.blogContent;
                let image = data.image;
                let publishedAt = data.publishedAt;

                let datatable = $('#blog-lists').DataTable();

                // İlgili satırı direkt olarak seç
                let rowToDelete = $('#blog-lists tbody tr').filter(function () {
                    return $(this).data('id') == blog.id;
                });

                if (!empty(rowToDelete)) {
                    // DataTable referansı ile satırı sil
                    let row = datatable.row(rowToDelete);
                    row.remove().draw(false);

                    trashedTableAdd(blog, content, image, publishedAt);
                }
            }

            $('#delete-blog-modal').modal('hide');

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

    function trashedTableAdd(blog, content, image, publishedAt) {
        let datatable = $('#trashed-blog-lists').DataTable();

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

        let description = `${(blog.deleted_description ?? '')}`;

        let date = `${(publishedAt ?? '')}`;

        let sorting = `${(blog.sorting ?? '1')}`;

        // Yeni satırı ekle ve referans al
        let rowNode = datatable.row.add([
            buttons,
            imageHtml,
            title,
            description,
            date,
            sorting,
        ]).draw(false).node(); // Tabloyu güncelle ve DOM düğümünü al

        // Eklenen satıra 'data-id' niteliği ekle
        $(rowNode).attr('data-id', blog.id ?? '');

        datatable.order([5, 'desc']).draw(false);
    }
});
