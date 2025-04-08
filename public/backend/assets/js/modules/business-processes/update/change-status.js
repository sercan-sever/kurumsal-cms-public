$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('change', '.cst-datatable-status', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        let checkbox = $(this);
        let previousState = checkbox.prop("checked");

        if (empty(id)) {
            checkbox.prop("checked", !previousState);

            showSwal('info', 'Süreç Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/business-processes/change-status', { id: id });

            const data = response.data;

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

            checkbox.prop("checked", !previousState);
            waitHideAndButtonActive();
        }
    });
});
