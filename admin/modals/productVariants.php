<?php
session_start();
    require '../../public/includes/ProductDB.php';
    $db = new ProductDB;
    $variants = $db->getVariants($_GET['pid']);
    $_SESSION['PRODUCT_ID']=$_GET['pid'];
    $_SESSION['VENDOR_NAME']=$_GET['vendor_name'];
    if(!empty($variants)){
        for($i=0;$i<count($variants);$i++){
            $_SESSION['VARIANT_IMAGES'][$i]=$variants[$i]['images'];
            $_SESSION['VARIANT_ID'][$i]=$variants[$i]['variant_id'];
        }
    }
    echo json_encode($variants);
    
?>