"use strict";

// Class definition
var SectionList = function () {
    var datatable;
    var table;

    // Private functions
    var initSectionList = function () {
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
        const filterSearch = document.querySelector('[data-kt-section-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#section-lists');

            if (!table) {
                return;
            }

            initSectionList();
            handleSearchDatatable();
        }
    }
}();

var SectionTrashedList = function () {
    var datatable;
    var table;

    // Private functions
    var initSectionTrashedList = function () {
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
        const filterSearch = document.querySelector('[data-kt-section-trashed-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#trashed-section-lists');

            if (!table) {
                return;
            }

            initSectionTrashedList();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    SectionList.init();
    SectionTrashedList.init();
});
