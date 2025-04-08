$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    /* GET USER DATA */
    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#update-user-management-modal', function () {
        $('#update-form').trigger('reset');

        // image değeri siliniyor
        $('#update-user-management-modal .image-input-placeholder').css('background-image', '');
        var $cancelElement = $('#update-user-management-modal [data-kt-image-input-action="cancel"]');
        $cancelElement.trigger('click');
    });

    $(document).on('click', '.edit-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Yetkili Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/user-managements/get-by-active-user', { id: id });

            const data = response.data;

            if (data.success) {

                let user = data.user;

                $('#update-id').val(user.id ?? '');
                $('#update-name').val(user.name ?? '');
                $('#update-email').val(user.email ?? '');
                $('#update-phone').val(user.phone ?? '');
                $('#update-user-management-modal .image-input-placeholder').css('background-image', `url(${(data.image ?? '')})`);
                $('#update-user-management-modal .image-input-wrapper').css('background-image', `url(${(data.image ?? '')})`);

                $('#update-user-management-modal').modal('show');
            }

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


    /* GET PASSWORD DATA */
    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#change-password-user-management-modal', function () {
        $('#update-password-form').trigger('reset');
    });

    $(document).on('click', '.password-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Yetkili Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/user-managements/get-by-active-user', { id: id });

            const data = response.data;

            if (data.success) {

                let user = data.user;

                $('#update-password-id').val(user.id ?? '');

                $('#change-password-user-management-modal').modal('show');
            }

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
