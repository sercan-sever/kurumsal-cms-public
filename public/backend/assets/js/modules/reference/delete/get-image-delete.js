$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#delete-image-reference-modal', function () {
        $('#delete-image-reference-modal .reference-delete-image-btn').data('id', '');
        $('#delete-image-reference-modal .reference-delete-image-btn').val('reference', '');
    });

    $(document).on('click', '.cst-remove-btn', async function (e) {
        e.preventDefault();

        let id = $(this).data('image');
        let reference = $(this).data('reference');

        if (empty(id)) {

            showSwal('info', 'Referans Görseli Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        if (empty(reference)) {

            showSwal('info', 'Referans Görseli Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#delete-image-reference-modal .reference-delete-image-btn').data('id', id);
        $('#delete-image-reference-modal .reference-delete-image-btn').data('reference', reference);
        $('#delete-image-reference-modal').modal('show');
    });
});
