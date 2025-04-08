$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    /* GET USER BANNED DATA */
    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#ban-user-management-modal', function () {
        $('#ban-user-management-modal .user-management-ban-btn').data('id', '');
        $('#ban-user-management-modal #banned-description').val('');
    });

    $(document).on('click', '.ban-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Yetkili Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/user-managements/get-by-active-authorize-user', { id: id });

            const data = response.data;

            if (data.success) {

                let user = data.user;

                $('#ban-user-management-modal .user-management-ban-btn').data('id', user.id ?? '');

                $('#ban-user-management-modal').modal('show');
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


    /* GET USER BANNED RESTORE DATA */
    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#banned-restore-user-management-modal', function () {
        $('#banned-restore-user-management-modal .banned-restore-user-management-delete-btn').data('id', '');
    });

    $(document).on('click', '.banned-restore-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Yetkili Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/user-managements/get-by-user-banned', { id: id });

            const data = response.data;

            if (data.success) {

                let user = data.user;

                $('#banned-restore-user-management-modal .banned-restore-user-management-delete-btn').data('id', user.id ?? '');

                $('#banned-restore-user-management-modal').modal('show');
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


    /* GET USER PERMISSION DATA */
    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#permission-user-management-modal', function () {
        $('#permission-update-form').trigger('reset');
    });

    $(document).on('click', '.permission-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Yetkili Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/user-managements/get-by-active-authorize-user', { id: id });

            const data = response.data;

            if (data.success) {

                let user = data.user;
                let roles = data.roles;
                let permissions = data.permissions;

                $('#update-permission-id').val(user.id);

                // Tüm checkbox'ları sıfırla
                $('#permission-user-management-modal input[type="checkbox"]').prop('checked', false);


                let isRole = false;

                $.each(roles, function (index, role) {
                    const roleName = role.name;
                    let checkbox = $(`#permission-user-management-modal input[type="checkbox"][name="${roleName}"]#update-admin`);

                    if (checkbox.length > 0) {
                        isRole = true;

                        checkbox.prop('checked', true);
                    }
                });


                if (!isRole) {
                    $.each(permissions, function (index, permission) {
                        const permissionName = permission.name;
                        let checkbox = $(`#permission-user-management-modal input[type="checkbox"][value="${permissionName}"]`);

                        if (checkbox.length > 0) {
                            checkbox.prop('checked', true);
                        }
                    });
                }


                $('#permission-user-management-modal').modal('show');
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


    /* GET USER DELETED DATA */
    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#delete-user-management-modal', function () {
        $('#delete-user-management-modal .user-management-delete-btn').data('id', '');
    });

    $(document).on('click', '.delete-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Yetkili Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/user-managements/get-by-active-authorize-user', { id: id });

            const data = response.data;

            if (data.success) {

                let user = data.user;

                $('#delete-user-management-modal .user-management-delete-btn').data('id', user.id ?? '');

                $('#delete-user-management-modal').modal('show');
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


    /* GET USER TRASHED RESTORE DATA */
    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#trashed-restore-user-management-modal', function () {
        $('#trashed-restore-user-management-modal .trashed-restore-user-management-delete-btn').data('id', '');
    });

    $(document).on('click', '.trashed-restore-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Yetkili Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post('/lk-admin/user-managements/get-by-user-delete', { id: id });

            const data = response.data;

            if (data.success) {

                let user = data.user;

                $('#trashed-restore-user-management-modal .trashed-restore-user-management-delete-btn').data('id', user.id ?? '');

                $('#trashed-restore-user-management-modal').modal('show');
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
