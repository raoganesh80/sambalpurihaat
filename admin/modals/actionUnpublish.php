<?php

require '../../public/includes/ProductDB.php';
    $db = new ProductDB;
    $result = $db->unPublishProduct($_GET['pid']);
    $msg=null;
    if($result){
        $msg = 'Product Un-Published Successfully';
    }
    echo $msg;
?>