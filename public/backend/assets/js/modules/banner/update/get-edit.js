$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#update-banner-modal', function () {
        $('#update-banner-modal #update-banner-modal-scroll').empty();
    });

    $(document).on('click', '.edit-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Banner Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/banner/read', { id: id });

            const data = response.data;

            if (data.success) {

                let banner = data.banner;
                $('#update-banner-modal-scroll').html(banner ?? '');

                const imageInputs = document.querySelectorAll('#update-banner-modal-scroll [data-kt-image-input="true"]');
                imageInputs.forEach(imageInput => {
                    new KTImageInput(imageInput); // KTImageInput'u başlat
                });

                $('#update-banner-modal').modal('show');
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
