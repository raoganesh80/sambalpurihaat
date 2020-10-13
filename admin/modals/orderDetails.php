<?php

    require '../../public/includes/ProductDB.php';
    $db = new ProductDB;
    //print_r( $_GET['quantity']);
    $orderProduct = $db->getOrderedProductInfo($_GET['pid'],$_GET['variant_ids']);
    //print_r($orderProduct);
    for($i=0;$i<count($_GET['quantity']);$i++){
        $orderProduct['variants'][$i]["quantity"]=$_GET['quantity'][$i];
    }
    echo json_encode($orderProduct);

?>