$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#togglePassword').on('click', function () {
        var passwordInput = $('#passwordInput');
        var eyeSlashIcon = $(this).find('.bi-eye-slash');
        var eyeIcon = $(this).find('.bi-eye');

        // Şifreyi görünür yapma/gizleme işlemi
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            eyeSlashIcon.addClass('d-none');
            eyeIcon.removeClass('d-none');
        } else {
            passwordInput.attr('type', 'password');
            eyeSlashIcon.removeClass('d-none');
            eyeIcon.addClass('d-none');
        }
    });


    $("#login-form").on("submit", async function (e) {
        e.preventDefault();

        let url = $(this).data('action');
        waitShowAndButtonPassive();

        try {
            // Axios ile istek at
            const response = await axios.post(url, new FormData(this));

            const data = response.data;

            if (data.success) {
                // Başarılıysa yönlendir
                $(location).attr('href', data.url);
            } else {
                waitHideAndButtonActive();
                resetForm();
            }

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
                showToast(error.message || 'An unexpected error occurred.', "#DC3546");
            }

            waitHideAndButtonActive();
            resetForm();
        }
    });

    function resetForm() {
        $('#login-form').trigger('reset');
        grecaptcha.reset();
    }
});
