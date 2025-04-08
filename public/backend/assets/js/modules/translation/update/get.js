$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /* UPDATE TRANSLATION GET DATA */

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#update-translation-modal', function () {
        $('#update-form').trigger('reset');
    });

    $(document).on('click', '.edit-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Çeviri Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/translation/content/read', { id: id });

            const data = response.data;

            if (data.success) {

                let translationContent = data.translationContent;

                $('#update-id').val(translationContent.id ?? '');
                $('#update-translation-id').val(translationContent.translation_id ?? '');
                $('#update-group').val(translationContent.group ?? '');
                $('#update-key').val(translationContent.key ?? '');
                $('#update-value').val(translationContent.value ?? '');

                $('#update-translation-modal').modal('show');
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


    /* DELETE TRANSLATION GET DATA */

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#delete-translation-modal', function () {
        $('#delete-translation-modal .translation-delete-btn').data('id', '');
    });

    $(document).on('click', '.delete-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Çeviri Bulunamadı. Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#delete-translation-modal .translation-delete-btn').data('id', id);
        $('#delete-translation-modal').modal('show');
    });
});
