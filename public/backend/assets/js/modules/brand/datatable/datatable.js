"use strict";

// Class definition
var BrandList = function () {
    var datatable;
    var table;

    // Private functions
    var initBrandList = function () {
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
        const filterSearch = document.querySelector('[data-kt-brand-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#brand-lists');

            if (!table) {
                return;
            }

            initBrandList();
            handleSearchDatatable();
        }
    }
}();

var BrandTrashedList = function () {
    var datatable;
    var table;

    // Private functions
    var initBrandTrashedList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[4, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 1 },
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
        const filterSearch = document.querySelector('[data-kt-brand-trashed-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#trashed-brand-lists');

            if (!table) {
                return;
            }

            initBrandTrashedList();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    BrandList.init();
    BrandTrashedList.init();
});
