"use strict";

// Class definition
var BlogCategoryList = function () {
    var datatable;
    var table;

    // Private functions
    var initBlogCategoryList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[5, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 1 },
                { orderable: false, targets: 3 },
                { orderable: false, targets: 4 },
            ],
            'initComplete': function () {
                $(".dataTables_length select").selectpicker({
                    dropdownParent: $(".dataTables_length"),
                });
            }
        });

        $(".dataTables_length .form-select").removeClass('form-select');
    }

    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-blog-category-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#blog-category-lists');

            if (!table) {
                return;
            }

            initBlogCategoryList();
            handleSearchDatatable();
        }
    }
}();

var BlogCategoryTrashedList = function () {
    var datatable;
    var table;

    // Private functions
    var BlogCategoryTrashedList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[5, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 1 },
                { orderable: false, targets: 3 },
                { orderable: false, targets: 4 },
            ],
            'initComplete': function () {
                $(".dataTables_length select").selectpicker({
                    dropdownParent: $(".dataTables_length"),
                });
            }
        });

        $(".dataTables_length .form-select").removeClass('form-select');
    }

    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-blog-category-trashed-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#trashed-blog-category-lists');

            if (!table) {
                return;
            }

            BlogCategoryTrashedList();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    BlogCategoryList.init();
    BlogCategoryTrashedList.init();
});
