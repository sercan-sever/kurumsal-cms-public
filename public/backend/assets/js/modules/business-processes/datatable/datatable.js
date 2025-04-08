"use strict";

// Class definition
var BusinessProcessesList = function () {
    var datatable;
    var table;

    // Private functions
    var initBusinessProcessesList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[5, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 1 },
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
        const filterSearch = document.querySelector('[data-kt-business-processes-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#business-processes-lists');

            if (!table) {
                return;
            }

            initBusinessProcessesList();
            handleSearchDatatable();
        }
    }
}();

var BusinessProcessesTrashedList = function () {
    var datatable;
    var table;

    // Private functions
    var BusinessProcessesTrashedList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[4, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 1 },
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
        const filterSearch = document.querySelector('[data-kt-business-processes-trashed-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#trashed-business-processes-lists');

            if (!table) {
                return;
            }

            BusinessProcessesTrashedList();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    BusinessProcessesList.init();
    BusinessProcessesTrashedList.init();
});
