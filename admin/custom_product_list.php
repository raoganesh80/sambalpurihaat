

<!-- ============================================ -->
<?php
    $title = 'Admin Dashboard';
    $active_page_name = 'custom_product_list';
    $active_section_name = 'Tools';
    $link = '
    <link href="css/bootstrap-tagsinput.css" rel="stylesheet">
    <link href="css/typeaheadjs.css" rel="stylesheet">
    ';
    include('include/header.php');
    $db = new ProductDB;
    $listOfProducts = $db->getProductListOfList();
?>
<!-- ============================================ -->

<!-- Page Heading -->


<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h1 class="h4 mb-2 text-gray-600">Create Custom Product List</h1>
    </div>
    <div class="card-body"> 
        <form id="create-list1-form">
            <h5>1st List</h5>
            <div class="form-row">
                <div class="col-12 form-group">
                    <input type="text" class="form-control" placeholder="Add Products" id="inputItems1" name="inputItems1" data-role="tagsinput" value="<?php echo $listOfProducts[0]['list_items'] ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 col-lg-3 form-group">
                    <input type="text" class="form-control" placeholder="Name of list" id="inputName1" name="inputName1" value="<?php echo $listOfProducts[0]['list_name'] ?>" required>
                </div> <!-- form-group end.// -->
                <div class="col-12 col-lg-3  form-group">
                    <button type="submit" class="btn btn-primary btn-block"> Save </button>
                </div> <!-- form-group// -->
            </div>
        </form>
        <div class="dropdown-divider mb-4"></div>
        <form id="create-list2-form">
            <h5>2nd List</h5>
            <div class="form-row">
                <div class="col-12 form-group">
                    <input type="text" class="form-control" placeholder="Add Products" id="inputItems2" name="inputItems2" data-role="tagsinput" value="<?php echo $listOfProducts[1]['list_items'] ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 col-lg-3 form-group">
                    <input type="text" class="form-control" placeholder="Name of list" id="inputName2" name="inputName2" value="<?php echo $listOfProducts[1]['list_name'] ?>" required>
                </div> <!-- form-group end.// -->
                <div class="col-12 col-lg-3  form-group">
                    <button type="submit" class="btn btn-primary btn-block"> Save </button>
                </div> <!-- form-group// -->
            </div>
        </form>
        <div class="dropdown-divider mb-4"></div>
        <form id="create-list3-form">
            <h5>3rd List</h5>
            <div class="form-row">
                <div class="col-12 form-group">
                    <input type="text" class="form-control" placeholder="Add Products" id="inputItems3" name="inputItems3" data-role="tagsinput" value="<?php echo $listOfProducts[2]['list_items'] ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="col-12 col-lg-3 form-group">
                    <input type="text" class="form-control" placeholder="Name of list" id="inputName3" name="inputName3" value="<?php echo $listOfProducts[2]['list_name'] ?>" required>
                </div> <!-- form-group end.// -->
                <div class="col-12 col-lg-3  form-group">
                    <button type="submit" class="btn btn-primary btn-block"> Save </button>
                </div> <!-- form-group// -->
            </div>
        </form>
    </div>
</div>

<!-- ============================================ -->
<?php
    $script='
    <script src="js/bootstrap-tagsinput.js"></script> 
    <script src="js/typeahead.bundle.js"></script> 
    <script>
        var product_names = new Bloodhound({
          datumTokenizer: Bloodhound.tokenizers.obj.whitespace("title"),
          queryTokenizer: Bloodhound.tokenizers.whitespace,
          prefetch: {
            url: "../public/getallproducts",
            filter: function(list) {
              return $.map(list.products, function(product_name) {
                //console.log(product_name.title);
                return { title: product_name.title ,product_id: product_name.product_id}; });
            },
            cache: false
          }
        });
        product_names.initialize();
        $("#inputItems1").tagsinput({
            maxTags: 18,
            typeaheadjs: {
              name: "product_names",
              displayKey: "title",
              valueKey: "product_id",
              source: product_names.ttAdapter()
            },
            freeInput: false
        });
        $("#inputItems2").tagsinput({
            maxTags: 18,
            typeaheadjs: {
              name: "product_names",
              displayKey: "title",
              valueKey: "product_id",
              source: product_names.ttAdapter()
            },
            freeInput: false
        });
        $("#inputItems3").tagsinput({
            maxTags: 18,
            typeaheadjs: {
              name: "product_names",
              displayKey: "title",
              valueKey: "product_id",
              source: product_names.ttAdapter()
            },
            freeInput: false
        });
    </script>
    
    ';
    include('include/footer.php');
?>
<!-- ============================================ -->