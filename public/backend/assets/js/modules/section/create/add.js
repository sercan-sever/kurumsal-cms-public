$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
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
                let section = data.section;
                let category = data.category;
                let image = data.image;
                let statusHtml = data.status;

                tableAdd(section, category, image, statusHtml);

                $('#add-section-content-modal').modal('hide');
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


    function tableAdd(section, category, image, statusHtml) {
        let datatable = $('#section-lists').DataTable();

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

        let contentTitle = `${(section.title ?? '')}`;

        let categoryName = `${(category ?? '')}`;

        let status = `${(statusHtml ?? '')}`;

        let sorting = `${(section.sorting ?? '1')}`;

        // Yeni satırı ekle ve referans al
        let rowNode = datatable.row.add([
            buttons,
            imageHtml,
            contentTitle,
            categoryName,
            status,
            sorting,
        ]).draw(false).node(); // Tabloyu güncelle ve DOM düğümünü al

        // Eklenen satıra 'data-id' niteliği ekle
        $(rowNode).attr('data-id', section.id ?? '');

        datatable.order([5, 'asc']).draw(false);
    }
});
