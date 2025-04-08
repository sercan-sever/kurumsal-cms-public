$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '#add-business-processes', function () {
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
    $(document).on('hidden.bs.modal', '#add-business-processes-modal', function () {
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
                let businessProcesses = data.businessProcesses;
                let content = data.content;
                let statusHtml = data.status;

                tableAdd(businessProcesses, content, statusHtml);

                $('#add-business-processes-modal').modal('hide');

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
    }


    function tableAdd(businessProcesses, content, statusHtml) {
        let datatable = $('#business-processes-lists').DataTable();

        let buttons = `<button type="button" class="read-view-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="edit-btn btn btn-icon btn-bg-light btn-light-info btn-sm me-1">
                            <i class="fa-solid fa-pencil"></i>
                        </button>
                        <button type="button" class="delete-btn btn btn-icon btn-bg-light btn-light-danger btn-sm me-1">
                            <i class="fa-solid fa-trash"></i>
                        </button>`;

        let header = `${(content.header ?? '')}`;

        let title = `${(content.title ?? '')}`;

        let description = `${(content.description ?? '')}`;

        let status = `${(statusHtml ?? '')}`;

        let sorting = `${(businessProcesses.sorting ?? '1')}`;

        // Yeni satırı ekle ve referans al
        let rowNode = datatable.row.add([
            buttons,
            header,
            title,
            description,
            status,
            sorting,
        ]).draw(false).node(); // Tabloyu güncelle ve DOM düğümünü al

        // Eklenen satıra 'data-id' niteliği ekle
        $(rowNode).attr('data-id', businessProcesses.id ?? '');

        datatable.order([5, 'asc']).draw(false);
    }


    function getMaxOrderValue() {
        let table = $('#business-processes-lists').DataTable();

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
