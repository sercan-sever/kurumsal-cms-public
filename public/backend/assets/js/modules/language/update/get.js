$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /* UPDATE LANGUAGE GET DATA */

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#update-language-modal', function () {
        $('#update-form').trigger('reset');

        // image değeri siliniyor
        $('#update-language-modal .image-input-placeholder').css('background-image', '');
        var $cancelElement = $('[data-kt-image-input-action="cancel"]');
        $cancelElement.trigger('click');
    });

    $(document).on('click', '.edit-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Dil Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/languages/read', { id: id });

            const data = response.data;

            if (data.success) {

                let language = data.language;

                $('#update-id').val(language.id);
                $('#update-name').val(language.name);
                $('#update-code').val(language.code);
                $('#update-sorting').val(language.sorting);
                $('#update-status').prop('checked', data.status);
                $('#update-default').prop('checked', data.default);

                $('#update-language-modal .image-input-placeholder').css('background-image', `url(${data.image})`);
                $('#update-language-modal .image-input-wrapper').css('background-image', `url(${data.image})`);

                $('#update-language-modal').modal('show');
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


    /* DELETE LANGUAGE GET DATA */

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#delete-language-modal', function () {
        $('#delete-language-modal .language-delete-btn').data('id', '');
    });

    $(document).on('click', '.delete-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Dil Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#delete-language-modal .language-delete-btn').data('id', id);
        $('#delete-language-modal').modal('show');
    });


    /* TRASHED RESTORE LANGUAGE GET DATA */

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#trashed-restore-language-modal', function () {
        $('#trashed-restore-language-modal .trashed-restore-language-delete-btn').data('id', '');
    });

    $(document).on('click', '.trashed-restore-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Dil Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#trashed-restore-language-modal .trashed-restore-language-delete-btn').data('id', id);
        $('#trashed-restore-language-modal').modal('show');
    });


    /* TRANSLATION GET PAGE */
    $(document).on('click', '.translate-btn', async function (e) {
        e.preventDefault();


        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Dil Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/translation/check-status', { id: id });

            const data = response.data;

            if (data.success) {

                showToast(data.message, data.success ? "#4fbe87" : "#DC3546");

                waitHideAndButtonActive();

                $(location).attr('href', data.url);

                return;
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
