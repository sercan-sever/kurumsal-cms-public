$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#add-user-management-modal', function () {
        resetAddForm();
    });


    $(document).on("submit", "#create-form", async function (e) {
        e.preventDefault();

        let url = $(this).data('action');
        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post(url, new FormData(this));

            const data = response.data;

            if (data.success) {

                let user = data.user;
                let html = data.html;
                let isAdmin = data.isAdmin;

                tableAdd(user, html, isAdmin);

                if (data.isTrue) {
                    $('#show-avatar').attr('src', data.image);
                    $('#show-menu-avatar').attr('src', data.image);
                }

                $('#add-user-management-modal').modal('hide');

                resetAddForm();
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


    function tableAdd(user, html) {
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

    function resetAddForm() {
        $('#create-form').trigger('reset');

        // image değeri siliniyor
        var $cancelElement = $('#add-user-management-modal [data-kt-image-input-action="cancel"]');
        $cancelElement.trigger('click');
    }
});
