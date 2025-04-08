"use strict";

// Class definition
var BlogCommentList = function () {
    var datatable;
    var table;

    // Private functions
    var initBlogCommentList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[8, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 2 },
                { orderable: false, targets: 3 },
                { orderable: false, targets: 4 },
                { orderable: false, targets: 5 },
                { orderable: false, targets: 6 },
                { orderable: false, targets: 7 },
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
        const filterSearch = document.querySelector('[data-kt-blog-comment-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#blog-comment-lists');

            if (!table) {
                return;
            }

            initBlogCommentList();
            handleSearchDatatable();
        }
    }
}();

var BlogCommentConfirmList = function () {
    var datatable;
    var table;

    // Private functions
    var BlogCommentConfirmList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[8, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 2 },
                { orderable: false, targets: 3 },
                { orderable: false, targets: 4 },
                { orderable: false, targets: 5 },
                { orderable: false, targets: 6 },
                { orderable: false, targets: 7 },
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
        const filterSearch = document.querySelector('[data-kt-blog-comment-confirm-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#confirm-blog-comment-lists');

            if (!table) {
                return;
            }

            BlogCommentConfirmList();
            handleSearchDatatable();
        }
    }
}();

var BlogCommentTrashedList = function () {
    var datatable;
    var table;

    // Private functions
    var BlogCommentTrashedList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[9, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 2 },
                { orderable: false, targets: 3 },
                { orderable: false, targets: 4 },
                { orderable: false, targets: 5 },
                { orderable: false, targets: 6 },
                { orderable: false, targets: 7 },
                { orderable: false, targets: 8 },
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
        const filterSearch = document.querySelector('[data-kt-blog-comment-trashed-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#trashed-blog-comment-lists');

            if (!table) {
                return;
            }

            BlogCommentTrashedList();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    BlogCommentList.init();
    BlogCommentTrashedList.init();
    BlogCommentConfirmList.init();
});
