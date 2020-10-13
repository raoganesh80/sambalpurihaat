<?php
    require '../../public/includes/VendorDB.php';
    $db = new VendorDB;
    $product = $db->getProductDetails($_GET['pid']);
    echo json_encode($product);
?>