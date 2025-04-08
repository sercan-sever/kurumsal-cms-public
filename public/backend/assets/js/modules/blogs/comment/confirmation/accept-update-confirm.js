$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '#add-update-confirm-blog-comment-btn', async function (e) {
        e.preventDefault();

        let id = $(this).data('comment');
        let comment = $('#comment').val();
        let replyComment = $('#reply-comment').val();

        if (empty(id)) {

            showSwal('info', 'Blog Yorum Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/blogs/comment/confirm/accept-update', { id: id, comment: comment, reply_comment: replyComment });

            const data = response.data;

            if (data.success) {

                let blogComment = data.blogComment;
                let blogTitle = data.blogTitle;
                let comment = data.comment;
                let blogStatus = data.blogStatus;
                let blogCommentStatus = data.blogCommentStatus;
                let createdAt = data.createdAt;

                tableAdd(blogComment, blogTitle, comment, blogStatus, blogCommentStatus, createdAt);

                // Silme İşlemleri
                let datatable = $('#blog-comment-lists').DataTable();

                let rowToDelete = $('#blog-comment-lists tbody tr').filter(function () {
                    return $(this).data('id') == blogComment.id;
                });

                if (!empty(rowToDelete)) {
                    let row = datatable.row(rowToDelete);
                    row.remove().draw(false);
                }

                // Modal Kapat
                $('#add-confirm-blog-comment-modal').modal('hide');
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

    function tableAdd(blogComment, blogTitle, comment, blogStatus, blogCommentStatus, createdAt) {
        let datatable = $('#confirm-blog-comment-lists').DataTable();

        let buttons = `<button type="button" class="view-confirm-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                            <i class="fa-solid fa-trash"></i>
                        </button>`;

        let name = `${(blogComment.name ?? '')}`;

        let email = `${(blogComment.email ?? '')}`;

        let title = `${(blogTitle ?? '')}`;

        let commentHtml = `${(comment ?? '')}`;

        let ip = `${(blogComment.ip_address ?? '')}`;

        let status = `${(blogStatus ?? '')}`;

        let commentStatus = `${(blogCommentStatus ?? '')}`;

        let createdAtDate = `${(createdAt ?? '')}`;

        // Yeni satırı ekle ve referans al
        let rowNode = datatable.row.add([
            buttons,
            name,
            email,
            title,
            commentHtml,
            ip,
            status,
            commentStatus,
            createdAtDate,
        ]).draw(false).node(); // Tabloyu güncelle ve DOM düğümünü al

        // Eklenen satıra 'data-id' niteliği ekle
        $(rowNode).attr('data-id', blogComment.id ?? '');

        datatable.order([8, 'asc']).draw(false);
    }
});
