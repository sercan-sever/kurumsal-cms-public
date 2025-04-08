$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#view-email-modal', function () {
        $('#view-email-modal-scroll .updated-name').html('');
        $('#view-email-modal-scroll .updated-email').html('');
        $('#view-email-modal-scroll .updated-statu').html('');
        $('#view-email-modal-scroll .updated-date').html('');
    });

    $(document).on('click', '.read-view-btn', async function (e) {
        e.preventDefault();

        let id = $(this).data('id');

        if (empty(id)) {

            showSwal('info', 'E-Mail Ayarları İçeriği Bulunamadı !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/settings/email/read-view', { id: id });

            const data = response.data;

            if (data.success) {

                let title = data.title;
                let email = data.email;
                let statu = data.statu;
                let date = data.date;

                $('#view-email-modal-scroll .updated-name').html(title ?? '');
                $('#view-email-modal-scroll .updated-email').html(email ?? '');
                $('#view-email-modal-scroll .updated-statu').html(statu ?? '');
                $('#view-email-modal-scroll .updated-date').html(date ?? '');

                $('#view-email-modal').modal('show');
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
