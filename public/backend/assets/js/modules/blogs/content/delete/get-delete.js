$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#delete-blog-modal', function () {
        $('#delete-blog-modal .blog-delete-btn').data('id', '');
        $('#delete-blog-modal #deleted-description').val('');
    });

    $(document).on('click', '.delete-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Blog Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#delete-blog-modal .blog-delete-btn').data('id', id);
        $('#delete-blog-modal').modal('show');
    });
});
