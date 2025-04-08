$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("submit", "#update-form", async function (e) {
        e.preventDefault();

        let url = $(this).data('action');
        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post(url, new FormData(this));

            const data = response.data;

            if (data.success) {
                let user = data.user;

                if (!empty(user)) {
                    tableUpdate(user);

                    if (data.isTrue) {
                        $('#show-avatar').attr('src', data.image ?? '');
                        $('#show-menu-avatar').attr('src', data.image ?? '');

                        $('#show-name').html(data.name ?? '');
                        $('#show-email').html(data.email ?? '');
                    }
                }

                resetUpdateForm();
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


    function tableUpdate(user) {
        let datatable = $('#user-management-lists').DataTable();

        // Mevcut sayfa indeksini al
        let page = datatable.page();

        // Mevcut satırı data-id ile bul
        let row = datatable.row(`[data-id="${(user.id ?? '')}"]`);

        if (!row.node()) {
            showSwal('info', 'Güncellenicek Bir Satır Bulunamadı!!!', 'center');
            return;
        }

        // Satırdaki mevcut veriyi al
        let rowData = row.data();

        rowData[1] = `${(user.name ?? '')}`;
        rowData[2] = `${(user.email ?? '')}`;
        rowData[3] = `${(user.phone ?? '')}`;

        // Güncellenen verileri satıra geri ekle
        row.data(rowData).draw(false);

        datatable.order([1, 'asc']).draw(false);

        // Güncelleme sonrası eski sayfaya geri dön
        datatable.page(page).draw(false);
    }


    function resetUpdateForm() {
        $('#update-user-management-modal').modal('hide');

        $('#update-form').trigger('reset');

        // image değeri siliniyor
        var $cancelElement = $('#update-user-management-modal [data-kt-image-input-action="cancel"]');
        $cancelElement.trigger('click');
    }
});
