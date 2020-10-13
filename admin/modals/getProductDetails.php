<?php
    require '../../public/includes/ProductDB.php';
    $db = new ProductDB;
    $product = $db->getProductDetails($_GET['pid']);
    echo json_encode($product);
?>