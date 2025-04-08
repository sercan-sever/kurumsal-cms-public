$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '#add-blog', function () {
        let maxOrder = getMaxOrderValue();
        $('#sorting').val(maxOrder + 1);
    });

    $(document).on('input', '#sorting', function () {
        let value = parseInt($(this).val(), 10);
        if (value <= 1 || isNaN(value)) {
            $(this).val(1);
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#add-blog-modal', function () {
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
                let blog = data.blog;
                let content = data.blogContent;
                let image = data.image;
                let publishedAt = data.publishedAt;
                let statusHtml = data.status;
                let commentStatusHtml = data.commentStatus;

                tableAdd(blog, content, image, publishedAt, statusHtml, commentStatusHtml);

                $('#add-blog-modal').modal('hide');

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


    function resetAddForm() {
        $('#create-form').trigger('reset');

        // image değeri siliniyor
        var $cancelElement = $('[data-kt-image-input-action="cancel"]');
        $cancelElement.trigger('click');

        // açıklama sıfırla
        $('.cst-description').each(function () {
            var editorId = $(this).attr('id');
            if (CKEDITOR.instances[editorId]) {
                CKEDITOR.instances[editorId].setData('');
            }
        });

        // select2 sıfırlama
        $('#categories').val(null).trigger('change');
        $('#tags').val(null).trigger('change');

        // flatpickr tarih sıfırlama
        $('#date').each(function () {
            const localeCode = $(this).data('locale') || 'default'; // Locale bilgisi
            const minDate = $(this).data('min-date') || new Date(); // Min date (şu anki tarih varsayılan)
            const maxDate = $(this).data('max-date') || new Date(new Date().setDate(new Date().getDate() + 7)); // 7 gün sonrası varsayılan

            $(this).flatpickr({
                locale: flatpickr.l10ns[localeCode] || flatpickr.l10ns.default,
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: minDate,
                maxDate: maxDate,
                disableMobile: true,
            });
        });

    }

    function tableAdd(blog, content, image, publishedAt, statusHtml, commentStatusHtml) {
        let datatable = $('#blog-lists').DataTable();

        let buttons = `<button type="button" class="read-view-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                            <i class="fa-solid fa-trash"></i>
                        </button>`;

        let imageHtml = `${(image ?? '')}`;

        let title = `${(content.title ?? '')}`;

        let date = `${(publishedAt ?? '')}`;

        let status = `${(statusHtml ?? '')}`;

        let comment = `${(commentStatusHtml ?? '')}`;

        let sorting = `${(blog.sorting ?? '1')}`;

        // Yeni satırı ekle ve referans al
        let rowNode = datatable.row.add([
            buttons,
            imageHtml,
            title,
            date,
            status,
            comment,
            sorting,
        ]).draw(false).node(); // Tabloyu güncelle ve DOM düğümünü al

        // Eklenen satıra 'data-id' niteliği ekle
        $(rowNode).attr('data-id', blog.id);

        datatable.order([6, 'desc']).draw(false);
    }

    function getMaxOrderValue() {
        let table = $('#blog-lists').DataTable();

        let maxOrder = 0;
        let data = table.column(6, {
            order: 'index'
        }).data().toArray();

        if (!empty(data)) {
            maxOrder = Math.max(...data.map(Number));
        }

        return (maxOrder <= 0) ? 0 : maxOrder;
    }
});
