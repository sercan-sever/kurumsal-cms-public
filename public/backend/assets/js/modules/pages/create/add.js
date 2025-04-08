$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#add-page-modal', function () {
        $('#add-page-modal #add-page-modal-scroll').empty();
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
                let page = data.page;
                let title = data.title;
                let topPage = data.topPage;
                let isTopPage = data.isTopPage;
                let menuHtml = data.menu;
                let statusHtml = data.status;
                let breadcrumbHtml = data.breadcrumb;

                tableAdd(page, title, topPage, isTopPage, menuHtml, statusHtml, breadcrumbHtml);

                $('#add-page-modal').modal('hide');
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


    function tableAdd(page, title, topPageHtml, isTopPageHtml, menuHtml, statusHtml, breadcrumbHtml) {
        let datatable = $('#page-lists').DataTable();

        let buttons = `<button type="button" class="read-view-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        ${!isTopPageHtml ? `<button type="button" class="page-builder-btn btn btn-icon btn-bg-light btn-light-success btn-sm me-1">
                            <i class="fa-solid fa-screwdriver-wrench"></i>
                        </button>` : ''}
                        <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                            <i class="fa-solid fa-trash"></i>
                        </button>`;

        let contentTitle = `${(title ?? '')}`;

        let topPage = `${(topPageHtml ?? '')}`;

        let menu = `${(menuHtml ?? '')}`;

        let status = `${(statusHtml ?? '')}`;

        let breadcrumb = `${(breadcrumbHtml ?? '')}`;

        let sorting = `${(page.sorting ?? '1')}`;

        // Yeni satırı ekle ve referans al
        let rowNode = datatable.row.add([
            buttons,
            contentTitle,
            topPage,
            menu,
            status,
            breadcrumb,
            sorting,
        ]).draw(false).node(); // Tabloyu güncelle ve DOM düğümünü al

        // Eklenen satıra 'data-id' niteliği ekle
        $(rowNode).attr('data-id', page.id ?? '');

        datatable.order([6, 'asc']).draw(false);
    }
});
