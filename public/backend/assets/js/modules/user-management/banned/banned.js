$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on("click", ".user-management-ban-btn", async function (e) {
        e.preventDefault();

        let id = $(this).data('id');
        let banned_description = $('#banned-description').val();

        if (empty(id)) {

            showSwal('info', 'Yetkili Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        if (empty(banned_description)) {

            showSwal('info', 'Banlama Nedeni Zorunludur !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/user-managements/banned', { id: id, banned_description: banned_description });

            const data = response.data;

            if (data.success) {
                let user = data.user;
                let html = data.html;
                let image = data.image;

                let datatable = $('#user-management-lists').DataTable();

                // İlgili satırı direkt olarak seç
                let rowToDelete = $('#user-management-lists tbody tr').filter(function () {
                    return $(this).data('id') == user.id;
                });

                if (!empty(rowToDelete)) {
                    // DataTable referansı ile satırı sil
                    let row = datatable.row(rowToDelete);
                    row.remove().draw(false);

                    bannedTableAdd(user, image, html);
                }
            }

            $('#ban-user-management-modal').modal('hide');

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

            $('#ban-user-management-modal').modal('hide');

            waitHideAndButtonActive();
        }
    });

    function bannedTableAdd(user, image, html) {
        let datatable = $('#banned-user-management-lists').DataTable();

        let buttons = `<button type="button" class="read-banned-view-btn btn btn-icon btn-bg-light btn-light-warning btn-sm me-1">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="banned-restore-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                            <i class="fa-solid fa-rotate-right"></i>
                        </button>`;

        let img = `${(image ?? '')}`;

        let name = `${(user.name ?? '')}`;

        let bannedDescription = `${(user.banned_description ?? '')}`;

        let email = `${(user.email ?? '')}`;

        let roleHtml = `${(html ?? '')}`;

        // Yeni satırı ekle ve referans al
        let rowNode = datatable.row.add([
            buttons,
            img,
            name,
            bannedDescription,
            email,
            roleHtml,
        ]).draw(false).node(); // Tabloyu güncelle ve DOM düğümünü al

        // Eklenen satıra 'data-id' niteliği ekle
        $(rowNode).attr('data-id', user.id ?? '');

        datatable.order([2, 'asc']).draw(false);
    }
});
