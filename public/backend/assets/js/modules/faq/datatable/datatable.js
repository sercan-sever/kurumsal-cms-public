"use strict";

// Class definition
var FaqList = function () {
    var datatable;
    var table;

    // Private functions
    var initFaqList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[3, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 2 },
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
        const filterSearch = document.querySelector('[data-kt-faq-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#faq-lists');

            if (!table) {
                return;
            }

            initFaqList();
            handleSearchDatatable();
        }
    }
}();

var FaqTrashedList = function () {
    var datatable;
    var table;

    // Private functions
    var FaqTrashedList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[3, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 2 },
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
        const filterSearch = document.querySelector('[data-kt-faq-trashed-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#trashed-faq-lists');

            if (!table) {
                return;
            }

            FaqTrashedList();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    FaqList.init();
    FaqTrashedList.init();
});
