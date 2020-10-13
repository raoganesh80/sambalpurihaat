<?php
if(isset($_GET['data'])){
    require '../../public/includes/VendorDB.php';
    $db = new VendorDB;
    $promotional_suppliers_list = $_GET['data'];
    $result = $db->savePromotional_suppliers($promotional_suppliers_list);
    if($result){
        $msg = 'Successfully Updated';
    }
    else{
        $msg = 'Failed to Update';
    }
    echo $msg;
}else
echo 'Add altest one supplier.';
    
?>