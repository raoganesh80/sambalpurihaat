

<!-- ============================================ -->
<?php
    $title = 'Admin Dashboard';
    $active_page_name = 'add_categories';
    $active_section_name = 'Tools';
    
    include('include/header.php');

?>
<!-- ============================================ -->

<!--Rename Modal -->
<div class="modal fade" id="ChangeNameModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form id="rename-category-form" action="" method="post">
              <div class="modal-body">
              <div class="form-row">
                    <div class="form-group col">
                      <input type="text" class="form-control" id="inputCategoryName" name="inputCategoryName" placeholder="Enter Name" required>
                      <input type="text" class="form-control" id="inputID" name="inputID" style="display:none;">
                    </div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
              </form>
            </div>
          </div>
        </div>

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Add Categories</h1>

<div class="card shadow mb-4">
    <!-- <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"></h6>
    </div> -->
    <div class="card-body"> 

    <div class="row">
        <div class="col-12 col-lg-4 p-5 main-category">
            <h5>Add Main Categories</h5>
            <form method="post" action="modals/addMainCategory.php" class="my-5">
                <div class="form-row">
                    <div class="col-12 form-group">
                        <input type="text" class="form-control" placeholder="Main Category Name" name="category_name" required>
                    </div> <!-- form-group end.// -->
                    <div class="col-12 form-group">
                        <button type="submit" class="btn btn-primary btn-block"> ADD </button>
                    </div> <!-- form-group// -->
                </div> <!-- form-row end.// -->
                
            </form>
            <div class="row"><button id="btnCategoryIcon" type="button" class="category-btn btn btn-light float-right">Set Icon</button><input type="file" name="category_icon" id="category_icon" class="hide"></div>
            
            <h5>Main Category List</h5>
            <ul class="list-group scrollbox">
            <?php
                $main_category = (new ProductDB)->readMainCategory();
                foreach($main_category as $key => $category){
                    if($key==0){
                        echo '<li id="'.$category['id'].'" style="font-size:15px;" class=" main-category-item list-group-item"><div class="float-left d-flex align-items-center"><img id="icon'.$category['id'].'" src="images/category_icons/'.$category['icon'].'" class=" float-left  img-xs mr-3"><span id="name'.$category['id'].'" style="width:230px;" class="text-wrap">'.$category["category_name"].'</span></div><div class="float-right"><button title="Rename" type="button" class="btn m-2 btn-outline-light" data-toggle="modal" onclick="renameMainCategory('.$category['id'].');"><i class="fa fa-edit"></i></button><button title="Delete" type="button" class="btn m-2 btn-outline-light" data-toggle="modal" onclick="deleteMainCategory('.$category['id'].');"><i class="fa fa-trash" aria-hidden="true"></i></button></div></li>';
                    }else{
                        echo '<li id="'.$category['id'].'" style="font-size:15px;" class=" main-category-item list-group-item"><div class="float-left d-flex align-items-center"><img id="icon'.$category['id'].'" src="images/category_icons/'.$category['icon'].'" class=" float-left  img-xs mr-3"><span id="name'.$category['id'].'" style="width:230px;" class="text-wrap">'.$category["category_name"].'</span></div><div class="float-right"><button title="Rename" type="button" class="btn m-2 btn-outline-light" data-toggle="modal" onclick="renameMainCategory('.$category['id'].');"><i class="fa fa-edit"></i></button><button title="Delete" type="button" class="btn m-2 btn-outline-light" data-toggle="modal" onclick="deleteMainCategory('.$category['id'].');"><i class="fa fa-trash" aria-hidden="true"></i></button></div></li>';
                    }
                } 
            ?>
            </ul> 
        </div>
        <div class="col-12 col-lg-4  p-5 sub-category">
            <h5>Add Sub Categories</h5>
            <form id="subcategoryform" method="post" enctype="multipart/form-data" action="modals/addSubCategory.php?" class="my-5">
                <div class="form-row">
                    <div class="col-12 form-group">
                        <input type="text" class="form-control" placeholder="Sub Category Name" name="category_name" required>
                    </div> <!-- form-group end.// -->
                    <div class="col-12 form-group">
                        <button type="submit" class="btn btn-primary btn-block"> ADD </button>
                    </div> <!-- form-group// -->
                </div> <!-- form-row end.// -->
            </form>
            <h5>Sub Category List</h5>
            <ul id="sub-category-list" class="list-group scrollbox"> 
            
            </ul> 
        </div>
        <div class="col-12 col-lg-4  p-5 category">
            <h5>Add Categories</h5>
            <form id="categoryform" method="post" enctype="multipart/form-data" action="modals/addCategory.php?" class="my-5">
                <div class="form-row">
                    <div class="col-12 form-group">
                        <input type="text" class="form-control" placeholder="Category Name" name="category_name" required>
                    </div> <!-- form-group end.// -->
                    <div class="col-12 form-group">
                        <button type="submit" class="btn btn-primary btn-block"> ADD </button>
                    </div> <!-- form-group// -->
                </div> <!-- form-row end.// -->
            </form>
            <h5>Category List</h5>
            <ul id="category-list" class="list-group scrollbox"> 
            
            </ul> 
        </div>
    </div>

    </div>
</div>

<div class="modal fade" id="delete-modal-dialog" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Delete Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <p>Do you want to delete this Category?</p>
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
    $script='
    <script src="js/category_model.js"></script>
    ';
    include('include/footer.php');
?>
<!-- ============================================ -->