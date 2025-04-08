$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#delete-faq-modal', function () {
        $('#delete-faq-modal .faq-delete-btn').data('id', '');
        $('#delete-faq-modal #deleted-description').val('');
    });

    $(document).on('click', '.delete-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Blog Etiketi Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#delete-faq-modal .faq-delete-btn').data('id', id);
        $('#delete-faq-modal').modal('show');
    });
});
