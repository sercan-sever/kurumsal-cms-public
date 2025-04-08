"use strict";

// Class definition
var BlogList = function () {
    var datatable;
    var table;

    // Private functions
    var initBlogList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[6, 'desc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 1 },
                { orderable: false, targets: 3 },
                { orderable: false, targets: 4 },
                { orderable: false, targets: 5 },
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
        const filterSearch = document.querySelector('[data-kt-blog-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#blog-lists');

            if (!table) {
                return;
            }

            initBlogList();
            handleSearchDatatable();
        }
    }
}();

var BlogTrashedList = function () {
    var datatable;
    var table;

    // Private functions
    var BlogTrashedList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[5, 'desc']],
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
        const filterSearch = document.querySelector('[data-kt-blog-trashed-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#trashed-blog-lists');

            if (!table) {
                return;
            }

            BlogTrashedList();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    BlogList.init();
    BlogTrashedList.init();
});
