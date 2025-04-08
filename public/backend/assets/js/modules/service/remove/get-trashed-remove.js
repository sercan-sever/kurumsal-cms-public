$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#trashed-remove-service-modal', function () {
        $('#trashed-remove-service-modal .trashed-remove-service-delete-btn').data('id', '');
    });

    $(document).on('click', '.trashed-remove-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Silinen Hizmet Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#trashed-remove-service-modal .trashed-remove-service-delete-btn').data('id', id);
        $('#trashed-remove-service-modal').modal('show');
    });
});
