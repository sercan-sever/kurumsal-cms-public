$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click", ".reference-delete-image-btn", async function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let reference = $(this).data('reference');

        if (empty(id)) {

            showSwal('info', 'Referans Görseli Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        if (empty(reference)) {

            showSwal('info', 'Referans Görseli Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/reference/delete/image', { id: id, reference_id: reference });

            const data = response.data;

            if (data.success) {
                let reference = data.reference;

                $('.cst-reference-images').filter(function () {
                    return $(this).data('image') === reference;
                }).remove();
            }

            $('#delete-image-reference-modal').modal('hide');

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
