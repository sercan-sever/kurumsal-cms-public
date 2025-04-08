$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click", ".translation-delete-btn", async function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        if (empty(id)) {

            showSwal('info', 'Dil Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/translation/content/delete', { id: id });

            const data = response.data;

            if (data.success) {
                let translationContent = data.translationContent;

                let datatable = $('#translation-lists').DataTable();

                // İlgili satırı direkt olarak seç
                let rowToDelete = $('#translation-lists tbody tr').filter(function () {
                    return $(this).data('id') == translationContent.id;
                });

                if (!empty(rowToDelete)) {
                    let row = datatable.row(rowToDelete);
                    row.remove().draw(false);
                }
            }

            $('#delete-translation-modal').modal('hide');

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

            $('#delete-translation-modal').modal('hide');

            waitHideAndButtonActive();
        }
    });
});
