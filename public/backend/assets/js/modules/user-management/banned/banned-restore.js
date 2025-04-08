$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click", ".banned-restore-user-management-delete-btn", async function (e) {
        e.preventDefault();

        let id = $(this).data('id');

        if (empty(id)) {

            showSwal('info', 'Yetkili Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/user-managements/banned-restore', { id: id });

            const data = response.data;

            if (data.success) {
                let user = data.user;
                let html = data.html;

                let datatable = $('#banned-user-management-lists').DataTable();

                // İlgili satırı direkt olarak seç
                let rowToDelete = $('#banned-user-management-lists tbody tr').filter(function () {
                    return $(this).data('id') == user.id;
                });

                if (!empty(rowToDelete)) {
                    // DataTable referansı ile satırı sil
                    let row = datatable.row(rowToDelete);
                    row.remove().draw(false);

                    bannedRestoreTableAdd(user, html);
                }
            }

            $('#banned-restore-user-management-modal').modal('hide');

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

            $('#banned-restore-user-management-modal').modal('hide');

            waitHideAndButtonActive();
        }
    });

    function bannedRestoreTableAdd(user, html) {
        let datatable = $('#user-management-lists').DataTable();

        let buttons = `<button type="button" class="read-active-view-btn btn btn-icon btn-bg-light btn-light-warning btn-sm me-1">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1 mb-1">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button type="button" class="password-btn btn btn-icon btn-bg-light btn-light-success btn-sm me-1 mb-1">
                            <i class="fa-solid fa-key"></i>
                        </button>
                        <button type="button" class="permission-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1 mb-1">
                            <i class="fa-solid fa-shield-halved"></i>
                        </button>
                        <button type="button" class="ban-btn btn btn-icon btn-bg-light btn-light-dark btn-sm me-1 mb-1">
                            <i class="fa-solid fa-ban"></i>
                        </button>
                        <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1 mb-1">
                            <i class="fa-solid fa-trash"></i>
                        </button>`;

        let name = `${(user.name ?? '')}`;

        let email = `${(user.email ?? '')}`;

        let phone = `${(user.phone ?? '')}`;

        let roleHtml = `${(html ?? '')}`;

        // Yeni satırı ekle ve referans al
        let rowNode = datatable.row.add([
            buttons,
            name,
            email,
            phone,
            roleHtml,
        ]).draw(false).node(); // Tabloyu güncelle ve DOM düğümünü al

        // Eklenen satıra 'data-id' niteliği ekle
        $(rowNode).attr('data-id', user.id ?? '');

        datatable.order([1, 'asc']).draw(false);
    }
});
