<?php
    require '../../public/includes/ProductDB.php';
    $db = new ProductDB;
    $subCategory = $db->readSubCategory($_GET['id']);
    if(!empty($subCategory)){
        echo json_encode($subCategory);
    }else{
        echo '{"error":"Data not found!"}';
    }
    
?>