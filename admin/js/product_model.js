function changeImage(input) {
    $(input).trigger('click');
}
var imgCounter = 1;
function imageOrderSelect(self) {
    var text = document.getElementById('checkmark' + self.id);
    if (self.checked == true) {
        text.innerText = imgCounter;
        self.value = imgCounter;
        imgCounter++;
    } else {
        if (self.value < imgCounter)
            console.log(self.value);
        text.innerText = "";
        imgCounter--;
    }
}

function btnEdit(ctx, pid, vendor_id) {
    $('#productEditModel').modal('show');

    imgCounter = 1;
    $tr = $(ctx).closest('tr');
    var data = $tr.children('td').map(function () {
        return $(this).text();
    }).get();
    $('#inputTitle').val(data[1]);
    $('#inputVariants').empty();
    $.ajax({
        url: "modals/getVendorProductDetails.php",
        method: "GET",
        data: { "pid": pid },
        success: function (response) {
            var product = JSON.parse(response);
            console.log(product);
            $('#inputDescription').val(product.description);
        }
    });
    //$('#imageGroup').empty();
    $.ajax({
        url: "modals/vendorvariants.php",
        method: "GET",
        data: { "pid": pid, "vendor_id": vendor_id },
        success: function (response) {
            //console.log(response);
            var variants = JSON.parse(response);
            $.each(variants, function (key, value) {
                var count = 0;
                //console.log(value);
                $('#inputVariants').append(`<div class="form-row">
                            <div class="form-group col-5">
                            <label for="inputVariantTitle">Vairant Title</label>
                            <input type="text" class="form-control" id="inputtitle${key}" name="inputtitle${key}" value="${value.variant_name}" required>
                            </div>
                            <div class="form-group col-2">
                            <label for="inputQuantity">Quantity</label>
                            <input readonly  type="text" class="form-control" id="inputQuantity${key}" name="inputQuantity${key}" value="${value.current_quantity}" required>
                            </div>
                            <div class="form-group col-3">
                            <label for="inputPrice">Price</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                <div class="input-group-text">&#x20B9;</div>
                                </div>
                                <input type="text" class="form-control" id="inputPrice${key}" name="inputPrice${key}"  value="${value.price}" required>
                            </div>
                            </div>
                            <div class="form-group col-2">
                            <label for="inputDiscount">Discount</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                <div class="input-group-text">&#37;</div>
                                </div>
                                <input type="text" class="form-control" id="inputDiscount${key}" name="inputDiscount${key}"  value="" placeholder="0">
                            </div>
                            </div>
                            <div class=" form-group col-12" id="imageGroup${key}">
                            </div>
                        </div>`);
                $.each(value.images, function (k, val) {
                    if (val != "") {
                        //$(`#imageGroup${key}`).append(`<img src="images/vendor_product/${val}" class="img-fluid img-thumbnail img-md mr-3" alt="...">`);
                        $(`#imageGroup${key}`).append(`
                            <div class="img-container img-md float-left mr-2 ">
                            <input id="checkbox${key + "" + count}" type="checkbox" name="image_list${key + "" + count}" value="${val}" style="display:none;" checked>
                            <img id="img${key + "" + count}" src="images/vendor_product/${val}" class="img-fluid img-thumbnail img-md" alt="...">
                            <input type="file" id="imgchange${key + "" + count}" style="display:none"/>
                            <button type="button" onclick="changeImage('#imgchange${key + "" + count}');" class="btn text-primary img-btn">Change</button>
                            <label class="select-img container">
                                <input type="hidden" name="selected_img${key + "" + count}" value="-1" />
                                <input type="checkbox" id="${key + "" + count}" name="selected_img${key + "" + count}" class="myCheck" onclick="imageOrderSelect(this)">
                                <span class="checkmark" id="checkmark${key + "" + count}"></span>
                            </label>
                            </div>
                            `);
                        $(`#imgchange${key + "" + count}`).change(function () {
                            var id = $(this).attr("id");
                            id = id.slice(-2);
                            //alert(id);
                            readURL(this, id);
                        });
                        count++;
                        if (count == 5) {
                            $(`#OpenImgUpload${key}`).hide();
                        }
                    }
                });


                $(`#imageGroup${key}`).append(`
                        <input type="file" name="file" id="imgupload${key}" style="display:none"/>
                        <button id="OpenImgUpload${key}" type="button" style="font-size: 20px;" class="btn btn-outline-primary img-btn img-md "><i class="fa fa-plus" aria-hidden="true"></i></button>
                    `);
                if (count == 5) {
                    $(`#OpenImgUpload${key}`).hide();
                }
                $(`#OpenImgUpload${key}`).click(function () { $(`#imgupload${key}`).trigger('click'); });

                $(`#imgupload${key}`).change(function () {

                    var property = this.files[0];
                    var image_name = property.name;
                    var image_extension = image_name.split('.').pop().toLowerCase();
                    if (jQuery.inArray(image_extension, ['jpg', 'jpeg']) == -1) {
                        alert("Invalid Image File");
                        return;
                    }
                    var image_size = property.size;
                    if (image_size > 2000000) {
                        alert("Image file size is very big");
                    } else {

                        if (property) {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                //$().attr("src", e.target.result);
                                console.log(e);
                                $(`
                                    <div class="img-container img-md float-left mr-2 ">
                                    <input id="checkbox${key + "" + count}" type="checkbox" name="image_list${key + "" + count}" value="${(e.target.result).split(',')[1]}" style="display:none;" checked>
                                    <img id="img${key + "" + count}" src="${e.target.result}" class="img-fluid img-thumbnail img-md" alt="...">
                                    <input type="file" id="imgchange${key + "" + count}" style="display:none"/>
                                    <button type="button" onclick="changeImage('#imgchange${key + "" + count}');" class="btn text-primary img-btn">Change</button>
                                    <label class="select-img container">
                                        <input type="hidden" name="selected_img${key + "" + count}" value="-1" />
                                        <input type="checkbox" id="${key + "" + count}" name="selected_img${key + "" + count}" class="myCheck" onclick="imageOrderSelect(this)">
                                        <span class="checkmark" id="checkmark${key + "" + count}"></span>
                                    </label>
                                    </div>
                                    `).insertBefore(`#OpenImgUpload${key}`);
                                $(`#imgchange${key + "" + count}`).change(function () {
                                    var id = $(this).attr("id");
                                    id = id.slice(-2);
                                    //alert(id);
                                    readURL(this, id);
                                });

                                count++;
                                if (count == 5) {
                                    $(`#OpenImgUpload${key}`).hide();
                                }
                            }
                            reader.readAsDataURL(property);
                        }
                    }


                });

            });
        }
    });
}



$("#inputMainCategory").change(function () {
    var id = $("#inputMainCategory option:selected").attr("id");

    $("#inputSubCategory").empty();
    $("#inputSubCategory").append(`<option id="0" value="0">Other</option>`);
    $.ajax({
        url: "modals/getSubCategory.php",
        method: "GET",
        data: { "id": id },
        success: function (response) {
            var subCategory = JSON.parse(response);
            if (subCategory['error'] === undefined) {
                $.each(subCategory, function (key, value) {

                    $("#inputSubCategory").append(`
                    <option id="${value['id']}" value="${value['id']}">${value['category_name']}</option>
                    `);
                });
            }
        }
    });
    $("#inputMainCategory option[selected='selected']").attr("selected", false);
});

$("#inputSubCategory").change(function () {
    var id = $("#inputSubCategory option:selected").attr("id");

    $("#inputCategory").empty();
    $("#inputCategory").append(`<option id="0" value="0">Other</option>`);
    $.ajax({
        url: "modals/getCategory.php",
        method: "GET",
        data: { "id": id },
        success: function (response) {
            var Category = JSON.parse(response);
            if (Category['error'] === undefined) {
                $.each(Category, function (key, value) {

                    $("#inputCategory").append(`
                    <option id="${value['id']}" value="${value['id']}">${value['category_name']}</option>
                    `);
                });
            }
        }
    });
    $("#inputSubCategory option[selected='selected']").attr("selected", false);
});

$(document).ready(function () {

    $('.producteditbtn').on('click', function () {

        $('#productEditModel').modal('show');
        imgCounter = 1;
        $("#inputMainCategory option[selected='selected']").attr("selected", false);
        $("#inputSubCategory option[selected='selected']").attr("selected", false);
        $tr = $(this).closest('tr');
        var data = $tr.children('td').map(function () {
            return $(this).text();
        }).get();
        var id = $(this).closest(".producteditbtn").attr("id");
        $('#inputTitle').val(data[1]);
        $('#inputVariants').empty();
        $('#inputTags').tagsinput('removeAll');
        $('#inputRelatedProducts').tagsinput('removeAll');

        $.ajax({
            url: "modals/getProductDetails.php",
            method: "GET",
            data: { "pid": id },
            success: function (response) {
                var product = JSON.parse(response);
                $('#inputDescription').val(product.description);
                $('#inputTNC').val(product.terms_condition);
                $('#uploadMainImage').css({ "background-image": `url('images/vendor_product/${product.base_image}')` });
                $('#inputMainImage').attr("value", product.base_image);
                $('#inputTags').tagsinput('add', product.tags);
                $('#inputRelatedProducts').tagsinput('add', product.related_products);
                $(`#inputMainCategory option[value="${product.main_category}"]`).attr("selected", true);

                $("#inputSubCategory").empty();
                $("#inputSubCategory").append(`<option id="0" value="0">Other</option>`);
                $.ajax({
                    url: "modals/getSubCategory.php",
                    method: "GET",
                    data: { "id": product.main_category },
                    success: function (response) {
                        var subCategory = JSON.parse(response);
                        if (subCategory['error'] === undefined) {
                            $.each(subCategory, function (key, value) {

                                $("#inputSubCategory").append(`
                                <option id="${value['id']}" value="${value['id']}">${value['category_name']}</option>
                                `);
                            });
                            $(`#inputSubCategory option[value="${product.sub_category}"]`).attr("selected", true);
                        }
                    }
                });
                
                $("#inputCategory").empty();
                $("#inputCategory").append(`<option id="0" value="0">Other</option>`);
                $.ajax({
                    url: "modals/getCategory.php",
                    method: "GET",
                    data: { "id": product.sub_category },
                    success: function (response) {
                        var Category = JSON.parse(response);
                        if (Category['error'] === undefined) {
                            $.each(Category, function (key, value) {

                                $("#inputCategory").append(`
                                <option id="${value['id']}" value="${value['id']}">${value['category_name']}</option>
                                `);
                            });
                            $(`#inputCategory option[value="${product.category}"]`).attr("selected", true);
                        }
                    }
                });

            }
        });

        //$('#imageGroup').empty();

        $.ajax({
            url: "modals/productVariants.php",
            method: "GET",
            data: { "pid": id, "vendor_name": data[5] },
            success: function (response) {
                var variants = JSON.parse(response);
                $.each(variants, function (key, value) {
                    var count = 0;
                    $('#inputVariants').append(`<div class="form-row">
                            <div class="form-group col-5">
                            <label for="inputVariantTitle">Vairant Title</label>
                            <input type="text" class="form-control" id="inputtitle${key}" name="inputtitle${key}" value="${value.variant_name}" required>
                            </div>
                            <div class="form-group col-2">
                            <label for="inputQuantity">Quantity</label>
                            <input readonly  type="text" class="form-control" id="inputQuantity${key}" name="inputQuantity${key}" value="${value.quantity}" required>
                            </div>
                            <div class="form-group col-3">
                            <label for="inputPrice">Price</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                <div class="input-group-text">&#x20B9;</div>
                                </div>
                                <input type="text" class="form-control" id="inputPrice${key}" name="inputPrice${key}"  value="${value.price}" required>
                            </div>
                            </div>
                            <div class="form-group col-2">
                            <label for="inputDiscount">Discount</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                <div class="input-group-text">&#37;</div>
                                </div>
                                <input type="text" class="form-control" id="inputDiscount${key}" name="inputDiscount${key}"  value="${value.discount}" placeholder="0">
                            </div>
                            </div>
                            <div class=" form-group col-12" id="imageGroup${key}">
                            </div>
                        </div>`);
                    $.each(value.images, function (k, val) {

                        if (val != "") {
                            if (val.pos != -1) { imgCounter++; }
                            //$(`#imageGroup${key}`).append(`<img src="images/vendor_product/${val}" class="img-fluid img-thumbnail img-md mr-3" alt="...">`);
                            $(`#imageGroup${key}`).append(`
                                <div class="img-container img-md float-left mr-2 ">
                                <input id="checkbox${key + "" + count}" type="checkbox" name="image_list${key + "" + count}" value="${val.img}" style="display:none;" checked>
                                <img id="img${key + "" + count}" src="images/vendor_product/${val.img}" class="img-fluid img-thumbnail img-md" alt="...">
                                <input type="file" id="imgchange${key + "" + count}" style="display:none"/>
                                <button type="button" onclick="changeImage('#imgchange${key + "" + count}');" class="btn text-primary img-btn">Change</button>
                                <label class="select-img container">
                                    <input type="hidden" name="selected_img${key + "" + count}" value="-1" />
                                    <input type="checkbox" id="${key + "" + count}" name="selected_img${key + "" + count}" class="myCheck" onclick="imageOrderSelect(this)" ${val.pos == -1 ? '' : 'value="' + val.pos + '" checked'}>
                                    <span class="checkmark" id="checkmark${key + "" + count}">${val.pos == -1 ? '' : val.pos}</span>
                                </label>
                                </div>
                                `);
                            $(`#imgchange${key + "" + count}`).change(function () {
                                var id = $(this).attr("id");
                                id = id.slice(-2);
                                //alert(id);
                                readURL(this, id);
                            });
                            count++;

                        }
                    });

                    $(`#imageGroup${key}`).append(`
                            <input type="file" name="file" id="imgupload${key}" style="display:none"/>
                            <button id="OpenImgUpload${key}" type="button" style="font-size: 20px;" class="btn btn-outline-primary img-btn img-md "><i class="fa fa-plus" aria-hidden="true"></i></button>
                        `);

                    if (count == 5) {
                        $(`#OpenImgUpload${key}`).hide();
                    }

                    $(`#OpenImgUpload${key}`).click(function () { $(`#imgupload${key}`).trigger('click'); });

                    $(`#imgupload${key}`).change(function () {

                        var property = this.files[0];
                        var image_name = property.name;
                        var image_extension = image_name.split('.').pop().toLowerCase();
                        if (jQuery.inArray(image_extension, ['jpg', 'jpeg']) == -1) {
                            alert("Invalid Image File");
                            return;
                        }
                        var image_size = property.size;
                        if (image_size > 2000000) {
                            alert("Image file size is very big");
                        } else {

                            if (property) {
                                var reader = new FileReader();
                                reader.onload = function (e) {
                                    //$().attr("src", e.target.result);
                                    console.log(e);
                                    $(`
                                        <div class="img-container img-md float-left mr-2 ">
                                        <input id="checkbox${key + "" + count}" type="checkbox" name="image_list${key + "" + count}" value="${(e.target.result).split(',')[1]}" style="display:none;" checked>
                                        <img id="img${key + "" + count}" src="${e.target.result}" class="img-fluid img-thumbnail img-md" alt="...">
                                        <input type="file" id="imgchange${key + "" + count}" style="display:none"/>
                                        <button type="button" onclick="changeImage('#imgchange${key + "" + count}');" class="btn text-primary img-btn">Change</button>
                                        <label class="select-img container">
                                            <input type="hidden" name="selected_img${key + "" + count}" value="-1" />
                                            <input type="checkbox" id="${key + "" + count}" name="selected_img${key + "" + count}" class="myCheck" onclick="imageOrderSelect(this)">
                                            <span class="checkmark" id="checkmark${key + "" + count}"></span>
                                        </label>
                                        </div>
                                        `).insertBefore(`#OpenImgUpload${key}`);
                                    $(`#imgchange${key + "" + count}`).change(function () {
                                        var id = $(this).attr("id");
                                        id = id.slice(-2);
                                        //alert(id);
                                        readURL(this, id);
                                    });

                                    count++;
                                    if (count == 5) {
                                        $(`#OpenImgUpload${key}`).hide();
                                    }
                                }
                                reader.readAsDataURL(property);
                            }
                        }


                    });

                });
            }
        });


    });
});

$('#fileInputMainImage').change(function () {
   
    var property = this.files[0];
    var image_name = property.name;
    var image_extension = image_name.split('.').pop().toLowerCase();
    if (jQuery.inArray(image_extension, ['jpg', 'jpeg']) == -1) {
        alert("Invalid Image File");
        return;
    }
    var image_size = property.size;
    if (image_size > 2000000) {
        alert("Image file size is very big");
    } else {

        if (property) {
            var reader = new FileReader();
            reader.onload = function (e) {

                $('#uploadMainImage').css({ "background-image": `url('${e.target.result}')` });
                $('#inputMainImage').attr("value", (e.target.result).split(',')[1]);
                console.log(e);

            }
            reader.readAsDataURL(property);
        }
    }
    
});

function readURL(input, id) {
    var property = input.files[0];
    var image_name = property.name;
    var image_extension = image_name.split('.').pop().toLowerCase();
    if (jQuery.inArray(image_extension, ['jpg', 'jpeg']) == -1) {
        alert("Invalid Image File");
        return;
    }
    var image_size = property.size;
    if (image_size > 2000000) {
        alert("Image file size is very big");
    } else {

        if (property) {
            var reader = new FileReader();
            reader.onload = function (e) {

                $(`#img${id}`).attr("src", e.target.result);
                $(`#checkbox${id}`).attr("value", (e.target.result).split(',')[1]);
                console.log(e);

            }
            reader.readAsDataURL(property);
        }
    }
}

function deleteProducts(pid) {
    $('#modal-dialog').modal('show');
    $('#btnYes').attr("href", "modals/deleteProducts.php?pid=" + pid);
}

function getOrder(pid, variant_ids, quantity) {
    $('#productView').modal('show');
    $('#ModalLabel').text(pid);
    $.ajax({
        url: "modals/orderDetails.php",
        method: "GET",
        data: { "pid": pid, "variant_ids": variant_ids, "quantity": quantity },
        success: function (response) {
            var orderProducts = JSON.parse(response);
            $('#product_title').text(orderProducts['title']);
            $('#supplier_name').text(orderProducts['supplier_name']);
            $('#description').text(orderProducts['description']);
            console.log(orderProducts);
            $('#tableBody').empty();
            $('#sub_total').empty();
            var sum = 0;
            $.each(orderProducts['variants'], function (key, value) {
                $('#tableBody').append(`
                        <tr>
                            <th scope="row">${key + 1}</th>
                            <td>${value['variant_name']}</td>
                            <td>${value['quantity']}</td>
                            <td>${value['price']}</td>
                            <td>${value['price'] * value['quantity']}</td>
                        </tr>
                    `);
                sum = sum + (value['price'] * value['quantity']);
            });
            $('#sub_total').append(`${sum}`);

        }
    });
}

function orderPlaced(orderID) {
    //alert(orderID);
    $.ajax({
        url: "modals/orderPlaced.php",
        method: "GET",
        data: { "order_id": orderID },
        success: function (response) {
            location.reload();
            alert(response);
        }
    });
}

function publish(id) {
    //alert(id+' published'); 
    $.ajax({
        url: "modals/actionPublish.php",
        method: "GET",
        data: { "pid": id },
        success: function (response) {
            location.reload();
            alert(response);
        }
    });
}

function unpublish(id) {
    //alert(id+' unpublished');
    $.ajax({
        url: "modals/actionUnpublish.php",
        method: "GET",
        data: { "pid": id },
        success: function (response) {
            location.reload();
            alert(response);
        }
    });
}

function setSupplierName(id) {
    $('#setSupplierNameModal').modal('show');
    $('#inputSupplierID').val(id);
}

function changeBtnCSS(self, currentPermission) {
    
    if (self.innerText === "Deny") {
        self.innerText = "Grant";
    } else {
        self.innerText = "Deny";
    }

    if (currentPermission) {
        self.classList.toggle("btn-outline-success");
        self.classList.toggle("btn-outline-danger");
    } else {
        self.classList.toggle("btn-outline-danger");
        self.classList.toggle("btn-outline-success");
    }
}

function changeUserPermission(currentPermission, uid) {
    //alert(currentPermission + " " + uid);
    $.ajax({
        url: "modals/changePermission.php",
        method: "GET",
        data: { "uid": uid ,"currentPermission":currentPermission},
        success: function (response) {
            location.reload();
            //alert(response);
        }
    });
}