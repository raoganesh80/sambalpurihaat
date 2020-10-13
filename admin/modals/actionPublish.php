<?php

require '../../public/includes/ProductDB.php';
    $db = new ProductDB;
    $result = $db->publishProduct($_GET['pid']);
    $msg=null;
    if($result){
        $msg = 'Product Published Successfully';
    }
    echo $msg;
?>