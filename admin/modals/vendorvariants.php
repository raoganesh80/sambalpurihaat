<?php
session_start();
    require '../../public/includes/VendorDB.php';
    $db = new VendorDB;
    $variants = $db->getVariants($_GET['pid']);
    $_SESSION['PRODUCT_ID']=$_GET['pid'];
    $_SESSION['VENDOR_ID']=$_GET['vendor_id'];
    if(!empty($variants)){
        for($i=0;$i<count($variants);$i++){
            $_SESSION['VARIANT_IMAGES'][$i]=$variants[$i]['images'];
            $_SESSION['VARIANT_ID'][$i]=$variants[$i]['variant_id'];
        }
    }
    echo json_encode($variants);
    
?>