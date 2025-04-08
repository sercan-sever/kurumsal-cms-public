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
                let blogComment = data.blogComment;
                let blogTitle = data.blogTitle;
                let comment = data.comment;
                let blogStatus = data.blogStatus;
                let blogCommentStatus = data.blogCommentStatus;
                let createdAt = data.createdAt;

                updateRowTable(blogComment, blogTitle, comment, blogStatus, blogCommentStatus, createdAt);

                $('#update-blog-comment-modal').modal('hide');
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

    function updateRowTable(blogComment, blogTitle, comment, blogStatus, blogCommentStatus, createdAt) {
        let datatable = $('#confirm-blog-comment-lists').DataTable();

        // Mevcut sayfa indeksini al
        let page = datatable.page();

        // Mevcut satırı data-id ile bul
        let row = datatable.row(`[data-id="${(blogComment.id ?? '')}"]`);

        if (!row.node()) {
            showSwal('info', 'Güncellenicek Bir Satır Bulunamadı!!!', 'center');
            return;
        }

        // Satırdaki mevcut veriyi al
        let rowData = row.data();

        rowData[3] = `${(blogTitle ?? '')}`;

        rowData[4] = `${(comment ?? '')}`;

        rowData[6] = `${(blogStatus ?? '')}`;

        rowData[7] = `${(blogCommentStatus ?? '')}`;

        rowData[8] = `${(createdAt ?? '')}`;

        // Güncellenen verileri satıra geri ekle
        row.data(rowData).draw(false);

        datatable.order([6, 'asc']).draw(false);

        // Güncelleme sonrası eski sayfaya geri dön
        datatable.page(page).draw(false);
    }
});
