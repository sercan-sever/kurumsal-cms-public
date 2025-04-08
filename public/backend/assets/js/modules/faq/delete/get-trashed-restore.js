$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#trashed-restore-faq-modal', function () {
        $('#trashed-restore-faq-modal .trashed-restore-faq-delete-btn').data('id', '');
    });

    $(document).on('click', '.trashed-restore-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Silinen S. S. S. Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#trashed-restore-faq-modal .trashed-restore-faq-delete-btn').data('id', id);
        $('#trashed-restore-faq-modal').modal('show');
    });
});
