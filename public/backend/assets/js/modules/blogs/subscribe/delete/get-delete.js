$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#delete-blog-subscribe-modal', function () {
        $('#delete-blog-subscribe-modal .blog-subscribe-delete-btn').data('id', '');
        $('#delete-blog-subscribe-modal #deleted-description').val('');
    });

    $(document).on('click', '.delete-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Blog Abone Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#delete-blog-subscribe-modal .blog-subscribe-delete-btn').data('id', id);
        $('#delete-blog-subscribe-modal').modal('show');
    });
});
