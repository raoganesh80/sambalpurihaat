$(document).ready(function () {
    $("#order-table").DataTable({
        "order": [[3, "desc"]]
    });

    $("#product-table").DataTable({
        "order": [[4, "desc"]]
    });

    $("#message-table").DataTable({
        "order": [[3, "desc"]]
    });

    $("#vendorusers-table").DataTable({
        "order": [[5, "desc"]]
    });

    $("#vendorproducts-table").DataTable({
        "order": [[5, "desc"]]
    });

    $("#users-table").DataTable({
        "order": [[6, "desc"]]
    });
});