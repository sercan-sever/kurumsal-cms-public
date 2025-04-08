$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#add-confirm-blog-comment-modal', function () {
        $('#add-confirm-blog-comment-modal #add-confirm-blog-comment-modal-scroll').empty();
        $('#add-confirm-blog-comment-btn').data('comment', null);
        $('#add-update-confirm-blog-comment-btn').data('comment', null);
    });

    $(document).on('click', '.confirm-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Blog Yorum Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/blogs/comment/confirm/read', { id: id });

            const data = response.data;

            if (data.success) {

                let blogComment = data.blogComment;
                let blogCommentView = data.blogCommentView;

                $('#add-confirm-blog-comment-modal-scroll').html(blogCommentView ?? '');
                $('#add-confirm-blog-comment-btn').data('comment', blogComment ?? '');
                $('#add-update-confirm-blog-comment-btn').data('comment', blogComment ?? '');

                $('#add-confirm-blog-comment-modal').modal('show');
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
});
