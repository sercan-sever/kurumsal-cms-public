$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#reject-blog-comment-modal', function () {
        $('#reject-blog-comment-modal .reject-blog-comment-delete-btn').data('id', '');
    });

    $(document).on('click', '.reject-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Blog Yorum Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#reject-blog-comment-modal .reject-blog-comment-delete-btn').data('id', id);
        $('#reject-blog-comment-modal').modal('show');
    });
});
