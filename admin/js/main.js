
$('#createUser-form').submit(function () {

    var username = $('#username').val();
    var email = $('#email').val();
    var mobile_no = $('#mobile_no').val();

    
    $.ajax({
        url: "modals/createNewVendorUser.php",
        method: "POST",
        data: { "username": username, "email": email, "mobile_no" : mobile_no },
        success: function (response) {
            if(response!='ok')
                alert(response);
            location.reload();
        }
    });

});

function deleteVendorUser(uid) {
    $('#modal-dialog').modal('show');
    $('#btnYes').attr("href", "modals/deleteVendorUser.php?uid=" + uid);
}


$('#create-list1-form').submit(function () {
    console.log('form1');
    var list_name = $('#inputName1').val();
    var list_items = $('#inputItems1').val();

    $.ajax({
        url: "modals/saveProductList.php",
        method: "POST",
        data: {"id":1, "list_name": list_name, "list_items": list_items },
        success: function (response) {
            alert(response);
            location.reload();
        }
    });
});

$('#create-list2-form').submit(function () {
    console.log('form2');
    var list_name = $('#inputName2').val();
    var list_items = $('#inputItems2').val();
    $.ajax({
        url: "modals/saveProductList.php",
        method: "POST",
        data: { "id": 2, "list_name": list_name, "list_items": list_items },
        success: function (response) {
            alert(response);
            location.reload();
        }
    });
});

$('#create-list3-form').submit(function () {
    console.log('form3');
    var list_name = $('#inputName3').val();
    var list_items = $('#inputItems3').val();
    $.ajax({
        url: "modals/saveProductList.php",
        method: "POST",
        data: { "id": 3, "list_name": list_name, "list_items": list_items },
        success: function (response) {
            alert(response);
            location.reload();
        }
    });
});