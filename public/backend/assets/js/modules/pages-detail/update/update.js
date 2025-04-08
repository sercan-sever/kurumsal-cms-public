$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(document).on("submit", "#update-form", async function (e) {
        e.preventDefault();

        let url = $(this).data('action');
        let id = $('#page').val();

        let sectionsData = [];
        $("#update-form .draggable-zone .draggable").each(function (index) {
            sectionsData.push($(this).data("id"));
        });

        if (empty(id)) {

            showSwal('info', 'Sayfa Bulunamadı !!!', 'center');

            return;
        }

        if (empty(sectionsData)) {

            showSwal('info', 'Bölüm Bulunamadı !!!', 'center');

            return;
        }


        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post(url, { id: id, sections: sectionsData });

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

            waitHideAndButtonActive();
        }
    });

});
