"use strict";

// Class definition
var KTLanguageList = function () {
    var datatable;
    var table;

    // Private functions
    var initLanguageList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[6, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 1 },
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
        const filterSearch = document.querySelector('[data-kt-customer-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#language-lists');

            if (!table) {
                return;
            }

            initLanguageList();
            handleSearchDatatable();
        }
    }
}();

// Class definition
var KTLanguageTrashedList = function () {
    var datatable;
    var table;

    // Private functions
    var initLanguageList = function () {
        datatable = $(table).DataTable({
            "info": false,
           'order': [[2, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 1 },
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
        const filterSearch = document.querySelector('[data-kt-customer-trashed-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#trashed-language-lists');

            if (!table) {
                return;
            }

            initLanguageList();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTLanguageList.init();
    KTLanguageTrashedList.init();
});
