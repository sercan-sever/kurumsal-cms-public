$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click", ".service-delete-image-btn", async function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let service = $(this).data('service');

        if (empty(id)) {

            showSwal('info', 'Hizmet Görseli Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        if (empty(service)) {

            showSwal('info', 'Hizmet Görseli Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/service/delete/image', { id: id, service_id: service });

            const data = response.data;

            if (data.success) {
                let service = data.service;

                $('.cst-service-images').filter(function () {
                    return $(this).data('image') === service;
                }).remove();
            }

            $('#delete-image-service-modal').modal('hide');

            waitHideAndButtonActive();

            showToast(data.message, data.success ? "#4fbe87" : "#DC3546");

        } catch (error) {
            const response = error.response;

            // Hata durumunu kontrol et
            if (response && response.status === 422) {
                $.each(response.data.errors, function (key, value) {
                    showToast(value[0], "#DC3546");
                });
            } else if (response && response.data.message) {
                showToast(response.data.message, "#DC3546");
            } else {
                showToast(error.message || 'Beklenmeyen Bir Hata Oluştu.', "#DC3546");
            }

            waitHideAndButtonActive();
        }
    });
});
