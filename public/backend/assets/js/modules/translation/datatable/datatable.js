"use strict";

// Class definition
var KTTranslationList = function () {
    var datatable;
    var table;

    // Private functions
    var initTranslationList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[1, 'asc']],
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

         $(".dataTables_length .dropdown").removeClass("form-select");
    }

    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-translation-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#translation-lists');

            if (!table) {
                return;
            }

            initTranslationList();
            handleSearchDatatable();
        }
    }
}();


// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTTranslationList.init();
});
