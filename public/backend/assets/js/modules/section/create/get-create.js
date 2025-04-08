$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#add-section-content-modal', function () {
        $('#add-section-content-modal #add-section-content-modal-scroll').empty();
    });


    $(document).on('click', '.add-section-btn', async function (e) {
        e.preventDefault();

        let section = $(this).data('section');

        if (empty(section)) {

            showSwal('info', 'Bölüm Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/pages/sections/get-dynamic-section', { section: section });

            const data = response.data;

            if (data.success) {

                // $('#add-section-modal').modal('hide');

                let section = data.section;
                $('#add-section-content-modal-scroll').html(section ?? '');

                $('#add-section-content-modal').modal('show');
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
