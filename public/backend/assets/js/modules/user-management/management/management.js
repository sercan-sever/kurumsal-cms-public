
// EKLEME
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
$('#toggleConfirmPassword').on('click', function () {
    var passwordInput = $('#confirmPasswordInput');
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


// GÜNCELLEME
$('#toggleUpdatePassword').on('click', function () {
    var passwordInput = $('#updatePasswordInput');
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
$('#toggleUpdateConfirmPassword').on('click', function () {
    var passwordInput = $('#updateConfirmPasswordInput');
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

// İZİN YÖNETİMİ
$(document).on('click', '#permission-user-management-modal #user-management-permission-roles-select-all', function () {
    const element = $('#permission-user-management-modal');
    const form = element.find('#update-permission-form');
    const selectAll = form.find('#user-management-permission-roles-select-all');
    const allCheckboxes = form.find('[type="checkbox"]');

    // IDs to exclude
    const excludedIds = ['update-admin'];

    // Handle check state
    selectAll.on('change', function (e) {
        allCheckboxes.each(function () {
            const checkbox = $(this);
            if (!excludedIds.includes(checkbox.attr('id'))) {
                checkbox.prop('checked', e.target.checked);
            }
        });
    });
});
