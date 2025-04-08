$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#delete-banner-modal', function () {
        $('#delete-banner-modal .banner-delete-btn').data('id', '');
        $('#delete-banner-modal #deleted-description').val('');
    });

    $(document).on('click', '.delete-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Banner Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#delete-banner-modal .banner-delete-btn').data('id', id);
        $('#delete-banner-modal').modal('show');
    });
});
