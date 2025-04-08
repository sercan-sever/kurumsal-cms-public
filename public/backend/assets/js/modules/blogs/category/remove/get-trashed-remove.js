$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Kapandığı Anda Form İçerisini Temizlemeye Yarar
    $(document).on('hidden.bs.modal', '#trashed-remove-blog-category-modal', function () {
        $('#trashed-remove-blog-category-modal .trashed-remove-blog-category-delete-btn').data('id', '');
    });

    $(document).on('click', '.trashed-remove-btn', async function (e) {
        e.preventDefault();

        let id = $(this).closest('tr').data('id');

        if (empty(id)) {

            showSwal('info', 'Silinen Blog Kategori Bulunamadı Sayfayı Yenileyerek Tekrar Deneyiniz !!!', 'center');

            return;
        }

        $('#trashed-remove-blog-category-modal .trashed-remove-blog-category-delete-btn').data('id', id);
        $('#trashed-remove-blog-category-modal').modal('show');
    });
});
