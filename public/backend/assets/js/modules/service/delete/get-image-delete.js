$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#delete-image-service-modal', function () {
        $('#delete-image-service-modal .service-delete-image-btn').data('id', '');
        $('#delete-image-service-modal .service-delete-image-btn').val('service', '');
    });

    $(document).on('click', '.cst-remove-btn', async function (e) {
        e.preventDefault();

        let id = $(this).data('image');
        let service = $(this).data('service');

        if (empty(id)) {

            showSwal('info', 'Hizmet Görseli Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        if (empty(service)) {

            showSwal('info', 'Hizmet Görseli Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#delete-image-service-modal .service-delete-image-btn').data('id', id);
        $('#delete-image-service-modal .service-delete-image-btn').data('service', service);
        $('#delete-image-service-modal').modal('show');
    });
});
