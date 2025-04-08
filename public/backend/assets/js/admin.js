function waitShowAndButtonPassive() {
    $(':button').prop('disabled', true);
    $('#waitModel').show();
}

function waitHideAndButtonActive() {
    $(':button').prop('disabled', false);
    $('#waitModel').hide();
}

function toggleButtonLoading(button, isLoading) {
    let $indicatorLabel = button.find('.indicator-label');
    let $indicatorProgress = button.find('.indicator-progress');

    if (isLoading) {
        $indicatorLabel.hide();
        $indicatorProgress.show();
        button.prop('disabled', true);  // Butonu pasif yap
    } else {
        $indicatorLabel.show();
        $indicatorProgress.hide();
        button.prop('disabled', false); // Butonu aktif yap
    }
}

function showToast(message, backgroundColor) {
    Toastify({
        text: message,
        duration: 3000,
        close: true,
        stopOnFocus: true,
        className: "toast-with-progress", // Custom class
        gravity: "top",
        position: "right",
        class: "top-right",
        backgroundColor: backgroundColor,
    }).showToast();
}

function showSwal(status, message, position = "top-end") {
    Swal.fire({
        position: position,
        icon: status,
        title: message,
        showConfirmButton: false,
        timer: 1500
    });
}

function axioasDefaultCatch(response) {
    if (response && response.status === 422) {
        $.each(response.data.errors, function (key, value) {
            showToast(value[0], "#DC3546");
        });
    } else if (response.data.message) {
        showToast(response.data.message, "#DC3546");
    } else {
        showToast(error.message || 'An unexpected error occurred.', "#DC3546");
    }
}


function updateSelect2ClearButton() {
    const clearButton = $('.select2-selection__clear'); // Clear butonunu bul
    // Var olan butonu kaldır
    clearButton.empty();

    // Görsel butonu ekleyin
    const customButton = $(`
            <button class="custom-clear-button">
                <img src="//kurumsal-cms.test/backend/assets/media/select2-clear.png" alt="Clear">
            </button>
        `);

    // Clear butonunun içine kendi butonunuzu ekleyin
    clearButton.append(customButton);

    // Kendi butonunuza tıklama olayını ekleyin
    customButton.on('click', function (e) {
        e.stopPropagation(); // Olayın diğer öğelere yayılmasını önleyin
        $('#edit-parent-select').val(null).trigger('change'); // Seçimi temizle
    });
}


/**
 * @param {*} value Kontrol edilecek değer
 * @return {boolean} Değer boşsa true, değilse false döner.
 */
function empty(value) {
    // null veya undefined kontrolü
    if (value === null || value === undefined) {
        return true;
    }

    // false değer kontrolü
    if (value === false) {
        return true;
    }

    // Sayı ve string olarak 0 kontrolü
    if (typeof value === 'number') {
        return value <= 0; // 0 ve negatif sayılar boş kabul edilir
    }

    if (value === "0") {
        return true;
    }

    // Boş string kontrolü
    if (value === "") {
        return true;
    }

    // Array kontrolü (boş ise)
    if (Array.isArray(value) && value.length === 0) {
        return true;
    }

    // Object kontrolü (boş ise)
    if (typeof value === "object" && value !== null && Object.keys(value).length === 0) {
        return true;
    }

    return false;
}


$(document).ready(function () {
    $(document).on('show.bs.modal', '.modal', function () {
        let zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);

        setTimeout(() => {
            $('.modal-backdrop').css('z-index', zIndex - 1);
        }, 0);
    });

    $(document).on('hidden.bs.modal', '.modal', function () {
        if ($('.modal:visible').length > 0) {

            let zIndex = 1040 + (10 * ($('.modal:visible').length - 1));
            $('.modal-backdrop').css('z-index', zIndex - 1);
        } else {

            $('.modal-backdrop').remove();
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    let duration = 3000; // Süreyi al (ms cinsinden)
    let style = document.createElement("style");
    style.innerHTML = `
        .toast-with-progress::after {
            animation: progressBar ${duration / 1000}s linear forwards;
        }
    `;
    document.head.appendChild(style);
});
