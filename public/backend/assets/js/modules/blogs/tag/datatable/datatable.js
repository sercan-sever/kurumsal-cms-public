"use strict";

// Class definition
var BlogTagList = function () {
    var datatable;
    var table;

    // Private functions
    var initBlogTagList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[4, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 2 },
                { orderable: false, targets: 3 },
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
        const filterSearch = document.querySelector('[data-kt-blog-tag-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#blog-tag-lists');

            if (!table) {
                return;
            }

            initBlogTagList();
            handleSearchDatatable();
        }
    }
}();

var BlogTagTrashedList = function () {
    var datatable;
    var table;

    // Private functions
    var BlogTagTrashedList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[4, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 2 },
                { orderable: false, targets: 3 },
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
        const filterSearch = document.querySelector('[data-kt-blog-tag-trashed-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#trashed-blog-tag-lists');

            if (!table) {
                return;
            }

            BlogTagTrashedList();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    BlogTagList.init();
    BlogTagTrashedList.init();
});
