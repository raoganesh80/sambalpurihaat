
$(document).ready(function() { 
    
    $(".main-category-item").click(function() { 
        var id = $(this).attr("id");
        $("li.list-group-item.active").removeClass("active");
        $(this).addClass("active");

        $("#subcategoryform").attr("action","modals/addSubCategory.php?id="+id);
        $("#sub-category-list").empty();
        $.ajax({
            url: "modals/getSubCategory.php",
            method: "GET",
            data: { "id": id },
            success: function (response) {
                var subCategory = JSON.parse(response);
                if(subCategory['error']===undefined){
                    $.each(subCategory, function (key, value) {
                        $("#sub-category-list").append(`
                        <li id="${value['id']}" class="sub-category-item list-group-item"><span id="subname${value['id']}" class="float-left mt-3">${value["category_name"]}</span><div class="float-right"><button title="Rename" type="button" class="btn m-2 btn-outline-light" data-toggle="modal" onclick="renameSubCategory(${value['id']});"><i class="fa fa-edit"></i></button><button title="Delete" type="button" class="btn m-2 btn-outline-light" data-toggle="modal" onclick="deleteSubCategory(${value['id']});"><i class="fa fa-trash" aria-hidden="true"></i></button></div></li>
                        `);
                    });

                    $(".sub-category-item").click(function () {
                        var sid = $(this).attr("id");
                        $("li.sub-category-item.active").removeClass("active");
                        $(this).addClass("active");

                        $("#categoryform").attr("action", "modals/addCategory.php?id=" + sid);
                        $("#category-list").empty();
                        $.ajax({
                            url: "modals/getCategory.php",
                            method: "GET",
                            data: { "id": sid },
                            success: function (response) {
                                var Categories = JSON.parse(response);
                                if (Categories['error'] === undefined) {
                                    $.each(Categories, function (key, value) {
                                        $("#category-list").append(`
                                            <li id="${value['id']}" class="category-item list-group-item"><span id="name${value['id']}" class="float-left mt-3">${value["category_name"]}</span><div class="float-right"><button title="Rename" type="button" class="btn m-2 btn-outline-light" data-toggle="modal" onclick="renameCategory(${value['id']});"><i class="fa fa-edit"></i></button><button title="Delete" type="button" class="btn m-2 btn-outline-light" data-toggle="modal" onclick="deleteCategory(${value['id']});"><i class="fa fa-trash" aria-hidden="true"></i></button></div></li>
                                        `);
                                    });
                                }
                            }
                        });
                    }); 
                }
            }
        });
    });
}); 



$(document).ready(function() { 
    $("#btnCategoryIcon").click(function(){
        $("#category_icon").trigger('click');
    });
    $("#category_icon").change(function (){
        var id = $(".main-category-item.active").attr("id");
        var property = this.files[0];
        var image_name=property.name;
        var image_extension=image_name.split('.').pop().toLowerCase();
        if(jQuery.inArray(image_extension,['jpg','jpeg','png'])== -1){
            alert("Invalid Image File");
            return;
        }
        var image_size=property.size;
        if(image_size>2000000){
            alert("Image file size is very big");
        }else{

            if(property) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    
                    $(`#icon${id}`).attr("src", e.target.result);
                    $.ajax({
                        url: "modals/uploadimage.php",
                        method: "POST",
                        data: { "id": id ,"base64Image":(e.target.result).split(',')[1]},
                        success: function (response) {
                            alert(response);
                        }
                    });
                    console.log(e);
                    
                }
                reader.readAsDataURL(property);
            }
        }
    });
});

function renameMainCategory(id){
    //alert(id);

    $('#inputCategoryName').val($(`#name${id}`).text());
    $('#ChangeNameModal').modal('show');
    $('#ModalLabel').html('Rename Main Category');
    $('#inputCategoryName').val($(`#name${id}`).text());
    $('#inputID').val(id);
    $('#rename-category-form').attr("action","modals/renameMainCategory.php");
}

function deleteMainCategory(id){
    //alert(id);
    $('#delete-modal-dialog').modal('show');
    $('#btnYes').attr("href","modals/deleteMainCategory.php?id="+id); 
}

function renameSubCategory(id){
    //alert(id);
    $('#inputCategoryName').val($(`#subname${id}`).text());
    $('#ChangeNameModal').modal('show');
    $('#ModalLabel').html('Rename Sub Category');
    $('#inputID').val(id);
    $('#rename-category-form').attr("action","modals/renameSubCategory.php");
}

function deleteSubCategory(id){
    //alert(id);
    $('#delete-modal-dialog').modal('show');
    $('#btnYes').attr("href","modals/deleteSubCategory.php?id="+id); 
}

function renameCategory(id) {
    //alert(id);
    $('#inputCategoryName').val($(`#name${id}`).text());
    $('#ChangeNameModal').modal('show');
    $('#ModalLabel').html('Rename Category');
    $('#inputID').val(id);
    $('#rename-category-form').attr("action", "modals/renameCategory.php");
}

function deleteCategory(id) {
    //alert(id);
    $('#delete-modal-dialog').modal('show');
    $('#btnYes').attr("href", "modals/deleteCategory.php?id=" + id);
}