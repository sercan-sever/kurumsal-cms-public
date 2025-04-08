$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#trashed-remove-section-modal', function () {
        $('#trashed-remove-section-modal .trashed-remove-section-delete-btn').data('id', '');
    });

    $(document).on('click', '.trashed-remove-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Silinen Bölüm Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#trashed-remove-section-modal .trashed-remove-section-delete-btn').data('id', id);
        $('#trashed-remove-section-modal').modal('show');
    });
});
