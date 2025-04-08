$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#trashed-remove-reference-modal', function () {
        $('#trashed-remove-reference-modal .trashed-remove-reference-delete-btn').data('id', '');
    });

    $(document).on('click', '.trashed-remove-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Silinen Referans Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#trashed-remove-reference-modal .trashed-remove-reference-delete-btn').data('id', id);
        $('#trashed-remove-reference-modal').modal('show');
    });
});
