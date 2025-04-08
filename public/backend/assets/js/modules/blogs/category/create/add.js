$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '#add-blog-category', function () {
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
    $(document).on('hidden.bs.modal', '#add-blog-category-modal', function () {
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
                let blogCategory = data.blogCategory;
                let content = data.blogCategoryContent;
                let categoryCount = data.blogCategoryCount;
                let image = data.image;
                let statusHtml = data.status;

                tableAdd(blogCategory, content, categoryCount, image, statusHtml);

                $('#add-blog-category-modal').modal('hide');

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
    }

    function tableAdd(blogCategory, content, categoryCount, image, statusHtml) {
        let datatable = $('#blog-category-lists').DataTable();

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

        let usage = `${(categoryCount ?? '0')}`;

        let status = `${(statusHtml ?? '')}`;

        let sorting = `${(blogCategory.sorting ?? '1')}`;

        // Yeni satırı ekle ve referans al
        let rowNode = datatable.row.add([
            buttons,
            imageHtml,
            title,
            usage,
            status,
            sorting,
        ]).draw(false).node(); // Tabloyu güncelle ve DOM düğümünü al

        // Eklenen satıra 'data-id' niteliği ekle
        $(rowNode).attr('data-id', blogCategory.id ?? '');

        datatable.order([5, 'asc']).draw(false);
    }

    function getMaxOrderValue() {
        let table = $('#blog-category-lists').DataTable();

        let maxOrder = 0;
        let data = table.column(5, {
            order: 'index'
        }).data().toArray();

        if (!empty(data)) {
            maxOrder = Math.max(...data.map(Number));
        }

        return (maxOrder <= 0) ? 0 : maxOrder;
    }
});
