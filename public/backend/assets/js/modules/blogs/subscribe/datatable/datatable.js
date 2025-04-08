"use strict";

// Class definition
var BlogSubscribeList = function () {
    var datatable;
    var table;

    // Private functions
    var initBlogSubscribeList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[5, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 2 },
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
        const filterSearch = document.querySelector('[data-kt-blog-subscribe-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#blog-subscribe-lists');

            if (!table) {
                return;
            }

            initBlogSubscribeList();
            handleSearchDatatable();
        }
    }
}();

var BlogSubscribeTrashedList = function () {
    var datatable;
    var table;

    // Private functions
    var BlogSubscribeTrashedList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[5, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 2 },
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
        const filterSearch = document.querySelector('[data-kt-blog-subscribe-trashed-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#trashed-blog-subscribe-lists');

            if (!table) {
                return;
            }

            BlogSubscribeTrashedList();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    BlogSubscribeList.init();
    BlogSubscribeTrashedList.init();
});
