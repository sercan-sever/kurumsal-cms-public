$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#trashed-remove-faq-modal', function () {
        $('#trashed-remove-faq-modal .trashed-remove-faq-delete-btn').data('id', '');
    });

    $(document).on('click', '.trashed-remove-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Silinen S. S. S. Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#trashed-remove-faq-modal .trashed-remove-faq-delete-btn').data('id', id);
        $('#trashed-remove-faq-modal').modal('show');
    });
});
