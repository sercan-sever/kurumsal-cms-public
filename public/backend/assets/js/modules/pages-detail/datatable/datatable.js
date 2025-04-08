$(document).ready(function () {

    $(".draggable-zone").each(function () {
        new Sortable(this, {
            group: "shared",
            animation: 150,
            filter: ".empty-message",
            onEnd: function (evt) {
                updateIcons($(evt.item));
                updateOrder();
            }
        });
    });

    $('#search').on('keyup', function () {
        var value = $(this).val().toLowerCase();
        var items = $('.component-zone .draggable');
        var found = false;

        items.each(function () {
            var title = $(this).find('.text-gray-800').text().toLowerCase();
            if (title.indexOf(value) > -1) {
                $(this).show();
                found = true;
            } else {
                $(this).hide();
            }
        });

        if (!found) {
            if ($('.component-zone').find(".empty-message").length === 0) {
                $('.component-zone').append('<div class="empty-message text-center text-danger fw-bold py-3">Bölüm Bulunamadı</div>');
            }
        } else {
            checkComponenetEmptyState();
        }
    });


    $(document).on("click", ".btn-icon .fa-square-plus, .btn-icon .fa-trash-can", function () {
        let item = $(this).closest(".draggable");
        let targetContainer = getTargetContainer(item);

        $(this).removeClass("fa-square-plus fa-trash-can fs-1 fs-2");

        if (targetContainer.hasClass("page-detail-zone")) {
            $(this).addClass("fa-trash-can fs-2");
            $(this).closest(".btn").removeClass("btn-active-color-success").addClass("btn-active-color-danger");
        } else {
            $(this).addClass("fa-square-plus fs-1");
            $(this).closest(".btn").removeClass("btn-active-color-danger").addClass("btn-active-color-success");
        }

        targetContainer.append(item);
        checkPageDetailEmptyState();
        checkComponenetEmptyState();
        updateOrder();
    });

    $(document).on("mousedown", ".empty-message", function (e) {
        e.preventDefault();
        e.stopPropagation();
    });

    function getTargetContainer(item) {
        let parentZone = item.closest(".draggable-zone");

        if (parentZone.hasClass("component-zone")) {
            return $(".page-detail-zone");
        } else {
            return $(".component-zone");
        }
    }

    function updateIcons(item) {
        let targetContainer = getTargetContainer(item);
        let icon = item.find(".fa-square-plus, .fa-trash-can");

        if (!targetContainer.hasClass("page-detail-zone")) {
            icon.removeClass("fa-square-plus fs-1").addClass("fa-trash-can fs-2");
            icon.closest(".btn").removeClass("btn-active-color-success").addClass("btn-active-color-danger");
            checkPageDetailEmptyState();
            checkComponenetEmptyState();
        } else {
            icon.removeClass("fa-trash-can fs-2").addClass("fa-square-plus fs-1");
            icon.closest(".btn").removeClass("btn-active-color-danger").addClass("btn-active-color-success");
            checkPageDetailEmptyState();
            checkComponenetEmptyState();
        }
    }

    function updateOrder() {
        let order = [];
        $(".draggable-zone .draggable").each(function (index) {
            order.push({
                id: $(this).data("id"),
                position: index + 1,
                location: $(this).closest(".card-body").parent().find("h3, h2").text().trim()
            });
        });

        // console.log("Yeni Sıralama:", JSON.stringify(order));
    }

    function checkPageDetailEmptyState() {
        let pageDetailZone = $(".page-detail-zone");

        if (pageDetailZone.children(".draggable").length === 0) {
            if (pageDetailZone.find(".empty-message").length === 0) {
                pageDetailZone.append('<div class="empty-message text-center text-danger fw-bold py-3">Bölüm Bulunamadı</div>');
            }
        } else {
            pageDetailZone.find(".empty-message").remove();
        }
    }

    function checkComponenetEmptyState() {
        let pageDetailZone = $(".component-zone");

        if (pageDetailZone.children(".draggable").length === 0) {
            if (pageDetailZone.find(".empty-message").length === 0) {
                pageDetailZone.append('<div class="empty-message text-center text-danger fw-bold py-3">Bölüm Bulunamadı</div>');
            }
        } else {
            pageDetailZone.find(".empty-message").remove();
        }
    }

});

