<?php

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    require '../../public/includes/ProductDB.php';
    $db = new ProductDB;
    $result = $db->orderPlaced($_GET['order_id']);
    $msg=null;
    if($result){
        $msg = 'Product Successfully Reached';
    }
    echo $msg;
}
?>