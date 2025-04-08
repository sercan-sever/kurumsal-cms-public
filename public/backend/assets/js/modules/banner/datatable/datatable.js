"use strict";

// Class definition
var BannerList = function () {
    var datatable;
    var table;

    // Private functions
    var initBannerList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[4, 'asc']],
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
        const filterSearch = document.querySelector('[data-kt-banner-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#banner-lists');

            if (!table) {
                return;
            }

            initBannerList();
            handleSearchDatatable();
        }
    }
}();

var BannerTrashedList = function () {
    var datatable;
    var table;

    // Private functions
    var initBannerTrashedList = function () {
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
        const filterSearch = document.querySelector('[data-kt-banner-trashed-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#trashed-banner-lists');

            if (!table) {
                return;
            }

            initBannerTrashedList();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    BannerList.init();
    BannerTrashedList.init();
});
