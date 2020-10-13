<?php
    require '../../public/includes/ProductDB.php';
    $db = new ProductDB;
    $Category = $db->readCategory($_GET['id']);
    if(!empty($Category)){
        echo json_encode($Category);
    }else{
        echo '{"error":"Data not found!"}';
    }
    
?>