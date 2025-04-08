"use strict";

// Class definition
var KTUserManagementList = function () {
    var datatable;
    var table;

    // Private functions
    var initUserManagementList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[1, 'asc']],
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
        const filterSearch = document.querySelector('[data-kt-user-management-table-filter="search"]');
        if (!filterSearch) {
            return;
        }

        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Select all handler
    var handleSelectAll = () => {
        const element = document.getElementById('add-user-management-modal');

        if (!element) {
            return;
        }

        const form = element.querySelector('#create-form');

        if (!form) {
            return;
        }

        const selectAll = form.querySelector('#user-management-roles-select-all');
        const allCheckboxes = form.querySelectorAll('[type="checkbox"]');

        if (!selectAll) {
            return;
        }

        const excludedIds = ['add-admin', 'email-send'];

        selectAll.addEventListener('change', e => {
            allCheckboxes.forEach(c => {
                if (!excludedIds.includes(c.id)) {
                    c.checked = e.target.checked;
                }
            });
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#user-management-lists');

            if (!table) {
                return;
            }

            initUserManagementList();
            handleSearchDatatable();
            handleSelectAll();
        }
    }
}();

var KTUserManagementTrashedList = function () {
    var datatable;
    var table;

    // Private functions
    var initUserManagementList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[2, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 1 },
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
        const filterSearch = document.querySelector('[data-kt-customer-trashed-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#trashed-user-management-lists');

            if (!table) {
                return;
            }

            initUserManagementList();
            handleSearchDatatable();
        }
    }
}();

var KTUserManagementBannedList = function () {
    var datatable;
    var table;

    // Private functions
    var initUserManagementList = function () {
        datatable = $(table).DataTable({
            "info": false,
            'order': [[2, 'asc']],
            'columnDefs': [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 1 },
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
        const filterSearch = document.querySelector('[data-kt-customer-banned-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Public methods
    return {
        init: function () {
            table = document.querySelector('#banned-user-management-lists');

            if (!table) {
                return;
            }

            initUserManagementList();
            handleSearchDatatable();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUserManagementList.init();
    KTUserManagementTrashedList.init();
    KTUserManagementBannedList.init();
});
