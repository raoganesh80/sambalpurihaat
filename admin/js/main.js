
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