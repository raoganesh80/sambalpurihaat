

<!-- ============================================ -->
<?php
    $title = 'Admin Dashboard';
    $active_page_name = 'promotional_suppliers';
    $active_section_name = 'Tools';
    
    include('include/header.php');
?>
<!-- ============================================ -->

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Promotional Suppliers</h1>

<div class="card shadow mb-4">
    <!-- <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"></h6>
    </div> -->
    <div class="card-body"> 

    <div class="row">
        <div class="col-3 p-5 main-category">
            
            <h5>Supplier List</h5>
            <?php 
            $db = new VendorDB;
            $users = $db->getUnSelectedSuppliers();
            $selected_suppliers = $db->getSelectedPromotionalSuppliers();
            ?>
            <select multiple="multiple" class="list-group scrollbox" name="listbox1" id="select1">
                <?php if(!empty($users)){
                    foreach($users as $user){
                        echo '<option class="list-group-item" value="'.$user['uid'].'">'.$user['supplier_name'].'</option>';
                    }
                    
                } ?>
            </select>
        </div>
        <div class="col-3 p-5 ">
            <button type="button" class="btn btn-primary btn-block mt-5" id="add">ADD &gt;&gt;</button><br/>
            <button type="button" class="btn btn-primary btn-block" id="remove">&lt;&lt; REMOVE</button>
        </div>
        <div class="col-3 p-5 sub-category">
            
            <h5>Selected Supplier List</h5>
            <select multiple="multiple" class="list-group scrollbox"  name="listbox2" id="select2">
                <?php if(!empty($selected_suppliers)){
                    foreach($selected_suppliers as $supplier){
                        echo '<option class="list-group-item" value="'.$supplier['supplier_id'].'">'.$supplier['supplier_name'].'</option>';
                    }
                    
                } ?>
            </select>
            
        </div>
        <div class="col-3 p-5 ">
            <button id="up2">&uarr;</button>
            <button id="down2">&darr;</button><br>
            
            <button type="button" id="btnSaveChanges" class="btn btn-primary btn-block m-5"> SAVE CHANGES </button>
            
            
        </div>
    </div>

    </div>
</div>

<!-- ============================================ -->
<?php
    $script='
    <script src="js/category_model.js"></script>
    <script src="js/tools.js"></script>
    
    ';
    include('include/footer.php');
?>
<!-- ============================================ -->