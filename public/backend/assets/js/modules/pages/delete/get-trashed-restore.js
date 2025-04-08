$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#trashed-restore-page-modal', function () {
        $('#trashed-restore-page-modal .trashed-restore-page-delete-btn').data('id', '');
    });

    $(document).on('click', '.trashed-restore-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {
            showSwal('info', 'Silinen Sayfa Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');
            return;
        }

        $('#trashed-restore-page-modal .trashed-restore-page-delete-btn').data('id', id);
        $('#trashed-restore-page-modal').modal('show');
    });
});
