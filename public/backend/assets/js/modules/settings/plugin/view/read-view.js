$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#view-plugin-modal', function () {
        $('#view-plugin-modal-scroll .updated-name').html('');
        $('#view-plugin-modal-scroll .updated-email').html('');
        $('#view-plugin-modal-scroll .updated-statu').html('');
        $('#view-plugin-modal-scroll .updated-date').html('');
    });

    $(document).on('click', '.read-view-btn', async function (e) {
        e.preventDefault();

        let id = $(this).data('id');

        if (empty(id)) {

            showSwal('info', 'Eklenti Ayarları İçeriği Bulunamadı !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/settings/plugin/read-view', { id: id });

            const data = response.data;

            if (data.success) {

                let title = data.title;
                let email = data.email;
                let statu = data.statu;
                let date = data.date;

                $('#view-plugin-modal-scroll .updated-name').html(title ?? '');
                $('#view-plugin-modal-scroll .updated-email').html(email ?? '');
                $('#view-plugin-modal-scroll .updated-statu').html(statu ?? '');
                $('#view-plugin-modal-scroll .updated-date').html(date ?? '');

                $('#view-plugin-modal').modal('show');
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
