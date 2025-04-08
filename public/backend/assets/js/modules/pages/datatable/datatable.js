"use strict";

// Class definition
var PageList = function () {
    var datatable;
    var table;

    // Private functions
    var initPageList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[6, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 2 },
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
        const filterSearch = document.querySelector('[data-kt-page-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#page-lists');

            if (!table) {
                return;
            }

            initPageList();
            handleSearchDatatable();
        }
    }
}();

var PageTrashedList = function () {
    var datatable;
    var table;

    // Private functions
    var initPageTrashedList = function () {
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
        const filterSearch = document.querySelector('[data-kt-page-trashed-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#trashed-page-lists');

            if (!table) {
                return;
            }

            initPageTrashedList();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    PageList.init();
    PageTrashedList.init();
});
