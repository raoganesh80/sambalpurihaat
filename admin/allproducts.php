<?php
session_start();
if(isset($_SESSION['LOGIN_STATUS'])){
    if(!$_SESSION['LOGIN_STATUS']===true){
      header('Location: login.php');
    }
}else{
  header('Location: login.php');
}
?>

<?php
    $active_page_name = 'allproducts';
    $active_section_name = 'products';
    $link = '
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="css/bootstrap-tagsinput.css" rel="stylesheet">
    <link href="css/typeaheadjs.css" rel="stylesheet">
    ';
    include('include/header.php');
?>
<!-- ============================================ -->
<?php
if(isset($_SESSION['PRODUCT_ID']) || isset($_SESSION['VENDOR_NAME']) || isset($_SESSION['VARIANT_ID'])){

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $product_id = $_SESSION['PRODUCT_ID'];
      $base_image = $_POST['inputMainImage'];
      $title = $_POST['inputTitle'];
      $description = $_POST['inputDescription'];
      $tags = $_POST['inputTags'];
      $related_products = $_POST['inputRelatedProducts'];
      $terms_condition = $_POST['inputTNC'];
      $vendor_name = $_SESSION['VENDOR_NAME'];
      $main_category = $_POST['inputMainCategory'];
      $sub_category = $_POST['inputSubCategory'];
      $category = $_POST['inputCategory'];

      if(empty(strpos($base_image,".jpeg"))){
        $target_dir = '../admin/images/vendor_product/';
        $imageFileName = $product_id."_".time().".jpeg";
        $target_dir = $target_dir."/".$imageFileName;
        file_put_contents($target_dir,base64_decode($base_image));
        $base_image = $imageFileName;
      }

      $variants = array();
      for($i=0;$i<count($_SESSION['VARIANT_ID']);$i++){
          $variants[$i]['variant_id'] = $_SESSION['VARIANT_ID'][$i];
          $variants[$i]['variant_name'] = $_POST['inputtitle'.$i];
          $variants[$i]['quantity'] = $_POST['inputQuantity'.$i];
          $variants[$i]['price'] = $_POST['inputPrice'.$i];
          $variants[$i]['discount'] = $_POST['inputDiscount'.$i];
          $variants[$i]['images'] = array();

          for($j=0;true;$j++){
              if(!isset($_POST['image_list'.$i."".$j])){
                break;
              }
              //echo $_POST['selected_img'.$i."".$j]." -> ".$_POST['image_list'.$i."".$j]."<br>";
              
              if(empty(strpos($_POST['image_list'.$i."".$j],".jpeg"))){
                $target_dir = '../admin/images/vendor_product/';
                $imageFileName = rand()."_".time().".jpeg";
                $target_dir = $target_dir."/".$imageFileName;
                file_put_contents($target_dir,base64_decode($_POST['image_list'.$i."".$j]));
                $_POST['image_list'.$i."".$j] = $imageFileName;
              }
              array_push($variants[$i]['images'],array("pos"=>$_POST['selected_img'.$i."".$j],"img"=>$_POST['image_list'.$i."".$j]));

            }
      }
      $db = new ProductDB;
      $success = $db->updateProduct($product_id,$base_image,$title,$description,$tags,$related_products,$terms_condition,$variants,$main_category,$sub_category,$category);
      if($success){
        echo '<div class="alert alert-success" role="alert">
        Product Updated
        </div>';
      }else{
        echo '<div class="alert alert-warning" role="alert">
        Proccess Failed
        </div>';
      }
      // remove session variable
      unset($_SESSION['PRODUCT_ID']);
      unset($_SESSION['VENDOR_NAME']);
      unset($_SESSION['VARIANT_IMAGES']);
      unset($_SESSION['VARIANT_ID']);
  }
}

?>
    <div id="error_msg"></div>

    <!-- Product Edit Form Modal -->
      <div class="modal fade" id="productEditModel" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Product Edit and Save</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="modal-body">
                  <div class="form-row">
                    <div class="form-group col">
                      <div class="form-group">
                        <label for="inputTitle">Title</label>
                        <input type="text" class="form-control" id="inputTitle" name="inputTitle"required>
                      </div>
                      <div class="form-group">
                        <label for="inputDescription">Description</label>
                        <textarea class="form-control" id="inputDescription" name="inputDescription" required></textarea>
                      </div>
                    </div>
                    <div class="form-group col-3 px-4">
                      <label for="inputMainImage">Add Main Image</label>
                      <div class="img-md">
                        <input type="hidden" id="inputMainImage" name="inputMainImage" value="">
                        <input type="file" name="fileInputMainImage" id="fileInputMainImage" style="display:none"/>
                        <button id="uploadMainImage" onclick="$('#fileInputMainImage').trigger('click');" type="button" style="font-size: 20px;background-size: cover;background-repeat: no-repeat;" class="btn btn-outline-primary img-btn img-md"><i class="fa fa-upload" aria-hidden="true"></i></button>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                        <label for="inputTags">Tags</label><br>
                        <input class="form-control" type="text" id="inputTags" name="inputTags" data-role="tagsinput" required>
                  </div>
                  
                  <div class="form-group">
                        <label for="inputRelatedProducts">Related Products</label><br>
                        <input type="text" id="inputRelatedProducts" name="inputRelatedProducts" data-role="tagsinput">
                  </div>

                  <div class="form-group">
                        <label for="inputTNC">Terms & Condition</label>
                        <textarea class="form-control" id="inputTNC" name="inputTNC" required></textarea>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-4">
                      <label for="inputTitle">Select Main Category</label>
                      <div class="form-group">
                          <select id="inputMainCategory" name="inputMainCategory" class="custom-select form-control">
                          <option id="0" value="0" selected>Other</option>
                          <?php  
                              $db = new ProductDB;
                              $result = $db->readMainCategory();
                              foreach($result as $value){
                                echo '<option id="'.$value['id'].'" value="'.$value['id'].'">'.$value['category_name'].'</option>';
                              }
                          ?>
                          </select>
                        </div> <!-- form-group end.// -->
                    </div>
                    <div class="form-group col-md-4">
                      <label for="inputCompany">Select Sub Category</label>
                      <div class="form-group">
                        <select id="inputSubCategory" name="inputSubCategory" class="custom-select form-control">
                          <option id="0" value="0" selected>Other</option>
                        </select>
                      </div> <!-- form-group end.// -->
                    </div>

                    <div class="form-group col-md-4">
                      <label for="inputCategory">Select Category</label>
                      <div class="form-group">
                        <select id="inputCategory" name="inputCategory" class="custom-select form-control">
                          <option value="0">Other</option>
                        </select>
                      </div> <!-- form-group end.// -->
                    </div>

                  </div>

                  <div class="form-group" id="inputVariants">
                    <div class="form-row">
                      <div class="form-group col-6">
                        <label for="inputVariantTitle">Vairant Title</label>
                        <input type="text" class="form-control" id="inputVariantTitle" name="inputVariantTitle" required>
                      </div>
                      <div class="form-group col">
                        <label for="inputQuantity">Quantity</label>
                        <input readonly type="text" class="form-control" id="inputQuantity" name="inputQuantity" required>
                      </div>
                      <div class="form-group col">
                        <label for="inputPrice">Price</label>
                        <div class="input-group mb-2">
                          <div class="input-group-prepend">
                            <div class="input-group-text">&#x20B9;</div>
                          </div>
                          <input type="text" class="form-control" id="inputPrice" name="inputPrice" required>
                        </div>
                      </div>
                      <div class=" form-group col-12" id="imageGroup">
                      </div>
                    </div>
                  </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
          </div>
        </div>
      </div>


       <!-- Page Heading -->
       <h1 class="h3 mb-2 text-gray-800">Products</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
          <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"></h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">

            <?php

              $db = new ProductDB;
              $products = $db->getAllProducts();
              
              $table_heading=array('Product Image','Title','Main Category','Supplier Name','Publish Date','Action');
              
              if(!empty($products)){
                
                //print_r($products[0]['variants'][0]['image'][0]);
                echo '<table class="table table-bordered" id="product-table" width="100%" cellspacing="0"><thead><tr>';
                foreach($table_heading as $heading){
                  echo '<th>'.$heading.'</th>';
                }
                echo '</tr></thead><tfoot><tr>';
                foreach($table_heading as $heading){
                  echo '<th>'.$heading.'</th>';
                }
                echo '</tr></tfoot><tbody>';
                
                foreach($products as $row){
                  echo '<tr>
                  <td class=" align-middle"><img src="images/vendor_product/'.$row['base_image'].'" class="img-sm"></td>
                  <td class=" align-middle">'.$row['title'].'</td>
                  <td class=" align-middle">'.$row['main_category'].'</td>
                  <td class=" align-middle">'.$row['supplier_name'].'</td>
                  <td class=" align-middle">'.$row['publish_date'].'</td>
                  <td class=" align-middle">
                    <!--Button trigger modal -->
                    <div class=" d-flex"><button title="Edit" type="button" id="'.$row['product_id'].'" class="btn m-2 btn-outline-primary producteditbtn"><i class="fa fa-edit"></i></button>
                    <button title="Delete" type="button" onclick="deleteProducts(\''.$row["product_id"].'\');" class="btn m-2 btn-outline-danger"><i class="fa fa-trash" aria-hidden="true"></i></button></div><button type="button" onclick="'.(($row["publish_status"]==="unpublished")?"publish('".$row['product_id']."')":"unpublish('".$row['product_id']."')").'" class="btn m-2 '.(($row["publish_status"]==="unpublished")?"btn-outline-success":"btn-outline-danger").' publish-btn">'.(($row["publish_status"]==="unpublished")?"Publish":"Un-Publish").'</button>
                  </td>
                  </tr>';
                }
                echo '</tbody></table>';

              }else{
                echo '<h4>Empty Products</h4>';
              }
              
              ?>


            </div>
          </div>
        </div>

        
      <div class="modal fade" id="modal-dialog" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Delete Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <p>Do you want to delete this product?</p>
              </div>
              <div class="modal-footer">
                <a href="#" id="btnYes" class="btn confirm">Yes</a>
                <a href="#" data-dismiss="modal" aria-hidden="true" class="btn secondary">No</a>
              </div>
            </div>
          </div>
        </div>
<!-- ============================================ -->
<?php
    $script = '
            <!-- Page level plugins -->
         <script src="vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="js/demo/datatables-demo.js"></script>
        <script src="js/table-orders.js"></script>
        <script src="js/bootstrap-tagsinput.js"></script> 
        <script src="js/typeahead.bundle.js"></script> 
        <script>
        $("#inputTags").tagsinput({
            confirmKeys: [13, 44 ],
            maxTags: 15,
            trimValue: true

        });

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
        $("#inputRelatedProducts").tagsinput({
            maxTags: 15,
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